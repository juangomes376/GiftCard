<?php
require_once 'carte-cadeau.php';
session_start();

if (!isset($_SESSION['cards'])) {
    $_SESSION['cards'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            $code = trim((string)($_POST['code'] ?? ''));
            $amount = str_replace(',', '.', (string)($_POST['amount'] ?? '0'));
            $amountCents = (int) round(floatval($amount) * 100);

            if ($code === '') {
                throw new InvalidArgumentException('Le code est requis.');
            }
            if (isset($_SESSION['cards'][$code])) {
                throw new InvalidArgumentException('Une carte avec ce code existe déjà.');
            }
            if ($amountCents < 0) {
                throw new InvalidArgumentException('Le solde initial ne peut pas être négatif.');
            }

            $card = new GiftCard($code, $amountCents);
            $_SESSION['cards'][$code] = $card;
            $_SESSION['flash'] = "Carte '$code' créée (" . number_format($amountCents/100, 2, ',', ' ') . " €).";
        }

        if ($action === 'block' || $action === 'unblock' || $action === 'credit' || $action === 'debit') {
            $code = (string)($_POST['code'] ?? '');
            if ($code === '' || !isset($_SESSION['cards'][$code])) {
                throw new InvalidArgumentException('Carte introuvable.');
            }

            $card = $_SESSION['cards'][$code];

            if ($action === 'block') {
                $msg = $card->BlockCard();
                $_SESSION['flash'] = $msg;
            }

            if ($action === 'unblock') {
                $msg = $card->UnblockCard();
                $_SESSION['flash'] = $msg;
            }

            if ($action === 'credit') {
                $amount = str_replace(',', '.', (string)($_POST['amount'] ?? '0'));
                $amountCents = (int) round(floatval($amount) * 100);
                if ($amountCents <= 0) throw new InvalidArgumentException('Montant de crédit invalide.');
                $msg = $card->CreditCard($amountCents);
                $_SESSION['flash'] = $msg;
            }

            if ($action === 'debit') {
                $amount = str_replace(',', '.', (string)($_POST['amount'] ?? '0'));
                $amountCents = (int) round(floatval($amount) * 100);
                if ($amountCents <= 0) throw new InvalidArgumentException('Montant de débit invalide.');
                $msg = $card->DebitCard($amountCents);
                $_SESSION['flash'] = $msg;
            }

            // persist modified object back into session (not strictly necessary but explicit)
            $_SESSION['cards'][$code] = $card;
        }
    } catch (Throwable $e) {
        $_SESSION['flash'] = 'Erreur: ' . $e->getMessage();
    }

    header('Location: /index.php');

}