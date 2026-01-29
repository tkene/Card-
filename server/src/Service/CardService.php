<?php

namespace App\Service;

class CardService
{
    private function getAllColors(): array
    {
        return [
            ['symbol' => '♦️', 'name' => 'Carreaux'],
            ['symbol' => '♥️', 'name' => 'Cœurs'],
            ['symbol' => '♠️', 'name' => 'Piques'],
            ['symbol' => '♣️', 'name' => 'Trèfles'],
        ];
    }

    private function getAllValues(): array
    {
        return ['As', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Valet', 'Dame', 'Roi'];
    }

    public function generateRandomColorOrder(): array
    {
        $colors = $this->getAllColors();
        shuffle($colors);
        return $colors;
    }

    public function generateRandomValuesOrder(): array
    {
        $values = $this->getAllValues();
        shuffle($values);
        return $values;
    }

    public function sortHand(array $hand, array $colorOrder, array $valuesOrder): array
    {
        $colorIndexMap = [];
        foreach ($colorOrder as $index => $color) {
            $colorIndexMap[$color['name']] = $index;
        }

        $valueIndexMap = [];
        foreach ($valuesOrder as $index => $value) {
            $valueIndexMap[$value] = $index;
        }

        usort($hand, function ($a, $b) use ($colorIndexMap, $valueIndexMap) {
            $colorIndexA = $colorIndexMap[$a['color']] ?? PHP_INT_MAX;
            $colorIndexB = $colorIndexMap[$b['color']] ?? PHP_INT_MAX;
            
            if ($colorIndexA !== $colorIndexB) {
                return $colorIndexA <=> $colorIndexB;
            }
            
            $valueIndexA = $valueIndexMap[$a['value']] ?? PHP_INT_MAX;
            $valueIndexB = $valueIndexMap[$b['value']] ?? PHP_INT_MAX;
            
            return $valueIndexA <=> $valueIndexB;
        });

        return $hand;
    }
}
