# 📚 Structure Complète: Maternelle & Primaire

## 🎯 Vue d'ensemble de l'implémentation

Ce document décrit complètement la structure et les règles de deux sous-modules éducatifs:
- **Maternelle** (3 niveaux) - Femmes uniquement
- **Primaire** (6 niveaux) - Mixte

---

## 🔐 SÉCURITÉ & ISOLATION DES DONNÉES

### Principe Fondamental
**L'admin d'école NE PEUT VOIR QUE SON ÉCOLE**

#### Mécanismes d'Isolation:
1. **school_id** - Tous les enregistrements liés à une école sont filtrés par `school_id`
2. **director_id** - Vérification que l'utilisateur est directeur de l'école
3. **Validation API** - Chaque endpoint vérifie l'accès avant de retourner les données
4. **Middleware** - Routes protégées par vérification de propriété

#### Points de Contrôle:
```php
// Avant d'accéder aux données:
1. Authorization header valide (token)
2. User extrait du token
3. school_id de la requête = school_id de l'utilisateur OU
   utilisateur est director_id de l'école
4. Si fail → 403 Forbidden
```

#### Ce qui est visible pour un admin:
- ✅ Son école uniquement
- ✅ Ses classes, élèves, enseignants, assistants
- ✅ Son année scolaire actuelle
- ✅ Ses rapports et statistiques

#### Ce qui n'est PAS visible:
- ❌ Autres écoles (même avec URL directe)
- ❌ Données d'autres écoles via API
- ❌ Autres administrateurs
- ❌ Données archivées (sauf filtre spécifique)

---

## 🍼 MATERNELLE - Sous-module `mp`

### Configuration de Base
```
Code: mp (Maternelle & Primaire)
Label: Maternelle & Primaire
Niveaux: ['maternelle', 'primaire']
Sous-type: maternelle uniquement pour cette section
```

### 📊 Structure des Niveaux
**3 niveaux uniquement, immuables:**

| Niveau | Nom officiel | Age | Effectif | Durée |
|--------|--------------|-----|----------|-------|
| 1er | Petite section | 3 ans | 15-20 | Full year (annual) |
| 2e | Moyenne section | 4 ans | 15-20 | Full year (annual) |
| 3e | Grande section | 5 ans | 15-20 | Full year (annual) |

### 👩‍🏫 Règles pour les Enseignants

#### Genre (🚨 STRICTEMENT APPLIQUÉE)
```
Maternelle: FEMMES UNIQUEMENT (F)
- Tous les enseignants doivent avoir gender = 'F'
- Pas d'exceptions
- Filtre appliqué côté client et serveur
- Validation: TeacherRequest ligne 'gender' => 'F'
```

#### Structure de l'Enseignant
```
- role: 'teacher' (enseignant principal)
  ├─ Un seul par classe pour l'année
  ├─ Contract: annual (toute l'année)
  ├─ Gender: F obligatoire
  └─ Responsable des cours et évaluation

- role: 'assistant' (assistante)
  ├─ 1-2 assistantes par école
  ├─ Contract: annual
  ├─ Gender: F obligatoire
  └─ Couvre absences, aide pédagogique
```

#### Contrats (contract_type)
- `annual` - Contrat annuel (full year, sept à août)
- Pas de `semester` ou `temporary` pour Maternelle

### 👶 Classes Maternelle

#### Nommage
```
Format: "Classe {level}"
Exemples:
- Classe 1er
- Classe 2e
- Classe 3e

Pas de variantes (pas de A, B, C)
class_variant = null
```

#### Attributs
```php
$class = [
    'name' => 'Classe 1er',           // Nom complet
    'level' => '1er',                 // Niveau
    'class_variant' => null,          // Pas de variantes
    'education_submodule' => 'mp',    // Denormalisation
    'academic_year' => '2025-2026',   // Année scolaire
    'teacher_id' => 123,              // Enseignante (F)
    'max_students' => 18,             // Effectif max
    'room' => 'Salle 1er',            // Salle
    'status' => 'active',
    'archived_at' => null,
];
```

### 🔄 Rotation Annuelle - Maternelle

**Système de rotation des enseignantes:**
```
Année 2025-2026:
- Mme. A → Classe 1er
- Mme. B → Classe 2e
- Mme. C → Classe 3e

Fin d'année (Juin 2026):
Admin prévoit rotation 2026-2027:
- Mme. A → Classe 2e (avance)
- Mme. B → Classe 3e (avance)
- Mme. C → Classe 1er (cycle)

Avantages:
✓ Enseignante suit sa cohorte d'élèves
✓ Continuité pédagogique
✓ Développement professionnel
```

**Cas d'absence - Assistante:**
```
Si Mme. A absente:
1. Assistante alertée
2. Assistante couvre la Classe 1er
3. Enregistrement dans Attendance
4. Notification aux parents
5. À fin du jour: Mme. A reprend normal
```

### 📋 Capacités Admin Maternelle
```
✅ Ajouter enseignantes (F uniquement)
✅ Assigner enseignante à classe
✅ Voir classes, élèves, notes, présences
✅ Gérer assistantes
✅ Planifier rotations annuelles
✅ Consulter bulletins
✅ Générer rapports présences
✅ Archiver année scolaire

❌ Ajouter enseignants (M) - Impossible
❌ Voir autres écoles
❌ Changer de sous-module (Maternelle → Primaire)
❌ Créer nouvelles catégories de niveaux
```

---

## 📖 PRIMAIRE - Sous-module `mp`

### Configuration de Base
```
Code: mp (Maternelle & Primaire)
Label: Maternelle & Primaire
Niveaux: ['maternelle', 'primaire']
Sous-type: primaire uniquement pour cette section
```

### 📊 Structure des Niveaux
**6 niveaux, immuables:**

| Niveau | Nom officiel | Age | Effectif | Durée |
|--------|--------------|-----|----------|-------|
| 1er | Première année | 6 ans | 25-30 | Full year |
| 2e | Deuxième année | 7 ans | 25-30 | Full year |
| 3e | Troisième année | 8 ans | 25-30 | Full year |
| 4e | Quatrième année | 9 ans | 25-30 | Full year |
| 5e | Cinquième année | 10 ans | 25-30 | Full year |
| 6e | Sixième année | 11 ans | 25-30 | Full year |

### 👨‍🏫 Règles pour les Enseignants

#### Genre (MIXTE)
```
Primaire: HOMMES ET FEMMES (M ou F)
- Pas de restriction
- Tous les genres acceptés
- Pas de filtre spécial
- gender: 'M' | 'F'
```

#### Structure de l'Enseignant
```
- role: 'teacher' (enseignant principal)
  ├─ Un seul par classe pour l'année
  ├─ Contract: annual (toute l'année)
  ├─ Gender: M ou F
  └─ Responsable des cours et évaluation

- role: 'assistant' (assistant/e)
  ├─ 1-2 assistants par école
  ├─ Contract: annual
  ├─ Gender: M ou F
  └─ Couvre absences, aide pédagogique

- role: 'substitute' (remplaçant)
  ├─ Enseignant de secours
  ├─ Contract: temporary ou semester
  ├─ Gender: M ou F
  └─ Intervention sur courte durée
```

#### Contrats (contract_type)
- `annual` - Contrat annuel (full year, sept à août)
- `semester` - Demi-année scolaire
- `temporary` - Court terme (remplacement)

### 📚 Classes Primaire

#### Nommage avec Variantes
```
Format: "{level} {variant}"
Exemples:
- 1er A, 1er B, 1er C...
- 2e A, 2e B, 2e C...
- 3e A
- 4e A
- 5e A
- 6e A

1er et 2e peuvent avoir plusieurs variantes (A, B, C...)
3e à 6e typiquement une variante (A)
```

#### Attributs
```php
$class = [
    'name' => '1er A',                // Nom complet (full_name attribute)
    'level' => '1er',                 // Niveau
    'class_variant' => 'A',           // Variante
    'education_submodule' => 'mp',    // Denormalisation
    'academic_year' => '2025-2026',   // Année scolaire
    'teacher_id' => 456,              // Enseignant (M ou F)
    'max_students' => 28,             // Effectif max
    'room' => 'Salle 1er A',          // Salle
    'status' => 'active',
    'archived_at' => null,
];

// Attribut calculé:
$class->full_name  // "1er A"
```

### 🔄 Rotation Annuelle - Primaire

**Système de rotation des enseignants:**
```
Année 2025-2026:
- M. X → 1er A
- Mme. Y → 1er B
- M. Z → 2e A
...

Fin d'année (Juin 2026):
Admin prévoit rotation 2026-2027:
- M. X → 2e A (avance)
- Mme. Y → 2e B (avance - si créée)
- M. Z → 3e A (avance)
...

Avantages:
✓ Enseignant suit sa cohorte
✓ Continuité pédagogique
✓ Progression cohérente
```

**Cas d'absence - Assistant/Remplaçant:**
```
Si M. X absent:
1. Assistant alerte (si assigné)
2. Assistant couvre 1er A
3. OU remplaçant temporaire appelé
4. Enregistrement de substitution
5. Parents notifiés
6. À retour de M. X: normalisation
```

### 📋 Capacités Admin Primaire
```
✅ Ajouter enseignants (H et F)
✅ Assigner enseignants à classes
✅ Créer variantes de classe (A, B, C...)
✅ Voir classes, élèves, notes, présences
✅ Gérer assistants
✅ Gérer remplaçants temporaires
✅ Planifier rotations annuelles
✅ Consulter bulletins
✅ Générer rapports présences
✅ Archiver année scolaire
✅ Gérer emplois du temps multiples

❌ Voir autres écoles
❌ Changer de sous-module (Primaire → autre)
```

---

## 🔄 COMPARAISON MATERNELLE vs PRIMAIRE

| Aspect | Maternelle | Primaire |
|--------|-----------|----------|
| **Niveaux** | 3 (1er, 2e, 3e) | 6 (1er-6e) |
| **Genre Enseignants** | 🚫 Femmes uniquement (F) | ✅ Mixte (M/F) |
| **Variantes Classe** | Aucune (null) | A, B, C... |
| **Effectif** | 15-20 élèves | 25-30 élèves |
| **Contrats** | annual uniquement | annual, semester, temporary |
| **Rôles** | teacher, assistant | teacher, assistant, substitute |
| **Rotation** | Enseignante suit cohorte | Enseignant suit cohorte |
| **Absence Couverte** | Assistante | Assistant/Remplaçant |
| **Durée Année** | Sept-Août (annual) | Sept-Août (annual) |

---

## 🗄️ MODÈLE DE DONNÉES

### Table `schools`
```sql
- id (PK)
- name
- level_types (JSON: ['maternelle', 'primaire'] ou ['primaire'] etc)
- education_submodule (string: 'mp', 'ps', 'sh', 'full')
- director_id (FK: users)
- status (active/inactive)
- subscription_status
- license_plan_code (mp_yearly, etc)
- ... autres champs
```

### Table `school_classes`
```sql
- id (PK)
- school_id (FK: schools)
- name (ex: "1er A")
- level (ex: "1er")
- class_variant (ex: "A", null pour Maternelle)
- education_submodule (mp, ps, sh, full)
- academic_year (ex: "2025-2026")
- teacher_id (FK: users)
- max_students (int)
- room (string)
- status (active/inactive)
- archived_at (datetime, nullable)
```

### Table `teachers`
```sql
- id (PK)
- user_id (FK: users)
- school_id (FK: schools)
- employee_number (unique)
- gender (M/F, F obligatoire pour Maternelle)
- contract_type (annual, semester, temporary)
- role (teacher, assistant, substitute)
- qualification (string)
- experience_years (int)
- subjects (JSON array)
- status (active/inactive/on_leave)
- archived_at (datetime)
```

### Table `students`
```sql
- id (PK)
- school_id (FK: schools)
- class_id (FK: school_classes)
- name
- email
- student_number (unique per school)
- status (active/inactive)
- archived_at (datetime)
```

---

## 🛠️ MIGRATIONS APPLIQUÉES

**Migration: 2026_05_09_000001_add_school_module_fields.php**

Colonnes ajoutées:
- `schools.education_submodule` (string)
- `teachers.gender` (enum: M, F)
- `teachers.contract_type` (enum: annual, semester, temporary)
- `teachers.role` (enum: teacher, assistant, substitute)
- `school_classes.class_variant` (string nullable)
- `school_classes.education_submodule` (string nullable)
- `school_classes.archived_at` (datetime nullable)

---

## 🌱 SEED DATA (MaternellePrimaireSeeder)

Le seeder crée:

### Maternelle: École Maternelle Les Bambins
```
Directrice: Mme. Félicité Kilo (directrice.maternelle@lumo.app)
Email: directrice.maternelle@lumo.app
Enseignantes:
  - Mme. Antoinette Makoso (Classe 1er)
  - Mme. Grace Mvembi (Classe 2e)
  - Mme. Jeanne Kabila (Classe 3e)
  - Mme. Marie Kalombo (Assistante)
Classes: 3 (1er, 2e, 3e)
Élèves: 12 (4 par classe)
```

### Primaire: École Primaire Horizon Nouveau
```
Directeur: M. Jean-Pierre Bolamba (directeur.primaire@lumo.app)
Email: directeur.primaire@lumo.app
Enseignants:
  - M. François Ekunda (1er A)
  - Mme. Sophie Musangu (1er B)
  - M. André Nkombo (2e A)
  - Mme. Claire Sanda (2e B)
  - M. Paul Lamba (3e A)
  - M. Théo Mbuyi (4e A)
  - Mme. Ruth Kabwe (5e A)
  - M. David Kasanda (6e A)
  - Mme. Nicole Tsongo (Assistante)
  - M. Roger Muamba (Assistant)
Classes: 8 (1er A/B, 2e A/B, 3e-6e A)
Élèves: 48 (6 par classe)
```

---

## ✅ VALIDATION & TESTS

### À Tester pour Maternelle:

**Création d'Enseignant:**
```
✅ POST /api/schools/{id}/teachers
   ├─ gender='F' → Accepté
   ├─ gender='M' → 422 Error (Femmes uniquement)
   └─ gender=null → Utilisé 'F' par défaut
```

**Création de Classe:**
```
✅ POST /api/schools/{id}/classes
   ├─ level='1er' → Accepté
   ├─ level='2e' → Accepté
   ├─ level='3e' → Accepté
   ├─ level='4e' → 422 Error (Max 3 niveaux)
   └─ class_variant → Ignoré (toujours null)
```

### À Tester pour Primaire:

**Création d'Enseignant:**
```
✅ POST /api/schools/{id}/teachers
   ├─ gender='M' → Accepté
   ├─ gender='F' → Accepté
   └─ gender=null → Accepté (pas de default)
```

**Création de Classe:**
```
✅ POST /api/schools/{id}/classes
   ├─ level='1er', variant='A' → Accepté ("1er A")
   ├─ level='1er', variant='B' → Accepté ("1er B")
   ├─ level='3e', variant='C' → Accepté ("3e C")
   └─ level='7e' → 422 Error (Max 6 niveaux)
```

### Isolation Admin:

```
✅ Admin Maternelle
   ├─ GET /api/schools/1 → OK
   ├─ GET /api/schools/2 → 403 Forbidden
   └─ GET /api/schools/1/classes → OK (4 classes)

✅ Admin Primaire
   ├─ GET /api/schools/2 → OK
   ├─ GET /api/schools/1 → 403 Forbidden
   └─ GET /api/schools/2/classes → OK (8 classes)
```

---

## 📝 Cas d'Usage - Workflow Jour Typique

### Matin - Admin Maternelle
```
1. Se connecte: directrice.maternelle@lumo.app / password
2. Voit portail "Mon Établissement"
3. Dashboard affiche:
   - 3 classes (1er, 2e, 3e)
   - 3 enseignantes + 1 assistante
   - 12 élèves totals
4. Peut:
   - Voir présences par classe
   - Consulter notes
   - Éditer infos enseignantes
   - Créer rotations

❌ Ne peut pas:
   - Voir école Primaire
   - Ajouter enseignant (M)
   - Ajouter 4e niveau
```

### Jour - Admin Primaire
```
1. Se connecte: directeur.primaire@lumo.app / password
2. Voit portail "Mon Établissement"
3. Dashboard affiche:
   - 8 classes (1er A/B, 2e A/B, 3e-6e A)
   - 10 enseignants (H et F) + 2 assistants
   - 48 élèves totals
4. Peut:
   - Voir présences par classe
   - Consulter notes et bulletins
   - Éditer infos enseignants
   - Créer variantes de classe (1er C, etc)
   - Gérer remplaçants temporaires
   - Créer rotations

❌ Ne peut pas:
   - Voir école Maternelle
   - Voir autres écoles
   - Accès données archivées (sauf filtre)
```

---

## 🚀 Prochaines Étapes

Après l'implémentation complète de Maternelle et Primaire:

1. **Secondaire (4 niveaux)**: 1ère, 2e, 3e, 4e
2. **Humanités (2 niveaux)**: 5e, 6e
3. **Sous-modules composites**: ps, sh, full
4. **Système de rotation avancée**
5. **Remplacement et couvertures**
6. **Bulletins et évaluations**
7. **Intégration mobile app**

---

## 📞 Support & Validation

**Points de contact pour validation:**
- Migration: `2026_05_09_000001_add_school_module_fields.php`
- Models: `School`, `Teacher`, `SchoolClass`
- Requests: `TeacherRequest`, `SchoolClassRequest`
- Seeder: `MaternellePrimaireSeeder`
- Policies: `SchoolPolicy` (applique director_id check)

---

*Dernière mise à jour: 9 mai 2026*
