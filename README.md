# Gestionnaire de Cartes Cadeaux 

Brève description
- Petite application PHP pour gérer des **cartes cadeaux** en session. Interface HTML minimale pour créer, créditer, débiter, bloquer et débloquer des cartes.

Fichiers principaux
- `carte-cadeau.php` — classe `GiftCard`
  - Implémente les propriétés et méthodes pour manipuler une carte (`CreateCard`, `BlockCard`, `UnblockCard`, `CreditCard`, `DebitCard`, `getCode`, `getMontant`, `isActive`).
  - Utilise `montant` en **centimes** et `status` comme `'1'` / `'0'`.
  - Les méthodes retournent des chaînes et effectuent des vérifications de base (ex. : impossible d'opérer sur une carte bloquée ; contrôle du solde pour les débits).

- `script.php` — contrôleur/processor
  - Reçoit les POST depuis `index.php`, valide les entrées (format des montants, code unique, solde initial non-négatif), met à jour `$_SESSION['cards']` et place un message flash.
  - Redirige ensuite vers l'interface (`index.php`).

- `index.php` — interface / vue
  - Formulaire pour créer une carte et tableau listant les cartes stockées en session.
  - Chaque action du tableau (bloquer/débloquer/recharger/débiter) envoie un POST vers `script.php`.
  - La fonction utilitaire `fmt_euro()` convertit les centimes en chaîne euros pour l'affichage.

Comment les fichiers sont connectés (flux)
1. L'utilisateur interagit avec `index.php` (formulaire / boutons).
2. Le formulaire envoie un POST à `script.php`.
3. `script.php` valide les données, appelle les méthodes de `GiftCard` (via `carte-cadeau.php`) et met à jour `$_SESSION['cards']`.
4. `script.php` redirige vers `index.php`, qui affiche l'état courant et les messages flash.

Exécution locale 🛠️
1. Ouvrir un terminal dans le dossier du projet (`POO`).
2. Lancer le serveur PHP intégré :

```bash
php -S localhost:8000
```

3. Ouvrir dans le navigateur : `http://localhost:8000/index.php`.

Utilisation de l'interface (pas à pas) ✨
- Créer une carte : saisir un `Code` et un `Solde initial (€)` (ex. `10.00` ou `10,00`) → Cliquer sur **Créer**. (La valeur est convertie en centimes.)
- Créditer/Débiter : utiliser les champs à côté de l'action ; les montants acceptent `,` ou `.` comme séparateur décimal.
- Bloquer/Débloquer : cliquer sur les boutons correspondants.
- Les messages de succès/erreur s'affichent en haut (flash).

Règles métier implémentées ✅
- Une carte ne peut pas avoir de solde négatif (vérification à la création / lors du débit).
- Impossible de débiter une carte bloquée.
- Impossible de recharger une carte bloquée.
- Impossible de débiter plus que le solde disponible.
- Les montants sont manipulés en **centimes** pour éviter les problèmes de virgule flottante.

Souhaitez-vous que j'applique l'une de ces améliorations maintenant (p. ex. refactorer la classe pour lancer des exceptions) ?
