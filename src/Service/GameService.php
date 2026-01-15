<?php

namespace App\Service;

class GameService
{
    public function validateCardsNumber(int $numberOfCards): array
    {
        if ($numberOfCards < 1 || $numberOfCards > 52) {
            return ['error' => 'Le nombre de cartes doit être entre 1 et 52.'];
        }
        return ['success' => true];
    }

    public function generateRandomHand(array $colorOrder, array $valuesOrder, int $numberOfCards): array
    {
        $allCards = [];

        foreach ($colorOrder as $color) {
            foreach ($valuesOrder as $value) {
                $allCards[] = [
                    'value' => $value,
                    'color' => $color['name'],
                    'symbol' => $color['symbol'],
                ];
            }
        }
        
        shuffle($allCards);

        $hand = [];
        $maxCards = min($numberOfCards, count($allCards));
        for ($i = 0; $i < $maxCards; $i++) {
            $hand[] = $allCards[$i];
        }
        
        return $hand;
    }
}
