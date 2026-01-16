<?php

namespace App\Tests\Service;

use App\Service\CardService;
use PHPUnit\Framework\TestCase;

class CardServiceTest extends TestCase
{
    private CardService $cardService;

    protected function setUp(): void
    {
        $this->cardService = new CardService();
    }

    public function testSortHand(): void
    {
        // Arrange : Préparer les données de test
        $hand = [
            ['value' => 'Roi', 'color' => 'Piques'],
            ['value' => 'As', 'color' => 'Carreaux'],
            ['value' => '2', 'color' => 'Carreaux'],
            ['value' => 'As', 'color' => 'Piques'],
        ];

        $colorOrder = [
            ['symbol' => '♦️', 'name' => 'Carreaux'],
            ['symbol' => '♥️', 'name' => 'Cœurs'],
            ['symbol' => '♠️', 'name' => 'Piques'],
            ['symbol' => '♣️', 'name' => 'Trèfles'],
        ];

        $valuesOrder = ['As', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Valet', 'Dame', 'Roi'];

        // Act : Exécuter la méthode à tester
        $sortedHand = $this->cardService->sortHand($hand, $colorOrder, $valuesOrder);

        // Assert : Vérifier les résultats
        $this->assertCount(4, $sortedHand);
        
        // Vérifier que les cartes sont triées par couleur d'abord (Carreaux avant Piques)
        $this->assertEquals('Carreaux', $sortedHand[0]['color']);
        $this->assertEquals('Carreaux', $sortedHand[1]['color']);
        $this->assertEquals('Piques', $sortedHand[2]['color']);
        $this->assertEquals('Piques', $sortedHand[3]['color']);
        
        // Vérifier que les cartes de même couleur sont triées par valeur (As avant 2, As avant Roi)
        $this->assertEquals('As', $sortedHand[0]['value']);
        $this->assertEquals('2', $sortedHand[1]['value']);
        $this->assertEquals('As', $sortedHand[2]['value']);
        $this->assertEquals('Roi', $sortedHand[3]['value']);
    }
}

