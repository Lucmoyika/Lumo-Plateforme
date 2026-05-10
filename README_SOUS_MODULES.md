# 📚 Module Education - Sous-Modules Structure

## 🎯 Vue d'ensemble

Le module Education est maintenant organisé en 4 sous-modules indépendants correspondant aux niveaux scolaires:

| Sous-Module | Niveaux | Genres | Variantes | Contrats |
|---|---|---|---|---|
| **Maternelle** | 1er, 2e, 3e | F uniquement | Non | Annual uniquement |
| **Primaire** | 1er-6e | M + F | Oui (A-Z) | Annual, Semester, Temporary |
| **Secondaire** | 1ère, 2e, 3e, 4e | M + F | Oui (A-Z) | Annual, Semester, Temporary |
| **Humanités** | 5e, 6e | M + F | Oui (A-Z) | Annual, Semester, Temporary |

---

## 📂 Structure des répertoires

```
app/Modules/Education/
├── Ecoles/                          # Core models, services, repositories (SHARED)
│   ├── Models/
│   │   ├── School.php              # Enhanced with sub-module detection
│   │   ├── Teacher.php              # Enhanced with gender, contract_type, role
│   │   ├── SchoolClass.php          # Enhanced with class_variant support
│   │   ├── Student.php
│   │   └── ...
│   ├── Services/
│   │   ├── SchoolService.php
│   │   ├── TeacherService.php       # Validation service
│   │   ├── SchoolClassService.php   # Validation service
│   │   └── ...
│   ├── Repositories/
│   │   ├── TeacherRepository.php    # Filtering capabilities
│   │   ├── SchoolClassRepository.php # Filtering capabilities
│   │   └── ...
│   ├── Requests/
│   ├── Controllers/
│   └── Routes/
│
├── Maternelle/                      # Sub-module: Maternelle (F-only, 3 levels)
│   ├── Models/
│   │   ├── MaternelleClass.php      # Specialized for levels [1er, 2e, 3e]
│   │   └── MaternelleTeacher.php    # Specialized for gender=F
│   ├── Services/
│   │   ├── MaternelleClassService.php # Enforces 3 levels
│   │   └── MaternelleTeacherService.php # Enforces F-only + annual contract
│   ├── Repositories/
│   │   ├── MaternelleClassRepository.php
│   │   └── MaternelleTeacherRepository.php
│   ├── Requests/
│   │   ├── MaternelleClassRequest.php # Validates level in [1er, 2e, 3e]
│   │   └── MaternelleTeacherRequest.php # Validates gender=F, contract_type=annual
│   ├── Controllers/
│   │   ├── MaternelleClassController.php
│   │   └── MaternelleTeacherController.php
│   └── Routes/
│       └── api.php # POST /api/maternelle/schools/{school}/classes, etc.
│
├── Primaire/                        # Sub-module: Primaire (M+F, 6 levels, variants)
│   ├── Models/
│   │   ├── PrimaireClass.php        # Specialized with class_variant support
│   │   └── PrimaireTeacher.php      # Mixed gender
│   ├── Services/
│   │   ├── PrimaireClassService.php # Enforces 6 levels + variants
│   │   └── PrimaireTeacherService.php
│   ├── Repositories/
│   │   ├── PrimaireClassRepository.php
│   │   └── PrimaireTeacherRepository.php
│   ├── Requests/
│   │   ├── PrimaireClassRequest.php # Validates level in [1er-6e], variant regex
│   │   └── PrimaireTeacherRequest.php # Mixed gender
│   ├── Controllers/
│   │   ├── PrimaireClassController.php
│   │   └── PrimaireTeacherController.php
│   └── Routes/
│       └── api.php # POST /api/primaire/schools/{school}/classes, etc.
│
├── Secondaire/                      # Sub-module: Secondaire (M+F, 4 levels)
│   ├── Models/
│   │   ├── SecondaireClass.php
│   │   └── SecondaireTeacher.php
│   ├── Services/
│   │   ├── SecondaireClassService.php # Enforces 4 levels
│   │   └── SecondaireTeacherService.php
│   ├── Repositories/
│   │   ├── SecondaireClassRepository.php
│   │   └── SecondaireTeacherRepository.php
│   ├── Requests/
│   │   ├── SecondaireClassRequest.php # Validates level in [1ère, 2e, 3e, 4e]
│   │   └── SecondaireTeacherRequest.php
│   ├── Controllers/
│   │   ├── SecondaireClassController.php
│   │   └── SecondaireTeacherController.php
│   └── Routes/
│       └── api.php # POST /api/secondaire/schools/{school}/classes, etc.
│
└── Humanites/                       # Sub-module: Humanités (M+F, 2 levels)
    ├── Models/
    │   ├── HumanitesClass.php
    │   └── HumanitesTeacher.php
    ├── Services/
    │   ├── HumanitesClassService.php # Enforces 2 levels
    │   └── HumanitesTeacherService.php
    ├── Repositories/
    │   ├── HumanitesClassRepository.php
    │   └── HumanitesTeacherRepository.php
    ├── Requests/
    │   ├── HumanitesClassRequest.php # Validates level in [5e, 6e]
    │   └── HumanitesTeacherRequest.php
    ├── Controllers/
    │   ├── HumanitesClassController.php
    │   └── HumanitesTeacherController.php
    └── Routes/
        └── api.php # POST /api/humanites/schools/{school}/classes, etc.
```

---

## 🔌 Endpoints API

### Maternelle
```
GET    /api/maternelle/schools/{school}/classes      # Liste classes
POST   /api/maternelle/schools/{school}/classes      # Créer classe
DELETE /api/maternelle/schools/{school}/classes/{id} # Archiver classe

GET    /api/maternelle/schools/{school}/teachers     # Liste enseignantes (F)
POST   /api/maternelle/schools/{school}/teachers     # Créer enseignante (F)
DELETE /api/maternelle/schools/{school}/teachers/{id} # Archiver enseignante
```

### Primaire
```
GET    /api/primaire/schools/{school}/classes      # Liste classes (1er-6e, variants A-Z)
POST   /api/primaire/schools/{school}/classes      # Créer classe
DELETE /api/primaire/schools/{school}/classes/{id} # Archiver classe

GET    /api/primaire/schools/{school}/teachers     # Liste enseignants (M+F)
POST   /api/primaire/schools/{school}/teachers     # Créer enseignant
DELETE /api/primaire/schools/{school}/teachers/{id} # Archiver enseignant
```

### Secondaire
```
GET    /api/secondaire/schools/{school}/classes    # Liste classes (1ère-4e)
POST   /api/secondaire/schools/{school}/classes    # Créer classe
DELETE /api/secondaire/schools/{school}/classes/{id}

GET    /api/secondaire/schools/{school}/teachers   # Liste enseignants (M+F)
POST   /api/secondaire/schools/{school}/teachers   # Créer enseignant
```

### Humanités
```
GET    /api/humanites/schools/{school}/classes    # Liste classes (5e-6e)
POST   /api/humanites/schools/{school}/classes    # Créer classe
DELETE /api/humanites/schools/{school}/classes/{id}

GET    /api/humanites/schools/{school}/teachers   # Liste enseignants (M+F)
POST   /api/humanites/schools/{school}/teachers   # Créer enseignant
```

---

## ✅ Validation d'entrée (Request Level)

### Maternelle
```php
// MaternelleClassRequest.php
'level' => ['required', 'string', 'in:1er,2e,3e']  // UNIQUEMENT 3 niveaux

// MaternelleTeacherRequest.php
'gender' => ['nullable', 'in:F']                    // F UNIQUEMENT
'contract_type' => ['nullable', 'in:annual']        // Annual UNIQUEMENT
'role' => ['nullable', 'in:teacher,assistant']      // Pas de substitute
```

### Primaire
```php
// PrimaireClassRequest.php
'level' => ['required', 'string', 'in:1er,2e,3e,4e,5e,6e']
'class_variant' => ['nullable', 'string', 'regex:/^[A-Z]$/']  // A, B, C, etc.

// PrimaireTeacherRequest.php
'gender' => ['nullable', 'in:M,F']                  // Mixte
'contract_type' => ['nullable', 'in:annual,semester,temporary']
'role' => ['nullable', 'in:teacher,assistant,substitute']
```

### Secondaire
```php
// SecondaireClassRequest.php
'level' => ['required', 'string', 'in:1ère,2e,3e,4e']
'class_variant' => ['nullable', 'string', 'regex:/^[A-Z]$/']
```

### Humanités
```php
// HumanitesClassRequest.php
'level' => ['required', 'string', 'in:5e,6e']
```

---

## 💡 Logique métier (Service Layer)

Chaque sous-module implémente ses propres Services qui valident les règles métier:

### MaternelleClassService
```php
validateMaternelleLevel($data)  // Vérifie level ∈ [1er, 2e, 3e]
                                 // Lance ValidationException sinon
```

### MaternelleTeacherService
```php
create($data)  // Force gender='F', contract_type='annual'
               // Lance ValidationException si gender !== 'F'
```

### PrimaireClassService
```php
validatePrimaireLevel($data)  // Vérifie level ∈ [1er-6e]
                               // Supporte class_variant A-Z
```

---

## 🔒 Sécurité - Isolation école

**CRITICAL**: Admin école NE peut voir que SA propre école

Implémentation:
- Chaque endpoint utilise `school_id` en route parameter
- Controllers vérifient school_id match avant CRUD
- Middleware `token.school_scope` appliqué si présent
- Example:
  ```php
  GET /api/maternelle/schools/5/classes  # Admin école 5 ✅
  GET /api/maternelle/schools/3/classes  # Admin école 5 ❌ 403 Forbidden
  ```

---

## 🔄 Migration DB

Migration: `database/migrations/2026_05_09_000001_add_school_module_fields.php`

Colonnes ajoutées:
- `schools.education_submodule` (mp, ps, sh, full)
- `teachers.gender` (M, F)
- `teachers.contract_type` (annual, semester, temporary)
- `teachers.role` (teacher, assistant, substitute)
- `school_classes.class_variant` (A-Z, nullable)
- `school_classes.education_submodule` (denormalization)
- `school_classes.archived_at` (soft delete)

---

## 📝 Seed data

Seeder: `database/seeders/MaternellePrimaireSeeder.php`

Crée 2 écoles de test:
1. **École Maternelle Les Bambins** (directrice.maternelle@lumo.app)
   - 3 enseignantes (F, annual, teacher role)
   - 1 assistante (F, annual, assistant role)
   - 3 classes (1er, 2e, 3e)
   - 12 étudiantes

2. **École Primaire Horizon Nouveau** (directeur.primaire@lumo.app)
   - 8 enseignants (M+F mix)
   - 2 assistants
   - 8 classes (1er A/B, 2e A/B, 3e-6e A) avec variants
   - 48 étudiants

Exécution:
```bash
php artisan db:seed --class=MaternellePrimaireSeeder
```

---

## 📋 Pattern utilisé

Chaque sous-module suit le même pattern architectural:

```
Request → Middleware → Controller → Service → Repository → Model → DB
  ↓                                               ↓
[Validation]                                 [Soft Delete]
[Authorization]                              [Scopes]
```

### Exemple: Créer une classe Maternelle
```php
// 1. Request validation (api/primaire/schools/1/classes)
POST /api/maternelle/schools/1/classes
{
  "name": "Classe 1er A",
  "level": "1er",           // ✅ Must be in [1er, 2e, 3e]
  "academic_year": "2024-2025"
}

// 2. PrimaireClassRequest validates
'level' => ['required', 'in:1er,2e,3e']

// 3. MaternelleClassController stores
$this->classService->create($data)

// 4. MaternelleClassService validates business logic
validateMaternelleLevel($data)  // Re-check level constraint

// 5. MaternelleClassRepository creates
$this->model->create($data)

// 6. MaternelleClass model (Eloquent)
Model binds to school_classes table
Scopes: active(), byLevel(), etc.

// 7. Response
{
  "success": true,
  "data": { "id": 1, "name": "Classe 1er A", "level": "1er", ... }
}
```

---

## 🧪 Testing Checklist

- [ ] Maternelle: Can only add female (F) teachers
- [ ] Maternelle: Can only add 3 levels (1er, 2e, 3e)
- [ ] Maternelle: Cannot add substitutes
- [ ] Primaire: Can add mixed gender teachers (M, F)
- [ ] Primaire: Can add 6 levels (1er-6e)
- [ ] Primaire: Can add class variants (A, B, C, etc.)
- [ ] Primaire: Can add substitutes
- [ ] Secondaire: 4 levels (1ère, 2e, 3e, 4e)
- [ ] Humanités: 2 levels (5e, 6e)
- [ ] Admin isolation: Cannot access other school's data

---

## 📦 Dependencies

- Laravel 11
- Eloquent ORM
- Sanctum (API authentication)
- Query Builder
- Form Requests
- Service/Repository pattern

---

## 🚀 Next Steps

1. **Test API endpoints** with Postman/Insomnia
2. **Add Grade management** for each sub-module
3. **Add Attendance tracking** for each sub-module
4. **Frontend UI** adapting dropdowns based on sub-module
5. **PDF export** (bulletins, reports per sub-module)
