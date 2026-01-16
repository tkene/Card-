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

    /**
     * Réorganise l'ordre d'un tableau en déplaçant un élément vers le haut ou le bas
     * 
     * @param array $array Le tableau à réorganiser
     * @param int $index L'index de l'élément à déplacer
     * @param string $direction 'up' pour monter, 'down' pour descendre
     * @return array|null Le nouveau tableau réorganisé, ou null si le déplacement est invalide
     */
    public function reorderArray(array $array, int $index, string $direction): ?array
    {
        $newIndex = $direction === 'up' ? $index - 1 : $index + 1;
        
        // Vérifier que les indices sont valides
        if ($newIndex < 0 || $newIndex >= count($array)) {
            return null;
        }
        
        // Créer une copie pour ne pas modifier l'original
        $reorderedArray = $array;
        
        // Échanger les éléments
        [$reorderedArray[$index], $reorderedArray[$newIndex]] = [
            $reorderedArray[$newIndex], 
            $reorderedArray[$index]
        ];
        
        return $reorderedArray;
    }

    /**
     * Réorganise l'ordre des couleurs en déplaçant un élément vers le haut ou le bas
     * 
     * @param array $colorOrder L'ordre actuel des couleurs
     * @param int $index L'index de l'élément à déplacer
     * @param string $direction 'up' pour monter, 'down' pour descendre
     * @return array|null Le nouvel ordre des couleurs, ou null si le déplacement est invalide
     */
    public function reorderColors(array $colorOrder, int $index, string $direction): ?array
    {
        return $this->reorderArray($colorOrder, $index, $direction);
    }

    /**
     * Réorganise l'ordre des valeurs en déplaçant un élément vers le haut ou le bas
     * 
     * @param array $valuesOrder L'ordre actuel des valeurs
     * @param int $index L'index de l'élément à déplacer
     * @param string $direction 'up' pour monter, 'down' pour descendre
     * @return array|null Le nouvel ordre des valeurs, ou null si le déplacement est invalide
     */
    public function reorderValues(array $valuesOrder, int $index, string $direction): ?array
    {
        return $this->reorderArray($valuesOrder, $index, $direction);
    }
}
