# 🛡️ CAPACITÉS & RESTRICTIONS DE L'ADMIN D'ÉCOLE

**Question initiale:** "Dis-moi ce que l'admin de l'école peut faire et ce qu'il ne peut pas, et surtout n'oublie pas qu'il ne peut pas voir d'autres écoles!"

---

## ✅ CE QUE L'ADMIN PEUT FAIRE

### 📚 GESTION DES CLASSES

L'admin peut:
- ✅ **Créer des classes** selon le type de son école
  - Maternelle: 3 classes max (1er, 2e, 3e)
  - Primaire: 6 classes par niveau (1er-6e) avec variantes A, B, C...
- ✅ **Modifier les infos de classe** (nom, salle, effectif max, etc.)
- ✅ **Assigner un enseignant** à chaque classe
- ✅ **Archiver les classes** à fin d'année scolaire
- ✅ **Restaurer des classes** archivées si besoin

### 👨‍🏫 GESTION DES ENSEIGNANTS

L'admin peut:
- ✅ **Ajouter des enseignants** (avec validation de genre)
  - Maternelle: FEMMES UNIQUEMENT ✋ Pas de hommes autorisés
  - Primaire: Hommes ET femmes (mixte) ✋ Pas de restriction
- ✅ **Modifier les infos enseignant** (qualification, expérience, etc.)
- ✅ **Assigner à des classes**
- ✅ **Gérer les rôles** (enseignant principal, assistant, remplaçant)
- ✅ **Gérer les contrats** (annuel, semestre, temporaire)
- ✅ **Voir les absences** enregistrées
- ✅ **Archiver/Désarchiver** les enseignants

### 👶 GESTION DES ÉLÈVES

L'admin peut:
- ✅ **Ajouter des élèves** aux classes
- ✅ **Voir la liste complète** des élèves (par classe ou école)
- ✅ **Modifier les infos élève** (nom, contact, etc.)
- ✅ **Consulter les présences** des élèves
- ✅ **Consulter les notes/bulletins** des élèves
- ✅ **Archiver les élèves** à fin d'année scolaire

### 📊 ASSISTANTS & REMPLAÇANTS

L'admin peut:
- ✅ **Ajouter des assistants/aides** (couverture en absence enseignant)
- ✅ **Ajouter des remplaçants** temporaires
- ✅ **Voir qui couvre** en cas d'absence
- ✅ **Gérer les emplois du temps** des assistants

### 📈 RAPPORTS & STATISTIQUES

L'admin peut:
- ✅ **Voir le dashboard** (nb classes, enseignants, élèves)
- ✅ **Générer des rapports** sur:
  - Présences par classe
  - Notes/bulletins
  - Performances élèves
  - Statistiques enseignants
- ✅ **Exporter les données** (listes classes, élèves, etc.)

### 🔄 ROTATION ANNUELLE

L'admin peut:
- ✅ **Planifier les rotations** des enseignants pour l'année suivante
- ✅ **Assigner des enseignants** à de nouvelles classes
- ✅ **Gérer les progressions** (ex: 1er → 2e)

### ⚙️ CONFIGURATION

L'admin peut:
- ✅ **Modifier infos école** (nom, adresse, email, téléphone)
- ✅ **Gérer les années scolaires** (2025-2026, 2026-2027, etc.)
- ✅ **Archiver une année scolaire** complète
- ✅ **Voir les logs** d'activités

---

## ❌ CE QUE L'ADMIN NE PEUT PAS FAIRE

### 🚫 VOIR D'AUTRES ÉCOLES

```
❌ L'admin d'une école NE PEUT JAMAIS:
   └─ Voir les données d'une autre école
   └─ Accéder à la liste complète des écoles
   └─ Modifier une autre école
   └─ Voir les enseignants d'une autre école
   └─ Voir les élèves d'une autre école
```

**Exemple:**
- Admin Maternelle avec école ID=1
  - ✅ Peut voir: École 1 (sa Maternelle)
  - ❌ NE PEUT PAS voir: École 2 (Primaire d'un collègue)
  - Même en tapant l'URL directe → 403 Forbidden

### 🚫 CHANGER LE SOUS-MODULE

```
❌ L'admin NE PEUT PAS:
   └─ Convertir Maternelle → Primaire
   └─ Convertir Primaire → Secondaire
   └─ Changer les niveaux de l'école
```

**Pourquoi:** C'est une décision stratégique = Super-Admin uniquement

### 🚫 GENRE ENSEIGNANT - MATERNELLE

```
❌ Pour Maternelle seulement:
   └─ NE PEUT PAS ajouter enseignant homme (M)
   └─ NE PEUT PAS changer F → M
   └─ Tous les enseignants DOIVENT être femmes (F)
```

**Validation serveur:**
```
POST /api/schools/1/teachers (Maternelle)
{
  "gender": "M"
}

RÉPONSE: ❌ 422 Error
"La Maternelle accepte uniquement les enseignantes (F)."
```

### 🚫 NIVEAUX DE CLASSE

```
❌ Maternelle: NE PEUT PAS créer
   ├─ Classe 4e ❌
   ├─ Classe 5e ❌
   ├─ Classe 6e ❌
   └─ Seulement: 1er, 2e, 3e ✅

❌ Primaire: NE PEUT PAS créer
   ├─ Classe 7e ❌
   ├─ Classe 1ère ❌
   └─ Seulement: 1er-6e ✅
```

### 🚫 VARIANTES DE CLASSE

```
❌ Maternelle: NE PEUT PAS faire
   ├─ Classe 1er A ❌
   ├─ Classe 1er B ❌
   └─ Seulement: Classe 1er (pas de variantes) ✅

✅ Primaire: PEUT faire
   ├─ 1er A, 1er B, 1er C ✅
   ├─ 2e A, 2e B, 2e C ✅
   └─ 3e A, 4e A, 5e A, 6e A ✅
```

### 🚫 UTILISATEURS ET PERMISSIONS

```
❌ L'admin NE PEUT PAS:
   └─ Créer de nouveaux utilisateurs
   └─ Changer les rôles/permissions d'autres utilisateurs
   └─ Accéder aux paramètres système
   └─ Voir les autres administrateurs d'écoles
   └─ Supprimer son compte ou un utilisateur
```

### 🚫 DONNÉES SENSIBLES

```
❌ L'admin NE PEUT PAS:
   └─ Voir les salaires/contrats d'autres écoles
   └─ Accéder aux données financières d'autres écoles
   └─ Voir les licenses/abonnements d'autres écoles
   └─ Modifier les facturations
```

### 🚫 ARCHIVAGE/SUPPRESSION

```
❌ Suppression DÉFINITIVE
   └─ L'admin NE PEUT QUE archiver (pas supprimer définitivement)
   └─ Les enseignants/élèves sont archivés, pas supprimés
   └─ Les données restent dans la BD pour l'historique

✅ Archivage (Soft Delete)
   └─ Classe peut être archivée → invisible par défaut
   └─ Peut être restaurée si besoin
```

---

## 🔐 SÉCURITÉ - CAS D'USAGE

### Scenario 1: Admin Maternelle tente d'ajouter un homme

```
Admin Maternelle:
├─ User: directrice.maternelle@lumo.app
├─ École: "Les Bambins" (ID=1)
├─ Niveau: Maternelle

Action: Ajouter M. François comme enseignant

Résultat:
❌ POST /api/schools/1/teachers
   Status: 422 Unprocessable Entity
   
   Errors: {
     "gender": "La Maternelle accepte uniquement les enseignantes (F)."
   }
```

### Scenario 2: Admin Maternelle tente d'accéder Primaire

```
Admin Maternelle:
├─ User: directrice.maternelle@lumo.app
├─ Token: eyJ0eXAi...

Action: GET /api/schools/2 (École Primaire ID=2)

Résultat:
❌ HTTP 403 Forbidden
   {
     "message": "Cette action n'est pas autorisée."
   }

Note: Même s'il connaît l'URL/ID de l'école Primaire,
      le système rejette la requête avec 403
```

### Scenario 3: Admin Primaire crée classe avec variante

```
Admin Primaire:
├─ User: directeur.primaire@lumo.app
├─ École: "Horizon Nouveau" (ID=2)
├─ Niveau: Primaire

Action: Créer classe "1er C"

Résultat:
✅ POST /api/schools/2/classes
   Status: 201 Created
   
   Data: {
     "id": 125,
     "name": "1er C",
     "level": "1er",
     "class_variant": "C",
     "full_name": "1er C",
     "max_students": 28
   }
```

---

## 📋 TABLEAU RÉCAPITULATIF

| Capacité | Maternelle | Primaire |
|----------|-----------|----------|
| Créer classe | Oui (max 3) | Oui (max 6/niveau) |
| Variantes classe | ❌ Non | ✅ Oui (A, B, C...) |
| Ajouter enseignant | Femmes seulement (F) | Hommes ET femmes |
| Ajouter assistant | ✅ Oui | ✅ Oui |
| Gérer présences | ✅ Oui | ✅ Oui |
| Consulter notes | ✅ Oui | ✅ Oui |
| Planifier rotations | ✅ Oui | ✅ Oui |
| Voir autre école | ❌ Non | ❌ Non |
| Créer utilisateur | ❌ Non | ❌ Non |
| Accès système | ❌ Non | ❌ Non |
| Modifier sous-module | ❌ Non | ❌ Non |

---

## 🎯 ISOLEMENT GARANTIS

### Données visibles par Admin Maternelle:
```
École Maternelle (ID=1)
├─ 3 classes (1er, 2e, 3e)
├─ 3 enseignantes + 1 assistante
├─ 12 élèves (4 par classe)
└─ Rapports/statistiques pour ces données
```

### Données NON accessibles:
```
❌ École Primaire (ID=2) - Complètement cachée
❌ Autres écoles quelconques
❌ Utilisateurs d'autres écoles
❌ Données archivées (sauf filtre spécifique)
```

---

## 🛠️ IMPLÉMENTATION TECHNIQUE

### Validation Genre (TeacherService):
```php
// Vérifie automatiquement:
if (école = Maternelle AND gender != 'F') {
  throw ValidationException("Femmes uniquement")
}
```

### Validation Niveaux (SchoolClassService):
```php
// Vérifie automatiquement:
if (école = Maternelle AND level NOT IN [1er, 2e, 3e]) {
  throw ValidationException("Niveaux autorisés: 1er, 2e, 3e")
}
```

### Isolation Données (SchoolPolicy):
```php
// Chaque requête vérifie:
if (user.school_id != requested_school_id) {
  return 403 Forbidden
}
```

---

## ✨ RÉSUMÉ EN UNE PHRASE

**L'admin gère 100% son école (classes, enseignants, élèves) mais voit UNIQUEMENT son école, jamais une autre, avec validations strictes sur le genre (Maternelle) et les niveaux.**

---

*Dernière mise à jour: 9 mai 2026*
