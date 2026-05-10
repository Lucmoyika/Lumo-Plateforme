# 🚀 DÉMARRAGE RAPIDE - Maternelle & Primaire

## 📝 Qu'est-ce qui a été implémenté?

### ✅ COMPLET - Prêt à utiliser

**2 Sous-modules éducatifs avec règles métier:**

#### 🍼 **MATERNELLE**
- 3 niveaux uniquement: 1er, 2e, 3e
- Enseignantes: FEMMES UNIQUEMENT (F)
- 1 enseignante par classe pour toute l'année
- Assistantes pour couverture en absence
- Pas de variantes de classe (juste "Classe 1er", "Classe 2e", etc.)
- Effectif: 15-20 élèves par classe

#### 📖 **PRIMAIRE**
- 6 niveaux: 1er, 2e, 3e, 4e, 5e, 6e
- Enseignants: HOMMES ET FEMMES (mixte)
- 1 enseignant par classe pour toute l'année
- Assistants et remplaçants disponibles
- Variantes de classe: "1er A", "1er B", "1er C", etc.
- Effectif: 25-30 élèves par classe

### 🔐 SÉCURITÉ GARANTIE

**L'admin ne voit QUE son école:**
```
Admin Maternelle
└─ Voit: École Maternelle uniquement
   ├─ Ses 3 classes (1er, 2e, 3e)
   ├─ Ses 4 enseignantes
   └─ Ses 12 élèves

Admin Primaire
└─ Voit: École Primaire uniquement
   ├─ Ses 8 classes (1er A/B, 2e A/B, 3e-6e A)
   ├─ Ses 10 enseignants
   └─ Ses 48 élèves
```

---

## 🎯 ÉTAPES POUR DÉMARRER

### 1️⃣ Exécuter la migration

Ajoute les nouvelles colonnes à la base de données:

```bash
php artisan migrate
```

**Colonnes ajoutées:**
- `teachers.gender` (M/F)
- `teachers.contract_type` (annual, semester, temporary)
- `teachers.role` (teacher, assistant, substitute)
- `school_classes.class_variant` (A, B, C...)
- `school_classes.education_submodule` (mp, ps, sh, full)

### 2️⃣ Charger les données de test

Crée 2 écoles complètes avec tous les utilisateurs:

```bash
php artisan db:seed --class=MaternellePrimaireSeeder
```

**Crée:**
- ✅ École Maternelle Les Bambins
- ✅ École Primaire Horizon Nouveau
- ✅ Tous les utilisateurs (directrices, enseignants, élèves)
- ✅ Toutes les classes
- ✅ Toutes les liaisons

### 3️⃣ Se connecter et tester

**Maternelle:**
```
Email: directrice.maternelle@lumo.app
Password: password
```

**Primaire:**
```
Email: directeur.primaire@lumo.app
Password: password
```

---

## 🧪 TESTER LES VALIDATIONS

### Test 1: Genre Maternelle (❌ Femmes uniquement)

**Essayer d'ajouter un enseignant homme:**
```bash
curl -X POST http://localhost:8000/api/schools/1/teachers \
  -H "Authorization: Bearer TOKEN_MATERNELLE" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 999,
    "gender": "M",
    "role": "teacher"
  }'
```

**Résultat attendu:**
```
❌ 422 Unprocessable Entity
{
  "message": "The given data was invalid.",
  "errors": {
    "gender": ["La Maternelle accepte uniquement les enseignantes (F)."]
  }
}
```

### Test 2: Niveau Maternelle (Max 3)

**Essayer de créer une classe 4e:**
```bash
curl -X POST http://localhost:8000/api/schools/1/classes \
  -H "Authorization: Bearer TOKEN_MATERNELLE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Classe 4e",
    "level": "4e",
    "academic_year": "2025-2026",
    "max_students": 20
  }'
```

**Résultat attendu:**
```
❌ 422 Unprocessable Entity
{
  "message": "The given data was invalid.",
  "errors": {
    "level": ["Le niveau '4e' n'est pas autorisé. Niveaux autorisés: 1er, 2e, 3e"]
  }
}
```

### Test 3: Primaire - Genre mixte (✅ Accepté)

**Ajouter enseignant homme au Primaire:**
```bash
curl -X POST http://localhost:8000/api/schools/2/teachers \
  -H "Authorization: Bearer TOKEN_PRIMAIRE" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 999,
    "gender": "M",
    "role": "teacher",
    "contract_type": "annual"
  }'
```

**Résultat attendu:**
```
✅ 201 Created
{
  "message": "Enseignant créé.",
  "data": {
    "id": 123,
    "user_id": 999,
    "gender": "M",
    "role": "teacher",
    ...
  }
}
```

### Test 4: Primaire - Variantes de classe (✅ Accepté)

**Créer classe avec variante:**
```bash
curl -X POST http://localhost:8000/api/schools/2/classes \
  -H "Authorization: Bearer TOKEN_PRIMAIRE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "1er C",
    "level": "1er",
    "class_variant": "C",
    "academic_year": "2025-2026",
    "max_students": 28
  }'
```

**Résultat attendu:**
```
✅ 201 Created
{
  "message": "Classe créée.",
  "data": {
    "id": 124,
    "name": "1er C",
    "level": "1er",
    "class_variant": "C",
    "full_name": "1er C",
    ...
  }
}
```

### Test 5: Isolation Données (❌ Admin ne voit que son école)

**Admin Maternelle essaie d'accéder école Primaire:**
```bash
curl -X GET http://localhost:8000/api/schools/2 \
  -H "Authorization: Bearer TOKEN_MATERNELLE"
```

**Résultat attendu:**
```
❌ 403 Forbidden
{
  "message": "Cette action n'est pas autorisée.",
  "error": "Accès refusé"
}
```

---

## 📂 FICHIERS CLÉS

### À connaître:

| Fichier | Rôle |
|---------|------|
| `STRUCTURE_MATERNELLE_PRIMAIRE.md` | 📚 Doc complète (350+ lignes) |
| `IMPLEMENTATION_MATERNELLE_PRIMAIRE_COMPLETE.md` | ✅ Résumé implémentation |
| `database/seeders/MaternellePrimaireSeeder.php` | 🌱 Données test |
| `database/migrations/2026_05_09_000001_add_school_module_fields.php` | 📊 Schéma BD |
| `app/Modules/Education/Ecoles/Models/*` | 🏗️ Modèles |
| `app/Modules/Education/Ecoles/Services/*` | ⚙️ Validations métier |

---

## ✨ FONCTIONNALITÉS BONUS

### Admin peut:
✅ Voir dashboard de son école (nb classes, enseignants, élèves)
✅ Ajouter enseignants (avec validation genre)
✅ Créer classes avec variantes
✅ Assigner enseignants à classes
✅ Gérer assistants/remplaçants
✅ Consulter listes classe/élèves
✅ Voir présences et notes (si modules intégrés)

### Admin CANNOT:
❌ Voir autres écoles
❌ Créer enseignant du mauvais genre (Maternelle)
❌ Créer classe de niveau inexistant
❌ Accéder données d'autres écoles via API
❌ Changer le sous-module (Maternelle → Primaire)

---

## 🐛 TROUBLESHOOTING

### "Migration stuck"
```bash
# Check:
php artisan migrate:status

# Rollback si needed:
php artisan migrate:rollback
```

### "Seeder not found"
```bash
# Vérifier le namespace:
php artisan db:seed --class=Database\\Seeders\\MaternellePrimaireSeeder
```

### "401 Unauthorized" sur API
```bash
# Vérifier token est valid:
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### "422 Validation Error" inattendu
```bash
# Check les messages d'erreur:
{
  "message": "The given data was invalid.",
  "errors": { ... }  // Lire ici les erreurs détaillées
}
```

---

## 📞 SUPPORT

**Questions sur Maternelle/Primaire?**

Voir: `STRUCTURE_MATERNELLE_PRIMAIRE.md` (sections 🍼 MATERNELLE et 📖 PRIMAIRE)

**Questions sur l'API?**

Voir: `app/Modules/Education/Ecoles/Controllers/TeacherController.php`
      `app/Modules/Education/Ecoles/Controllers/SchoolClassController.php`

**Questions sur les validations?**

Voir: `app/Modules/Education/Ecoles/Services/TeacherService.php`
      `app/Modules/Education/Ecoles/Services/SchoolClassService.php`

---

## 🎓 RÉSUMÉ STRUCTURE

### Maternelle vs Primaire

| Aspect | Maternelle | Primaire |
|--------|-----------|----------|
| **Niveaux** | 1er, 2e, 3e | 1er-6e |
| **Genre** | 🚫 F uniquement | ✅ M/F mixte |
| **Variantes** | Aucune | A, B, C... |
| **Effectif** | 15-20 | 25-30 |
| **Assistants** | Oui | Oui |
| **Contrats** | annual | annual, semester, temporary |

---

## ✅ CHECKLIST DÉMARRAGE

- [ ] Exécuter migration: `php artisan migrate`
- [ ] Charger seeder: `php artisan db:seed --class=MaternellePrimaireSeeder`
- [ ] Tester connexion Maternelle: directrice.maternelle@lumo.app
- [ ] Tester connexion Primaire: directeur.primaire@lumo.app
- [ ] Tester validation genre (Test 1)
- [ ] Tester validation niveau (Test 2)
- [ ] Tester isolation données (Test 5)
- [ ] Lire documentation complète: `STRUCTURE_MATERNELLE_PRIMAIRE.md`

---

**PRÊT À TESTER! 🚀**

*Toutes les validations, sécurités et données de test sont en place.*

---

*Dernière mise à jour: 9 mai 2026*
