# 🚀 Commandes Rapides pour Tester le Module Écoles

## Installation Rapide

```bash
# 1. Installer les données de test
php artisan db:seed --class=SchoolTestUsersSeeder

# 2. Vérifier l'installation
php artisan tinker
>>> User::where('email', 'like', '%@school-test.local%')->count()  // Devrait retourner 9
```

## Utilisateurs de Test (password: "password" pour tous)

```
DIRECTEUR
  📧 admin@school-test.local
  
STAFF
  📧 staff@school-test.local
  📧 assistant@school-test.local
  
ENSEIGNANTS
  📧 prof1@school-test.local (CP1 + CE1)
  📧 prof2@school-test.local (CP2)
  📧 sub-prof@school-test.local (remplaçant)
  
PARENTS
  📧 parent1@school-test.local (enfants: student1, student4)
  📧 parent2@school-test.local (enfants: student2, student3)
  
ÉLÈVES
  📧 student1@school-test.local (CP1)
  📧 student2@school-test.local (CP1)
  📧 student3@school-test.local (CP2)
  📧 student4@school-test.local (CE1)
```

## Tester dans Postman/Insomnia

### 1️⃣ Se connecter comme Directeur

```
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "email": "admin@school-test.local",
  "password": "password"
}

✅ Réponse: {token: "xxx..."}
```

### 2️⃣ Créer une note (avec le token du directeur)

```
POST http://localhost:8000/api/schools/1/grades
Authorization: Bearer TOKEN_ICI
Content-Type: application/json

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

✅ Status: 201
✅ Réponse: {id: 1, subject: "Français", score: 16, ...}
```

### 3️⃣ Se déconnecter et connecter comme ÉLÈVE

```
POST http://localhost:8000/api/login
{
  "email": "student1@school-test.local",
  "password": "password"
}

✅ Token reçu
```

### 4️⃣ Voir ses propres notes (comme élève)

```
GET http://localhost:8000/api/my-grades
Authorization: Bearer TOKEN_ELEVE

✅ Status: 200
✅ Retourne: [
  {id: 1, subject: "Français", score: 16, student_id: 1, ...}
]
```

### 5️⃣ Essayer voir les notes d'un autre élève ❌

```
GET http://localhost:8000/api/schools/1/grades?student_id=2
Authorization: Bearer TOKEN_ELEVE

❌ Status: 403
❌ Message: "Vous ne pouvez voir que vos propres notes"
```

### 6️⃣ Se connecter comme PARENT

```
POST http://localhost:8000/api/login
{
  "email": "parent1@school-test.local",
  "password": "password"
}
```

### 7️⃣ Voir les enfants et leurs notes

```
GET http://localhost:8000/api/my-children
Authorization: Bearer TOKEN_PARENT

✅ Retourne: [
  {id: 1, name: "Kaïs Yao", ...},
  {id: 4, name: "Fatou Diallo", ...}
]

GET http://localhost:8000/api/my-children/1/grades
Authorization: Bearer TOKEN_PARENT

✅ Retourne les notes de student1
```

### 8️⃣ Se connecter comme ENSEIGNANT

```
POST http://localhost:8000/api/login
{
  "email": "prof1@school-test.local",
  "password": "password"
}
```

### 9️⃣ Voir ses classes

```
GET http://localhost:8000/api/teachers/1/classes
Authorization: Bearer TOKEN_PROF

✅ Status: 200
✅ Retourne: [
  {id: 1, name: "CP1", ...},
  {id: 3, name: "CE1", ...}
]
```

### 🔟 Créer une note pour sa classe

```
POST http://localhost:8000/api/schools/1/grades
Authorization: Bearer TOKEN_PROF
Content-Type: application/json

{
  "student_id": 1,  // Student de CP1
  "class_id": 1,    // Sa classe
  "subject": "Maths",
  "term": "Trimestre 1",
  "score": 13,
  "max_score": 20
}

✅ Status: 201 - Créée
```

### 1️⃣1️⃣ Essayer créer une note HORS sa classe ❌

```
POST http://localhost:8000/api/schools/1/grades
Authorization: Bearer TOKEN_PROF
Content-Type: application/json

{
  "student_id": 3,  // Student de CP2 (prof2)
  "class_id": 2,    // CP2 (pas sa classe)
  ...
}

❌ Status: 403
❌ Vous n'avez pas la permission...
```

---

## Tests de Sécurité Critiques

### Test 1: Isolation par école

```bash
# Directeur école 1 ne peut PAS voir école 2
GET /api/schools/2
Authorization: Bearer TOKEN_DIRECTEUR_ECOLE_1

❌ Status: 403 (si l'isolation est implémentée)
```

### Test 2: Élève ne peut voir que ses données

```bash
# Student1 essaye voir notes de student2
GET /api/schools/1/grades?student_id=2
Authorization: Bearer TOKEN_STUDENT1

❌ Status: 403
```

### Test 3: Parent ne peut voir que ses enfants

```bash
# Parent1 essaye voir enfants de parent2
GET /api/my-children/3  # Enfant de parent2
Authorization: Bearer TOKEN_PARENT1

❌ Status: 403
```

### Test 4: Super admin n'a pas accès automatique

```bash
# Super admin connecté essaye accéder l'école
POST /api/login
{
  "email": "super@app.test",
  "password": "password"
}

GET /api/schools/1
Authorization: Bearer TOKEN_SUPER

❌ Status: 403
✅ "Vous n'avez pas accès à cette école"
```

---

## Déboggage

### Voir les logs en temps réel

```bash
tail -f storage/logs/laravel.log
```

### Vérifier les rôles d'un utilisateur

```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->roles->pluck('name');
>>> $user->permissions->pluck('name');
```

### Assigner un rôle manuellement

```bash
php artisan tinker
>>> $user = User::where('email', 'admin@school-test.local')->first();
>>> $user->assignRole('school_admin');
```

### Réinitialiser complètement

```bash
# Option 1 : Reset + seed
php artisan migrate:fresh --seed --class=SchoolTestUsersSeeder

# Option 2 : Juste re-seed
php artisan db:seed --class=SchoolTestUsersSeeder
```

---

## Vérification Rapide

```bash
# Vérifier que tout est installé
php artisan tinker

# Nombre d'utilisateurs test
>>> User::where('email', 'like', '%@school-test.local%')->count()
# Devrait retourner: 9

# École créée
>>> School::where('name', 'École Excellence Test')->first()
# Devrait retourner: School object

# Classes créées
>>> SchoolClass::where('school_id', 1)->count()
# Devrait retourner: 3

# Rôles définis
>>> Role::count()
# Devrait retourner: 10+ (tous les rôles du système)

# Permissions définies
>>> Permission::count()
# Devrait retourner: 60+
```

---

## Résumé des Étapes de Test Rapide

```
1. php artisan db:seed --class=SchoolTestUsersSeeder
2. Ouvrir Postman/Insomnia
3. POST /api/login avec admin@school-test.local
4. Copier le token reçu
5. GET /api/schools/1 (directeur voit son école)
6. POST /api/schools/1/grades (créer une note)
7. Se déconnecter, connecter comme student1@school-test.local
8. GET /api/my-grades (élève voit sa note)
9. GET /api/schools/1/grades?student_id=2 (essayer voir autre → 403)
10. ✅ Module écoles fonctionne!
```

---

## Document de Référence

Pour plus de détails sur les cas de test, consulter:
- 📖 [GUIDE_TEST_MODULE_ECOLES.md](GUIDE_TEST_MODULE_ECOLES.md) - Guide complet
- 📋 [PLAN_FINITION_MODULE_ECOLES.md](PLAN_FINITION_MODULE_ECOLES.md) - Plan détaillé
- 📊 [RESUME_MODULE_ECOLES.md](RESUME_MODULE_ECOLES.md) - État d'avancement

