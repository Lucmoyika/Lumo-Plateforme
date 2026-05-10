# Plan de Finition Module Écoles - 2026-05-08

## 🎯 Vue d'ensemble

Le module écoles est **70% complet**. Il manque :
1. Rôles complets (parent, assistant, etc.)
2. Contrôles d'accès granulaires
3. Sécurité stricte sur les endpoints
4. Archivage annuel complet
5. Guide de test avec utilisateurs

---

## 📋 Phase 1 : Rôles et Permissions

### Rôles à implémenter/ajuster :

#### 1. **school_admin** (Directeur/Directrice)
- Peut gérer entièrement l'école
- Permissions : schools.*, school-classes.*, students.*, teachers.*, attendance.*, grades.*

#### 2. **school_staff** (Personnel administratif)
- Peut assister le directeur
- Permissions : school-classes.view, students.view, attendance.create, grades.view

#### 3. **teacher** (Enseignant)
- Gère sa classe et ses notes
- Permissions : students.view, grades.create, grades.update, attendance.create, videos.view
- Restriction : Voir uniquement ses classes

#### 4. **substitute_teacher** (Remplaçant)
- Remplace enseignant temporairement
- Permissions : teacher permissions + temporalité
- Utilise : SchoolPermissionDelegation

#### 5. **student** (Élève)
- Voit son bulletin et présences
- Permissions : grades.view (propres notes), attendance.view (propres présences)
- Restriction : Voir uniquement ses données

#### 6. **parent** (Parent/Tuteur) 🆕
- Voit notes et présences de ses enfants
- Permissions : grades.view, attendance.view
- Restriction : Enfants liés via Student.parent_id

#### 7. **assistant** (Assistant de direction) 🆕
- Aide administratif + gestion RH
- Permissions : school-classes.view, students.view, teachers.view, attendance.create, school_tasks.view

---

## 🔐 Phase 2 : Contrôles d'Accès et Sécurité

### Principes clés :
1. **Vérifier la propriété réelle des IDs** (pas juste le prefix route)
2. **Isolation stricte des données** par école
3. **Aucun accès par défaut** au super_admin (doit avoir rôle)
4. **Validation de l'academic_year**
5. **Révocation des permissions temporelles**

### Controllers à sécuriser :

**[1] SchoolController**
- ✅ show() : Vérifier user = school.director_id OU staff de l'école
- ❌ Accès croisé = 403

**[2] StudentController**
- ✅ index() : Filtrer par school du user
- ✅ show(id) : Vérifier student.school_id = user.school_id
- ✅ store() : Vérifier class_id appartient à la même école
- Autorisation: school_admin, school_staff, teacher

**[3] GradeController**
- ✅ store() : Vérifier grade.class_id.school_id = user.school_id
- ✅ Vérifier student_id dans la même classe
- ✅ parent : peut voir SEULEMENT ses enfants
- ✅ student : peut voir SEULEMENT ses notes
- Autorisation: school_admin, teacher, parent (données enfant)

**[4] AttendanceController**
- ✅ create() : Vérifier class_id.school_id = user.school_id
- ✅ Vérifie student_id dans la classe
- Autorisation: school_admin, teacher

**[5] TeacherController**
- ✅ Enseignant voit uniquement ses classes via /teachers/{id}/classes
- ✅ getClasses() : Vérifier teacher.school_id = user.school_id

**[6] SchoolClassController**
- ✅ updateSchedule() : Vérifier teacher_id & class_id même école
- ✅ Vérifier academic_year valide

---

## 🗂️ Phase 3 : Archivage Annuel

### Structure BD existante ✅
- Colonnes `archived_at` sur : school_classes, students, teachers, schedules
- Listes excluent par défaut les records archivés

### À implémenter :

**[1] Endpoint archivage** 
```
POST /api/schools/{school}/school-years/{year}/archive
- Archive : classes, students, teachers (statut inactive), schedules
- Réinitialise : grades, attendance pour l'année suivante
- Transition: academic_year++ in school
```

**[2] Endpoint restauration**
```
PUT /api/schools/{school}/school-years/{year}/restore
- Restaure soft-deleted records
- Remet les statuts à 'active'
```

**[3] Filtres**
```
GET /api/schools/{school}/students?academic_year=2024
GET /api/schools/{school}/teachers?academic_year=2024
GET /api/schools/{school}/classes?academic_year=2024
- Filtre par academic_year
- Exclut archived par défaut (inclus si ?include_archived=true)
```

---

## 🛡️ Phase 4 : Modèles de Sécurité (Policy)

### Implémenter Gates/Policies :

**StudentPolicy**
```php
canView(User $user, Student $student)
  - school_admin de student.school_id
  - teacher de student.class_.school_id
  - parent_id = user.id
  - student_id = user.id
  - else: DENY

canUpdate(User $user, Student $student)
  - school_admin ou school_staff de l'école
  - else: DENY

canDelete(User $user, Student $student)
  - school_admin uniquement
  - else: DENY
```

**GradePolicy**
```php
canViewGrade(User $user, Grade $grade)
  - school_admin
  - teacher du grade.class_
  - parent dont student = grade.student_id
  - student lui-même
  - else: DENY

canCreateGrade(User $user, Grade $grade)
  - teacher de grade.class_
  - school_admin
  - else: DENY
```

**AttendancePolicy**
```php
canViewAttendance(User $user, Attendance $attendance)
  - school_admin
  - teacher du student.class_
  - parent de l'étudiant
  - student lui-même
  - else: DENY

canCreateAttendance(User $user, Attendance $attendance)
  - teacher ou school_admin
  - else: DENY
```

---

## 📊 Phase 5 : Seeders Utilisateurs Test

Créer `database/seeders/SchoolTestUsersSeeder.php` :

### École de test : "École Excellence Test"
Directeur: admin@school.test
Staff: staff@school.test
Enseignants: 
  - prof1@school.test (CP1 - Français)
  - prof2@school.test (CP1 - Maths)
Classes: CP1, CP2, CE1
Élèves: 
  - student1@school.test → CP1
  - student2@school.test → CP1
  - student3@school.test → CP2
Parents:
  - parent1@school.test (parent de student1)
  - parent2@school.test (parent de student2 & student3)
Assistants: assistant@school.test
Substituts: sub_teacher@school.test (peut remplacer prof1)

---

## ✅ Phase 6 : Tests Utilisateurs

### Scénarios de test :

#### **Scénario 1 : Directeur**
```
Connexion: admin@school.test / password
Actions:
  1. Accéder /schools/my → Voir l'école
  2. Gérer élèves → Ajouter student4
  3. Gérer enseignants → Ajouter prof3
  4. Voir toutes les notes de l'école
  5. Archiver l'année 2024 → POST /api/schools/1/school-years/2024/archive
  6. Restaurer l'année → PUT /api/schools/1/school-years/2024/restore
Vérifications:
  ✅ Accès complet à l'école
  ✅ Pas d'accès aux autres écoles
  ✅ Archivage fonctionne
```

#### **Scénario 2 : Enseignant**
```
Connexion: prof1@school.test / password
Actions:
  1. Voir /teachers/{id}/classes → Sa classe CP1
  2. Accéder /classes/1 → CP1 détails
  3. Créer notes pour student1 → POST /api/grades
  4. Créer présences → POST /api/attendance
  5. Voir /classes/2 → Accès DÉNIÉ (CE1, pas sa classe)
  6. Voir /teachers/2/classes → Accès DÉNIÉ (prof2, autre enseignant)
Vérifications:
  ✅ Voit uniquement ses classes
  ✅ Peut créer notes/présences
  ✅ Pas d'accès croisé
  ✅ Autres enseignants masqués
```

#### **Scénario 3 : Élève**
```
Connexion: student1@school.test / password
Actions:
  1. GET /api/my-grades → Voit ses notes SEULEMENT
  2. GET /api/my-attendance → Voit ses présences
  3. GET /api/students → Essai liste → Accès DÉNIÉ
  4. GET /api/students/3 (autre étudiant) → DÉNIÉ
Vérifications:
  ✅ Voit uniquement ses données
  ✅ Pas d'accès à autres élèves
```

#### **Scénario 4 : Parent**
```
Connexion: parent1@school.test / password
Actions:
  1. GET /api/my-children → [student1]
  2. GET /api/my-children/1/grades → Notes de student1
  3. GET /api/my-children/1/attendance → Présences student1
  4. GET /api/my-children/3/grades (parent2's child) → DÉNIÉ
  5. GET /api/students/2/grades → DÉNIÉ (direct access)
Vérifications:
  ✅ Voit SEULEMENT ses enfants
  ✅ Pas d'accès croisé parents
```

#### **Scénario 5 : Assistant**
```
Connexion: assistant@school.test / password
Actions:
  1. GET /api/schools/1 → École
  2. GET /api/school-classes → Classes
  3. GET /api/students → Élèves
  4. POST /api/teachers (créer) → DÉNIÉ
  5. POST /api/attendance → Créer présence ✅
Vérifications:
  ✅ Voit données basiques
  ✅ Peut créer présences
  ✅ Pas de création enseignant
```

#### **Scénario 6 : Super Admin**
```
Connexion: super@app.test / password
Actions:
  1. GET /api/schools/1 → DÉNIÉ (pas directeur/staff)
  2. Assigner rôle school_admin à super → Assigne à lui-même
  3. GET /api/schools/1 → ✅ Accès
Vérifications:
  ✅ Isolation stricte respectée
  ✅ Doit avoir rôle pour accéder
```

#### **Scénario 7 : Permissions temporelles**
```
Création: substitute pour prof1 valide jusqu'à 2026-05-15
Actions:
  1. sub_teacher@school.test crée notes
  2. Dates < 2026-05-15 : ✅ Autorisé
  3. Dates > 2026-05-15 : ❌ DÉNIÉ (expiration)
  4. Revoke permission : DELETE /api/.../delegation/1
  5. Tentative création → ❌ DÉNIÉ
Vérifications:
  ✅ Permissions temporelles fonctionnent
  ✅ Expiration respectée
  ✅ Revocation effective
```

#### **Scénario 8 : Archivage Données**
```
Avant archivage : student1, prof1 actifs, 45 grades CP1
Actions:
  1. POST /api/schools/1/school-years/2024/archive
Après:
  - CP1 archived_at = 2026-05-08
  - student1 archived_at = 2026-05-08
  - prof1 archived_at = 2026-05-08
  - Grades conservés mais pas restaurés
  2. GET /api/students (default) → 0 students (tous archivés)
  3. GET /api/students?include_archived=true → student1 visible archived
  4. PUT /api/schools/1/school-years/2024/restore
Après restore:
  - archived_at = NULL
  - Tous reretrievable normalement
  5. GET /api/students → student1 visible again
Vérifications:
  ✅ Archivage complet
  ✅ Filtrage par défaut correct
  ✅ Restauration correcte
```

---

## 📝 Checklist d'Implémentation

### Rôles & Permissions
- [ ] Mettre à jour RolePermissionSeeder avec rôles complets
- [ ] Créer SchoolPolicy (autorisation accès école)
- [ ] Créer StudentPolicy
- [ ] Créer GradePolicy
- [ ] Créer AttendancePolicy
- [ ] Créer TeacherPolicy

### Controllers
- [ ] SchoolController::show() - Ajouter vérification school scope
- [ ] StudentController::* - Ajouter vérifications d'accès
- [ ] GradeController::* - Ajouter vérifications d'accès + parent permissions
- [ ] AttendanceController::* - Ajouter vérifications d'accès
- [ ] TeacherController::* - Ajouter vérifications d'accès
- [ ] SchoolClassController::* - Ajouter vérifications d'accès

### Models
- [ ] Ajouter accessor percentage à Grade
- [ ] Ajouter scopes archived/active à tous les modèles
- [ ] Vérifier relations parent->students

### Services
- [ ] Ajouter logique archivage annuel à SchoolService
- [ ] Ajouter permission delegation logic à PermissionService
- [ ] Ajouter validation academic_year partout

### Seeders
- [ ] Créer SchoolTestUsersSeeder
- [ ] Ajouter données test grades/attendance
- [ ] Ajouter dans DatabaseSeeder

### Documentation
- [ ] Créer guide API endpoints sécurisés
- [ ] Créer guide test complet
- [ ] Documenter rôles & permissions

---

## 🚀 Ordre d'Exécution Recommandé

1. **Rôles & Permissions** (30 min)
2. **Policies & Gates** (45 min)
3. **Sécurité Controllers** (90 min)
4. **Archivage Annuel** (60 min)
5. **Seeders Test** (30 min)
6. **Tests Manuels** (120 min)

**Total : ~5-6 heures de travail**

