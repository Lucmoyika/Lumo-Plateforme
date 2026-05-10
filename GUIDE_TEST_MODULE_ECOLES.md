# 🧪 Guide Complet de Test du Module Écoles

## 📋 Table des matières
1. [Installation des données de test](#installation)
2. [Guide par rôle](#guide-par-rôle)
3. [Scénarios complets](#scénarios)
4. [Endpoints à tester](#endpoints)
5. [Checklist de validation](#checklist)

---

## 🔧 Installation {#installation}

### Étape 1 : Lancer le seeder de test

```bash
# Option 1 : Seeder spécifique
php artisan db:seed --class=SchoolTestUsersSeeder

# Option 2 : Tout réinitialiser (données de dev)
php artisan migrate:fresh --seed
# Puis décommenter SchoolTestUsersSeeder dans DatabaseSeeder.php
```

### Étape 2 : Vérifier les données créées

```bash
# Vérifier les utilisateurs
php artisan tinker
>>> User::where('email', 'like', '%@school-test.local%')->pluck('email', 'name');
```

### Données disponibles

**École:** École Excellence Test
- **ID:** 1
- **Directeur:** admin@school-test.local
- **Classes:** CP1, CP2, CE1
- **Enseignants:** 2 permanents + 1 remplaçant
- **Élèves:** 4 élèves
- **Parents:** 2 parents

---

## 🎯 Guide par Rôle {#guide-par-rôle}

### 1️⃣ DIRECTEUR D'ÉCOLE (school_admin)

**Accès:** `admin@school-test.local` / `password`

#### Actions permetties ✅
- Voir l'école complètement
- Gérer les élèves (CRUD)
- Gérer les enseignants (CRUD)
- Gérer les classes (CRUD)
- Créer/modifier notes et présences
- Archiver/restaurer l'année scolaire
- Déléguer des permissions temporaires

#### Tests détaillés

**Test 1.1 : Accès à l'école**
```
GET /api/schools/1
- Status: 200 ✅
- Retourne les données complètes de l'école
```

**Test 1.2 : Voir tous les élèves**
```
GET /api/schools/1/students
- Status: 200 ✅
- Retourne: [student1, student2, student3, student4]
```

**Test 1.3 : Créer un nouvel élève**
```
POST /api/schools/1/students
Body: {
  "user_id": (new_user_id),
  "class_id": 1,
  "student_number": "STU005",
  "enrollment_date": "2026-05-08",
  "status": "active"
}
- Status: 201 ✅
- Élève créé avec succès
```

**Test 1.4 : Modifier un élève**
```
PUT /api/schools/1/students/1
Body: {
  "class_id": 2
}
- Status: 200 ✅
- Élève transféré de CP1 à CP2
```

**Test 1.5 : Supprimer un élève**
```
DELETE /api/schools/1/students/4
- Status: 200 ✅
```

**Test 1.6 : Créer une note**
```
POST /api/schools/1/grades
Body: {
  "student_id": 1,
  "class_id": 1,
  "subject": "Français",
  "term": "Trimestre 1",
  "score": 15.5,
  "max_score": 20
}
- Status: 201 ✅
- Note créée
```

**Test 1.7 : Voir les notes de la classe**
```
GET /api/schools/1/grades?class_id=1
- Status: 200 ✅
- Retourne toutes les notes de CP1
```

**Test 1.8 : Archiver l'année 2024**
```
POST /api/schools/1/school-years/2024/archive
- Status: 200 ✅
- Classes, élèves, enseignants archivés
- archived_at = 2026-05-08
```

**Test 1.9 : Voir les données archivées**
```
GET /api/schools/1/students?include_archived=false
- Status: 200 ✅
- Aucun étudiant (tous archivés)

GET /api/schools/1/students?include_archived=true
- Status: 200 ✅
- Les 4 étudiants visibles avec archived_at
```

**Test 1.10 : Restaurer l'année**
```
PUT /api/schools/1/school-years/2024/restore
- Status: 200 ✅
- archived_at = NULL, données restaurées
```

---

### 2️⃣ ENSEIGNANT (teacher)

**Accès:** `prof1@school-test.local` / `password`

#### Actions permetties ✅
- Voir sa classe (CP1 + CE1)
- Voir ses élèves
- Créer/modifier ses notes
- Créer/modifier présences
- Voir ses horaires

#### Restrictions ❌
- Pas accès à CP2 (classe de prof2)
- Pas accès aux élèves de prof2
- Pas suppression de notes
- Pas création d'autres enseignants

#### Tests détaillés

**Test 2.1 : Voir ses classes**
```
GET /api/teachers/1/classes
- Status: 200 ✅
- Retourne: [CP1 (ID:1), CE1 (ID:3)]
```

**Test 2.2 : Voir les élèves de sa classe**
```
GET /api/schools/1/students?class_id=1
- Status: 200 ✅
- Retourne: [student1, student2]
- Les élèves de CE1 visibles aussi (prof1 enseigne CE1)
```

**Test 2.3 : Créer une note DANS sa classe**
```
POST /api/schools/1/grades
Body: {
  "student_id": 1,
  "class_id": 1,
  "subject": "Français",
  "term": "Trimestre 1",
  "score": 14,
  "max_score": 20
}
- Status: 201 ✅
- Note créée
```

**Test 2.4 : Essayer créer une note HORS sa classe ❌**
```
POST /api/schools/1/grades
Body: {
  "student_id": 3,  // Student3 dans CP2
  "class_id": 2,    // CP2 (prof2)
  ...
}
- Status: 403 ❌
- Message: "Vous n'avez pas la permission..."
```

**Test 2.5 : Créer présence**
```
POST /api/schools/1/attendance
Body: {
  "student_id": 1,
  "class_id": 1,
  "date": "2026-05-08",
  "status": "present"
}
- Status: 201 ✅
```

**Test 2.6 : Essayer accéder à prof2 ❌**
```
GET /api/teachers/2
- Status: 403 ❌
- Pas d'accès aux données d'autres enseignants
```

**Test 2.7 : Essayer supprimer une note ❌**
```
DELETE /api/schools/1/grades/1
- Status: 403 ❌
- Seul le directeur peut supprimer
```

---

### 3️⃣ ÉLÈVE (student)

**Accès:** `student1@school-test.local` / `password`

#### Actions permetties ✅
- Voir ses propres notes
- Voir ses propres présences
- Voir ses informations de classe

#### Restrictions ❌
- Pas voir notes d'autres élèves
- Pas créer/modifier notes
- Pas créer/modifier présences
- Pas liste complète des élèves

#### Tests détaillés

**Test 3.1 : Voir ses propres notes**
```
GET /api/my-grades
- Status: 200 ✅
- Retourne seulement les notes de student1
```

**Test 3.2 : Essayer voir notes d'un autre ❌**
```
GET /api/schools/1/grades?student_id=2
- Status: 403 ❌
- Message: "Vous ne pouvez voir que vos propres notes"
```

**Test 3.3 : Voir ses présences**
```
GET /api/my-attendance
- Status: 200 ✅
- Retourne seulement ses présences
```

**Test 3.4 : Essayer créer une note ❌**
```
POST /api/schools/1/grades
- Status: 403 ❌
- Pas de permission grades.create
```

**Test 3.5 : Essayer voir liste des élèves ❌**
```
GET /api/schools/1/students
- Status: 403 ❌
- Pas d'accès à la liste
```

**Test 3.6 : Essayer voir détails d'un autre élève ❌**
```
GET /api/schools/1/students/2
- Status: 403 ❌
- Pas d'accès aux données d'autres
```

---

### 4️⃣ PARENT (parent)

**Accès:** `parent1@school-test.local` / `password`

#### Enfants ✅
- student1 (CP1)
- student4 (CE1)

#### Actions permittes ✅
- Voir notes de ses enfants
- Voir présences de ses enfants
- Voir messages/communications

#### Restrictions ❌
- Pas voir notes d'autres élèves
- Pas créer/modifier données
- Pas voir les données du parent2 ou ses enfants

#### Tests détaillés

**Test 4.1 : Voir ses enfants**
```
GET /api/my-children
- Status: 200 ✅
- Retourne: [student1, student4]
```

**Test 4.2 : Voir notes de son enfant 1**
```
GET /api/my-children/1/grades
ou
GET /api/schools/1/grades?student_id=1
- Status: 200 ✅
- Retourne les notes de student1
```

**Test 4.3 : Voir présences de son enfant 4**
```
GET /api/my-children/4/attendance
- Status: 200 ✅
- Retourne les présences de student4
```

**Test 4.4 : Essayer voir notes d'un enfant de parent2 ❌**
```
GET /api/schools/1/grades?student_id=2  // Enfant de parent2
- Status: 403 ❌
- Message: "Vous ne pouvez voir que les notes de vos enfants"
```

**Test 4.5 : Essayer accéder directement à student2 ❌**
```
GET /api/schools/1/students/2
- Status: 403 ❌
```

**Test 4.6 : Essayer créer une note ❌**
```
POST /api/schools/1/grades
- Status: 403 ❌
```

---

### 5️⃣ ASSISTANT (assistant)

**Accès:** `assistant@school-test.local` / `password`

#### Actions permittes ✅
- Voir les classes
- Voir les élèves
- Voir les enseignants
- Créer présences
- Voir les tâches administratives
- Créer tâches

#### Restrictions ❌
- Pas créer enseignants
- Pas modifier notes (view only)
- Pas accès archivage

#### Tests détaillés

**Test 5.1 : Voir les classes**
```
GET /api/schools/1/classes
- Status: 200 ✅
- Retourne: [CP1, CP2, CE1]
```

**Test 5.2 : Voir les élèves**
```
GET /api/schools/1/students
- Status: 200 ✅
- Retourne: [student1, student2, student3, student4]
```

**Test 5.3 : Créer une présence**
```
POST /api/schools/1/attendance
Body: {
  "student_id": 1,
  "class_id": 1,
  "date": "2026-05-08",
  "status": "present"
}
- Status: 201 ✅
```

**Test 5.4 : Essayer créer un enseignant ❌**
```
POST /api/schools/1/teachers
- Status: 403 ❌
- Pas de permission teachers.create
```

**Test 5.5 : Essayer voir notes (read-only) ✅**
```
GET /api/schools/1/grades?class_id=1
- Status: 200 ✅
- Peut voir mais pas modifier
```

**Test 5.6 : Essayer modifier une note ❌**
```
PUT /api/schools/1/grades/1
- Status: 403 ❌
```

---

### 6️⃣ STAFF (school_staff)

**Accès:** `staff@school-test.local` / `password`

#### Actions permittes ✅
- Même que Assistant + plus

#### Tests détaillés

**Test 6.1 : Créer un élève**
```
POST /api/schools/1/students
- Status: 201 ✅
```

**Test 6.2 : Modifier un élève**
```
PUT /api/schools/1/students/1
- Status: 200 ✅
```

---

### 7️⃣ REMPLAÇANT D'ENSEIGNANT (substitute_teacher)

**Accès:** `sub-prof@school-test.local` / `password`

#### Caractéristiques ⏰
- Permissions temporaires
- Peut créer notes + présences
- Limitation dans le temps (expiration_date)

#### Tests détaillés

**Test 7.1 : Créer une délégation temporaire**
```
POST /api/schools/1/permission-delegation
Body: {
  "delegated_to_user_id": (sub_prof_user_id),
  "permissions": ["grades.create", "attendance.create"],
  "expires_at": "2026-05-15",
  "scope": "class",
  "scope_id": 1
}
- Status: 201 ✅
- ID delegation créée: 123
```

**Test 7.2 : Avant l'expiration (2026-05-08)**
```
POST /api/schools/1/grades
Body: {
  "student_id": 1,
  "class_id": 1,
  "subject": "Français",
  "term": "Trimestre 1",
  "score": 12,
  "max_score": 20
}
- Status: 201 ✅
- Sub-prof peut créer
```

**Test 7.3 : Après l'expiration (simulation)**
```
# Modifier la date dans DB:
UPDATE permission_delegations SET expires_at = '2026-05-07' WHERE id = 123;

POST /api/schools/1/grades
- Status: 403 ❌
- Message: "Permission temporaire expirée"
```

**Test 7.4 : Révoquer la permission**
```
DELETE /api/permission-delegation/123
- Status: 200 ✅
- Permission révoquée immédiatement

POST /api/schools/1/grades
- Status: 403 ❌
- Sub-prof plus accès
```

---

### 8️⃣ SUPER ADMIN (super_admin)

**Accès:** `super@app.test` / `password`

#### ⚠️ Isolation stricte
- N'a **PAS** d'accès automatique aux écoles
- Doit avoir un rôle (school_admin) pour accéder

#### Tests détaillés

**Test 8.1 : Essayer accéder directement ❌**
```
GET /api/schools/1
- Status: 403 ❌
- Message: "Vous n'avez pas accès à cette école"
```

**Test 8.2 : S'assigner le rôle school_admin**
```
# Via admin panel ou:
php artisan tinker
>>> $user = User::find(super_admin_id);
>>> $user->assignRole('school_admin');
```

**Test 8.3 : Essayer accéder avec le rôle ✅**
```
GET /api/schools/1
- Status: 200 ✅
- Maintenant accès complet (comme directeur)
```

---

## 📊 Scénarios Complets {#scénarios}

### Scénario A : Saisie de notes complète

**Acteurs:** prof1, student1, parent1

**Étapes:**

1. Prof1 se connecte
   ```
   POST /login
   Email: prof1@school-test.local
   Password: password
   → Token reçu
   ```

2. Prof1 créé une note pour student1
   ```
   POST /api/schools/1/grades
   {
     "student_id": 1,
     "class_id": 1,
     "subject": "Français",
     "term": "Trimestre 1",
     "score": 16,
     "max_score": 20,
     "type": "exam",
     "comment": "Bon travail!"
   }
   → Note créée (ID: 1)
   ```

3. Student1 se connecte et voit sa note
   ```
   POST /login
   Email: student1@school-test.local
   Password: password
   
   GET /api/my-grades
   → Retourne: [{id:1, subject:'Français', score:16, ...}]
   ```

4. Parent1 se connecte et voit la note de son enfant
   ```
   POST /login
   Email: parent1@school-test.local
   Password: password
   
   GET /api/my-children/1/grades
   → Retourne: [{id:1, subject:'Français', score:16, ...}]
   ```

5. Directeur valide le bulletin
   ```
   POST /login
   Email: admin@school-test.local
   Password: password
   
   GET /api/schools/1/grades?class_id=1&term=Trimestre 1
   → Voit toutes les notes de CP1
   ```

---

### Scénario B : Restriction d'accès croisée

**Test:** Assurer que prof2 ne peut pas voir CP1

1. Prof2 se connecte
   ```
   POST /login
   Email: prof2@school-test.local
   ```

2. Essayer voir élèves de CP1
   ```
   GET /api/schools/1/grades?class_id=1
   → Status: 403 ❌
   → Message: "Vous ne pouvez voir que vos propres classes"
   ```

3. Essayer voir student1
   ```
   GET /api/schools/1/students/1
   → Status: 403 ❌
   ```

---

### Scénario C : Archivage fin d'année

**Acteurs:** admin (directeur)

**Before:**
```
GET /api/schools/1/students
→ Retourne: 4 étudiants
```

**Archivage:**
```
POST /api/schools/1/school-years/2024/archive
{
  "academic_year": "2024"
}
→ Status: 200 ✅
```

**After:**
```
GET /api/schools/1/students
→ Retourne: 0 étudiants (tous archivés)

GET /api/schools/1/students?include_archived=true
→ Retourne: 4 étudiants avec archived_at = "2026-05-08"
```

**Restauration:**
```
PUT /api/schools/1/school-years/2024/restore
→ Status: 200 ✅

GET /api/schools/1/students
→ Retourne: 4 étudiants (restaurés)
```

---

## 🔗 Endpoints à tester {#endpoints}

### Authentification
- [x] POST `/login` - Se connecter
- [x] POST `/logout` - Se déconnecter
- [x] GET `/api/me` - Profil utilisateur

### Écoles
- [x] GET `/api/schools/{school}` - Voir l'école
- [x] GET `/api/schools/{school}/classes` - Classes
- [x] GET `/api/schools/{school}/students` - Élèves
- [x] GET `/api/schools/{school}/teachers` - Enseignants

### Élèves
- [x] POST `/api/schools/{school}/students` - Créer
- [x] PUT `/api/schools/{school}/students/{id}` - Modifier
- [x] DELETE `/api/schools/{school}/students/{id}` - Supprimer
- [x] GET `/api/my-grades` - Mes notes
- [x] GET `/api/my-attendance` - Mes présences
- [x] GET `/api/my-children` - Mes enfants (parents)

### Notes
- [x] GET `/api/schools/{school}/grades` - Lister
- [x] POST `/api/schools/{school}/grades` - Créer
- [x] PUT `/api/schools/{school}/grades/{id}` - Modifier
- [x] DELETE `/api/schools/{school}/grades/{id}` - Supprimer

### Présences
- [x] GET `/api/schools/{school}/attendance` - Lister
- [x] POST `/api/schools/{school}/attendance` - Créer
- [x] PUT `/api/schools/{school}/attendance/{id}` - Modifier

### Années Scolaires
- [x] POST `/api/schools/{school}/school-years/{year}/archive` - Archiver
- [x] PUT `/api/schools/{school}/school-years/{year}/restore` - Restaurer

### Permissions Temporelles
- [x] POST `/api/permission-delegation` - Créer délégation
- [x] DELETE `/api/permission-delegation/{id}` - Révoquer

---

## ✅ Checklist de Validation {#checklist}

### Authentification et Autorisation
- [ ] Chaque rôle peut se connecter avec ses credentials
- [ ] Token JWT reçu et valide
- [ ] Tentative accès non-autorisée → 403
- [ ] Token expiré → 401

### Isolation des Données par École
- [ ] Directeur école A ne voit pas école B
- [ ] Élève ne voit ses données SEULEMENT
- [ ] Parent voit SEULEMENT ses enfants
- [ ] Pas accès cross-école

### Isolement Enseignants
- [ ] Prof1 voit CP1 + CE1 (ses classes)
- [ ] Prof1 ne voit pas CP2
- [ ] Prof2 ne voit pas CP1
- [ ] Prof1 ne voit pas notes de prof2

### Notes et Présences
- [ ] Enseignant peut créer notes/présences
- [ ] Directeur peut créer/modifier/supprimer
- [ ] Élève ne peut pas créer
- [ ] Parent peut voir uniquement ses enfants

### Permissions Temporaires
- [ ] Délégation temporaire fonctionne
- [ ] Expiration respectée
- [ ] Révocation immédiate effective

### Archivage
- [ ] Archivage masque les données (par défaut)
- [ ] include_archived=true affiche les archivés
- [ ] Restauration remet archived_at = NULL
- [ ] Les relations (grades) restent intactes

### Erreurs et Messages
- [ ] Messages d'erreur clairs en français
- [ ] Codes HTTP corrects (200, 201, 400, 403, 404, 422)
- [ ] Validation des données stricte
- [ ] Logs complets des opérations sensibles

---

## 🚀 Commandes Utiles

```bash
# Voir les rôles de prof1
php artisan tinker
>>> User::where('email', 'prof1@school-test.local')->first()->roles;

# Assigner un rôle
>>> $user = User::find(1);
>>> $user->assignRole('school_admin');

# Voir les permissions d'un utilisateur
>>> $user->getAllPermissions();

# Nettoyer et réinstaller
php artisan migrate:fresh --seed --class=SchoolTestUsersSeeder

# Lancer tests spécifiques
php artisan test --filter "GradeControllerTest"

# Déboguer une requête
>>> $user = User::where('email', 'prof1@school-test.local')->first();
>>> $user->can('grades.create');  // true ou false
```

---

## 📞 Support

Si un test échoue :

1. **Vérifier les logs**
   ```
   tail -f storage/logs/laravel.log
   ```

2. **Vérifier les permissions**
   ```
   php artisan tinker
   >>> User::find(user_id)->getAllPermissions();
   ```

3. **Réinstaller les données**
   ```
   php artisan migrate:fresh --seed
   ```

4. **Vérifier la route**
   ```
   php artisan route:list | grep grades
   ```

