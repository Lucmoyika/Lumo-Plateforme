# Module Education/Ecoles - Récapitulatif Complet

## 📋 Vue d'ensemble
Module complet de gestion des écoles avec support pour classes, élèves, enseignants, présences, notes et plannings.

---

## 🎮 Controllers (9)

| Controller | Fichier | Actions |
|-----------|---------|---------|
| **SchoolController** | Controllers/SchoolController.php | `index()` - Liste écoles<br>`show(id)` - Détail école |
| **StudentController** | Controllers/StudentController.php | `index()` - Liste élèves<br>`show(id)` - Détail élève |
| **TeacherController** | Controllers/TeacherController.php | `index()` - Liste enseignants<br>`show(id)` - Détail enseignant |
| **SchoolClassController** | Controllers/SchoolClassController.php | `index()` - Liste classes<br>`show(id)` - Détail classe |
| **AttendanceController** | Controllers/AttendanceController.php | `record()` - Enregistrer présences<br>`getByClass()` - Présences par classe |
| **GradeController** | Controllers/GradeController.php | `index()` - Liste notes<br>`getByStudent()` - Notes d'élève |
| **SchoolYearController** | Controllers/SchoolYearController.php | `index()` - Années scolaires<br>`archive()` - Archiver année |
| **SchoolTaskController** | Controllers/SchoolTaskController.php | `index/show/store/update` - Gestion tâches |
| **SchoolMemberPermissionController** | Controllers/SchoolMemberPermissionController.php | `index()` - Membres et permissions<br>`store()` - Déléguer permissions |

---

## 📊 Models (9) avec Relations

### 1. **School** (École)
```
Attributs clés: name, level_types (JSON), city, phone, email, logo, director_id, status
Relations:
  - director (BelongsTo → User)
  - classes (HasMany → SchoolClass)
  - teachers (HasMany → Teacher)
  - students (HasMany → Student)
  - tasks (HasMany → SchoolTask)
```

### 2. **Student** (Élève)
```
Attributs clés: user_id, school_id, class_id, student_number, enrollment_date, parent_id, status
Relations:
  - user (BelongsTo → User)
  - school (BelongsTo → School)
  - class_ (BelongsTo → SchoolClass)
  - parent (BelongsTo → User)
  - grades (HasMany → Grade)
  - attendances (HasMany → Attendance)
```

### 3. **Teacher** (Enseignant)
```
Attributs clés: user_id, school_id, employee_number, subjects (JSON), qualification, experience_years
Relations:
  - user (BelongsTo → User)
  - school (BelongsTo → School)
  - classes (HasMany → SchoolClass)
  - schedules (HasMany → Schedule)
```

### 4. **SchoolClass** (Classe)
```
Attributs clés: school_id, name, level, academic_year, teacher_id, max_students, room
Relations:
  - school (BelongsTo → School)
  - teacher (BelongsTo → User)
  - students (HasMany → Student)
  - schedules (HasMany → Schedule)
  - attendances (HasMany → Attendance)
  - grades (HasMany → Grade)
```

### 5. **Grade** (Note)
```
Attributs clés: student_id, subject, class_id, academic_year, term, score, max_score, grade_letter, exam_type
Relations:
  - student (BelongsTo → Student)
  - class_ (BelongsTo → SchoolClass)
  - teacher (BelongsTo → User)
Accesseur: percentage (calcule %)
```

### 6. **Attendance** (Présence)
```
Attributs clés: student_id, class_id, date, status (present/absent/late), notes, recorded_by
Relations:
  - student (BelongsTo → Student)
  - class_ (BelongsTo → SchoolClass)
  - recorder (BelongsTo → User)
```

### 7. **Schedule** (Emploi du temps)
```
Attributs clés: class_id, subject, teacher_id, day_of_week, start_time, end_time, room, color
Relations:
  - class_ (BelongsTo → SchoolClass)
  - teacher (BelongsTo → User)
```

### 8. **SchoolTask** (Tâche administrative)
```
Attributs clés: school_id, title, description, priority, status, due_date, assigned_to, created_by, completed_at
Relations:
  - school (BelongsTo → School)
  - assignee (BelongsTo → User)
  - creator (BelongsTo → User)
Status: todo, in_progress, done, blocked, cancelled
Priority: low, medium, high, urgent
```

### 9. **SchoolPermissionDelegation** (Délégation permissions)
```
Attributs clés: school_id, user_id, granted_by, role_name, permissions (JSON), starts_at, ends_at, revoked_at
Relations:
  - school (BelongsTo → School)
  - user (BelongsTo → User)
  - grantor (BelongsTo → User)
```

---

## 🔧 Services (7)

| Service | Méthodes principales |
|---------|---------------------|
| **SchoolService** | `list()`, `restore()`, `createWithDirector()` |
| **StudentService** | `listBySchool()`, `importFromCsv()`, `getGrades()`, `getBulletin()` |
| **TeacherService** | `listBySchool()`, `getSchedule()`, `getClasses()` |
| **SchoolClassService** | `getBySchool()` |
| **GradeService** | `getByStudent()`, `getByClass()`, `getBulletin()`, `getReport()`, `getStats()` |
| **AttendanceService** | `record()`, `getByClass()`, `getByStudent()`, `getReport()` |
| **SchoolTaskService** | `listBySchool()`, `getBySchool()` |

---

## 📦 Repositories (7)

| Repository | Spécialisation |
|------------|-----------------|
| **SchoolRepository** | Contrôle d'accès par rôle (admin vs director/teacher/student) |
| **StudentRepository** | Filtrage par école et année académique |
| **TeacherRepository** | Filtrage par école et relations classes |
| **SchoolClassRepository** | Filtrage par école avec relations enseignant |
| **GradeRepository** | Opérations upsert, filtrage par période |
| **AttendanceRepository** | Upsert présences, filtrage par plage dates |
| **SchoolTaskRepository** | Filtrage avancé (status, priority, assigned_to) |

---

## 📁 Structure Données - Tables Principales

### schools
- Propriétaires d'écoles avec directeur
- level_types: JSON (primaire, secondaire, humanites)
- Soft delete

### students
- Liaison user + école + classe
- Parent optionnel
- Archive support

### teachers
- Liaison user + école
- subjects: JSON array
- Archive support

### school_classes
- Par année académique
- Lié à un enseignant principal
- Archive support

### grades
- Scores par élève/matière/période
- exam_type (homework, quiz, midterm, final, project)
- academic_year + term pour regroupement

### attendances
- Date de présence avec statut
- Enregistré par qui

### schedules
- Jour/heure avec salle et couleur
- Pour affichage calendrier

### school_tasks
- Tâches assignées avec priorité
- Suivi complétion

### school_permission_delegations
- Permissions temporaires avec dates de validité
- Peut être révoqué

---

## 🔐 Contrôle d'Accès

- **super_admin/admin**: Accès complet
- **school_admin (director)**: Gestion de son école
- **teacher**: Accès à ses classes, notes, présences
- **student**: Accès à ses notes et présences
- **Permissions déléguées**: Granulaires par permission

---

## 🚀 Fonctionnalités Clés

✅ Gestion complète des écoles avec directeur  
✅ Classes par année académique avec archivage  
✅ Suivi élèves avec notes et présences  
✅ Emploi du temps calendrier  
✅ Tâches administratives avec priorités  
✅ Délégation permissions avec validité temporelle  
✅ Import CSV massif d'élèves  
✅ Rapports présence et bulletins  
✅ Statistiques académiques  
✅ Contrôle accès basé rôles  

---

## 📋 Fichiers de Validation (Requests)

- AttendanceRequest.php
- GradeRequest.php
- SchoolClassRequest.php
- SchoolRequest.php
- SchoolTaskRequest.php
- StudentRequest.php
- TeacherRequest.php

---

## 📄 JSON Complet

Un fichier `ECOLES_MODULE_STRUCTURE.json` a été généré avec tous les détails techniques complets incluant:
- Schémas de base de données avec colonnes
- Relations détaillées
- Signatures de méthodes
- Énumérations (statuts, priorités)
- Contrôle d'accès
- Factories et migrations
