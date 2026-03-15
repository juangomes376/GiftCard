<?php
require_once __DIR__ . '/carte-cadeau.php';
session_start();

if (!isset($_SESSION['cards'])) {
    $_SESSION['cards'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Card Management</title>
</head>
<?php

// flash handling
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);


function fmt_euro(int $cents): string
{
    return number_format($cents / 100, 2, ',', ' ') . ' €';
}
?>

<body>
<div class="container">
    <h1>Gestion des Cartes Cadeaux</h1>

    <?php if ($flash): ?>
        <div class="flash"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <section>
        <h2>Créer une carte</h2>
        <form method="post" action="script.php">
            <input type="hidden" name="action" value="create">
            <label>Code : <input name="code" required></label>
            <label style="margin-left:12px">Solde initial (€) : <input name="amount" type="text" value="0.00" required></label>
            <button type="submit">Créer</button>
        </form>
    </section>

    <section>
        <h2 style="margin-top:20px">Cartes existantes (session)</h2>

        <?php if (empty($_SESSION['cards'])): ?>
            <p>Aucune carte pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Solde</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($_SESSION['cards'] as $card): ?>
                    <tr>
                        <td><?= htmlspecialchars($card->getCode()) ?></td>
                        <td><?= fmt_euro((int)$card->getMontant()) ?></td>
                        <td>
                            <?php if ($card->isActive()): ?>
                                <span class="status-active">Active</span>
                            <?php else: ?>
                                <span class="status-blocked">Bloquée</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions" style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                <form method="post" action="script.php" class="inline">
                                    <input type="hidden" name="action" value="block">
                                    <input type="hidden" name="code" value="<?= htmlspecialchars($card->getCode()) ?>">
                                    <button type="submit">Bloquer</button>
                                </form>

                                <form method="post" action="script.php" class="inline">
                                    <input type="hidden" name="action" value="unblock">
                                    <input type="hidden" name="code" value="<?= htmlspecialchars($card->getCode()) ?>">
                                    <button type="submit">Débloquer</button>
                                </form>
                            </div>
                            <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                <form method="post" action="script.php" class="inline">
                                    <input type="hidden" name="action" value="credit">
                                    <input type="hidden" name="code" value="<?= htmlspecialchars($card->getCode()) ?>">
                                    <input name="amount" type="text" placeholder="€" required>
                                    <button type="submit">Recharger</button>
                                </form>

                                <form method="post" action="script.php" class="inline">
                                    <input type="hidden" name="action" value="debit">
                                    <input type="hidden" name="code" value="<?= htmlspecialchars($card->getCode()) ?>">
                                    <input name="amount" type="text" placeholder="€" required>
                                    <button type="submit">Débiter</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

</div>
</body>
</html>