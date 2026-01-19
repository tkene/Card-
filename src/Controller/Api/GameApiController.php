<?php

namespace App\Controller\Api;

use App\Service\CardService;
use App\Service\GameService;
use App\Service\GameStateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/game', name: 'api_game_')]
class GameApiController extends AbstractController
{
    private CardService $cardService;
    private GameService $gameService;
    private GameStateService $gameStateService;

    public function __construct(
        GameService $gameService,
        CardService $cardService,
        GameStateService $gameStateService
    ) {
        $this->gameService = $gameService;
        $this->cardService = $cardService;
        $this->gameStateService = $gameStateService;
    }

    #[Route('/initialize', name: 'initialize', methods: ['POST'])]
    public function initialize(SessionInterface $session): JsonResponse
    {
        $this->gameStateService->initializeGame($session);
        return new JsonResponse(['success' => true]);
    }

    #[Route('/color-order', name: 'color_order', methods: ['GET'])]
    public function getColorOrder(SessionInterface $session): JsonResponse
    {
        $colorOrder = $this->gameStateService->getColorOrder($session);
        return new JsonResponse(['colorOrder' => $colorOrder]);
    }

    #[Route('/color-order/new', name: 'color_order_new', methods: ['POST'])]
    public function generateNewColorOrder(SessionInterface $session): JsonResponse
    {
        $colorOrder = $this->cardService->generateRandomColorOrder();
        $this->gameStateService->setColorOrder($session, $colorOrder);
        return new JsonResponse(['colorOrder' => $colorOrder]);
    }

    #[Route('/color-order/reorder', name: 'color_order_reorder', methods: ['POST'])]
    public function reorderColors(SessionInterface $session, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $index = $data['index'] ?? null;
        $direction = $data['direction'] ?? null;

        if ($index === null || $direction === null) {
            return new JsonResponse(['error' => 'Index et direction requis'], 400);
        }

        $colorOrder = $this->gameStateService->getColorOrder($session);
        if ($colorOrder === null) {
            return new JsonResponse(['error' => 'Ordre des couleurs non initialisé'], 400);
        }

        $reorderedColors = $this->gameService->reorderColors($colorOrder, (int)$index, $direction);
        
        if ($reorderedColors === null) {
            return new JsonResponse(['error' => 'Déplacement invalide'], 400);
        }

        $this->gameStateService->setColorOrder($session, $reorderedColors);
        return new JsonResponse(['colorOrder' => $reorderedColors]);
    }

    #[Route('/color-order/confirm', name: 'color_order_confirm', methods: ['POST'])]
    public function confirmColorOrder(SessionInterface $session): JsonResponse
    {
        if ($this->gameStateService->getColorOrder($session) === null) {
            return new JsonResponse(['error' => 'Ordre des couleurs non initialisé'], 400);
        }

        $this->gameStateService->confirmColorOrder($session);
        return new JsonResponse(['success' => true]);
    }

    #[Route('/values-order', name: 'values_order', methods: ['GET'])]
    public function getValuesOrder(SessionInterface $session): JsonResponse
    {
        $valuesOrder = $this->gameStateService->getValuesOrder($session);
        return new JsonResponse(['valuesOrder' => $valuesOrder]);
    }

    #[Route('/values-order/new', name: 'values_order_new', methods: ['POST'])]
    public function generateNewValuesOrder(SessionInterface $session): JsonResponse
    {
        if (!$this->gameStateService->isColorOrderConfirmed($session)) {
            return new JsonResponse(['error' => 'L\'ordre des couleurs doit être confirmé d\'abord'], 400);
        }

        $valuesOrder = $this->cardService->generateRandomValuesOrder();
        $this->gameStateService->setValuesOrder($session, $valuesOrder);
        return new JsonResponse(['valuesOrder' => $valuesOrder]);
    }

    #[Route('/values-order/reorder', name: 'values_order_reorder', methods: ['POST'])]
    public function reorderValues(SessionInterface $session, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $index = $data['index'] ?? null;
        $direction = $data['direction'] ?? null;

        if ($index === null || $direction === null) {
            return new JsonResponse(['error' => 'Index et direction requis'], 400);
        }

        $valuesOrder = $this->gameStateService->getValuesOrder($session);
        if ($valuesOrder === null) {
            return new JsonResponse(['error' => 'Ordre des valeurs non initialisé'], 400);
        }

        $reorderedValues = $this->gameService->reorderValues($valuesOrder, (int)$index, $direction);
        
        if ($reorderedValues === null) {
            return new JsonResponse(['error' => 'Déplacement invalide'], 400);
        }

        $this->gameStateService->setValuesOrder($session, $reorderedValues);
        return new JsonResponse(['valuesOrder' => $reorderedValues]);
    }

    #[Route('/values-order/confirm', name: 'values_order_confirm', methods: ['POST'])]
    public function confirmValuesOrder(SessionInterface $session): JsonResponse
    {
        if ($this->gameStateService->getValuesOrder($session) === null) {
            return new JsonResponse(['error' => 'Ordre des valeurs non initialisé'], 400);
        }

        $this->gameStateService->confirmValuesOrder($session);
        return new JsonResponse(['success' => true]);
    }

    #[Route('/cards-number', name: 'cards_number', methods: ['POST'])]
    public function confirmCardsNumber(SessionInterface $session, Request $request): JsonResponse
    {
        if (!$this->gameStateService->isColorOrderConfirmed($session)) {
            return new JsonResponse(['error' => 'L\'ordre des couleurs doit être confirmé'], 400);
        }

        if (!$this->gameStateService->isValuesOrderConfirmed($session)) {
            return new JsonResponse(['error' => 'L\'ordre des valeurs doit être confirmé'], 400);
        }

        $data = json_decode($request->getContent(), true);
        $numberOfCardsInput = $data['numberOfCards'] ?? null;

        if ($numberOfCardsInput === null || $numberOfCardsInput === '') {
            return new JsonResponse(['error' => 'Veuillez entrer un nombre de cartes.'], 400);
        }

        $numberOfCards = (int) $numberOfCardsInput;

        $validationResult = $this->gameService->validateCardsNumber($numberOfCards);
        if (isset($validationResult['error'])) {
            return new JsonResponse(['error' => $validationResult['error']], 400);
        }

        $this->gameStateService->setNumberOfCards($session, $numberOfCards);
        return new JsonResponse(['success' => true]);
    }

    #[Route('/unsorted-hand', name: 'unsorted_hand', methods: ['GET'])]
    public function getUnsortedHand(SessionInterface $session): JsonResponse
    {
        if (!$this->gameStateService->isGameSetupComplete($session)) {
            return new JsonResponse(['error' => 'Configuration du jeu incomplète'], 400);
        }

        if (!$this->gameStateService->hasNumberOfCards($session)) {
            return new JsonResponse(['error' => 'Nombre de cartes non défini'], 400);
        }

        if (!$this->gameStateService->hasUnsortedHand($session)) {
            $handData = $this->gameStateService->getHandGenerationData($session);
            
            if ($handData === null) {
                return new JsonResponse(['error' => 'Données de génération manquantes'], 400);
            }
            
            $unsortedHand = $this->gameService->generateRandomHand(
                $handData['colorOrder'],
                $handData['valuesOrder'],
                $handData['numberOfCards']
            );
            $this->gameStateService->setUnsortedHand($session, $unsortedHand);
        }

        $drawnCards = $this->gameStateService->getUnsortedHand($session);
        return new JsonResponse(['drawnCards' => $drawnCards]);
    }

    #[Route('/sorted-hand', name: 'sorted_hand', methods: ['GET'])]
    public function getSortedHand(SessionInterface $session): JsonResponse
    {
        if (!$this->gameStateService->isGameSetupComplete($session)) {
            return new JsonResponse(['error' => 'Configuration du jeu incomplète'], 400);
        }

        if (!$this->gameStateService->hasNumberOfCards($session)) {
            return new JsonResponse(['error' => 'Nombre de cartes non défini'], 400);
        }

        if (!$this->gameStateService->hasUnsortedHand($session)) {
            return new JsonResponse(['error' => 'Main non triée non générée'], 400);
        }

        $sortingData = $this->gameStateService->getSortingData($session);
        
        if ($sortingData === null) {
            return new JsonResponse(['error' => 'Données de tri manquantes'], 400);
        }
        
        $sortedHand = $this->cardService->sortHand(
            $sortingData['unsortedHand'],
            $sortingData['colorOrder'],
            $sortingData['valuesOrder']
        );
        $this->gameStateService->setSortedHand($session, $sortedHand);

        return new JsonResponse([
            'sortedCards' => $sortedHand,
            'colorOrder' => $sortingData['colorOrder'],
            'valuesOrder' => $sortingData['valuesOrder'],
        ]);
    }

    #[Route('/reset', name: 'reset', methods: ['POST'])]
    public function resetGame(SessionInterface $session): JsonResponse
    {
        $this->gameStateService->clearGame($session);
        return new JsonResponse(['success' => true]);
    }
}

