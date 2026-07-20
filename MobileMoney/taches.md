# Taches.md - Projet Mobile Money (v1)

## Responsable partie Opérateur
- [ ] Configuration des préfixes valides
- [ ] Gestion des barèmes de frais (consultation/modification)
- [ ] Suivi des gains (Retrait + Transfert)
- [ ] Situation des comptes clients

## Détails des tâches - Partie Opérateur

### 1. Configuration des préfixes
- [ ] Créer la vue `admin/prefixes.php` (Formulaire ajout/suppression).
- [ ] Implémenter `PrefixeModel` et les méthodes `index`, `add`, `delete` dans `OperatorController`.

### 2. Gestion des barèmes (Tranches de frais)
- [ ] Créer la vue `admin/baremes.php` (Tableau des tranches avec formulaires de modification).
- [ ] Implémenter la logique `update` dans `OperatorController` pour modifier les frais fixes/pourcentages dans `tranches_frais`.

### 3. Suivi des gains
- [ ] Créer la vue `admin/gains.php` (Affichage du cumul des frais).
- [ ] Implémenter la requête `SELECT SUM(frais) FROM transactions WHERE id_type_operation IN (2, 3)` dans le modèle ou contrôleur.

### 4. Situation des comptes clients
- [ ] Créer la vue `admin/clients.php` (Tableau affichant `numero_telephone` et `solde`).
- [ ] Implémenter la récupération de tous les clients via `ClientModel`.

