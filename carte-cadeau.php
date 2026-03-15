<?php

class GiftCard
{
    private $code;
    private $montant;
    private $status;

    public function __construct($code, $montant)
    {
        $this->code = $code;
        $this->montant = $montant;
        $this->status = '1';
    }


    public function CreateCard($code, $montant)
    {
        return new GiftCard($code, $montant);
    }

    public function BlockCard()
    {
        $this->status = '0';
        
        return "Carte bloquée avec succès.";
    }
    
    public function UnblockCard()
    {
        $this->status = '1';
        return "Carte débloquée avec succès.";
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function isActive()
    {
        return $this->status === '1';
    }

    public function CreditCard($amount)
    {
        if ($this->status === '1') {
            $this->montant += $amount;
            return "Carte créditée de " . number_format($amount / 100, 2, ',', ' ') . " €. Nouveau montant: " . number_format($this->montant / 100, 2, ',', ' ') . " €.";
        } else {
            return "Impossible de créditer une carte bloquée.";
        }
    }

    public function DebitCard($amount)
    {
        if ($this->status === '1') {
            if ($this->montant >= $amount && $this->montant > 0) {
                $this->montant -= $amount;
                return "Carte débitée de " . number_format($amount / 100, 2, ',', ' ') . " €. Nouveau montant: " . number_format($this->montant / 100, 2, ',', ' ') . " €.";
            } else {
                return "Fonds insuffisants pour débiter la carte.";
            }
        } else {
            return "Impossible de débiter une carte bloquée.";
        }
    }
}