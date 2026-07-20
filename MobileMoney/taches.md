# Taches.md - Projet Mobile Money (v1)

## Membres
- ETU3994 (Responsable Opérateur)
- ETU4104 (Responsable Client)

## Répartition des tâches - v1

### Partie Opérateur (ETU3994)
- [ ] Configuration des préfixes valides (Vue + Model + Contrôleur)
- [ ] Gestion des barèmes de frais (CRUD tranches)
- [ ] Suivi des gains (Calcul agrégé des frais)
- [ ] Situation des comptes clients (Vue d'ensemble des soldes)
- [ ] Centralisation du fichier base.sql

### Partie Client (ETU4104)
- [ ] AuthController (Login automatique par téléphone)
- [ ] Dashboard Client (Affichage solde)
- [ ] Logique d'opérations (Dépôt, Retrait, Transfert + calcul frais)
- [ ] Historique des transactions


# Version 2
- [ ] Création table `autres_operateurs` et `commission_externe`
- [ ] CRUD configuration préfixes externes
- [ ] Vue "Situation gain" séparée (Interne vs Externe)
- [ ] Vue "Situation montants à reverser par opérateur"
- [ ] Option "Inclure frais" dans le formulaire client
- [ ] Fonctionnalité d'envoi multiple (JS + Controller)