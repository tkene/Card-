<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/game/initialize' => [[['_route' => 'api_game_initialize', '_controller' => 'App\\Controller\\Api\\GameApiController::initialize'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/color-order' => [[['_route' => 'api_game_color_order', '_controller' => 'App\\Controller\\Api\\GameApiController::getColorOrder'], null, ['GET' => 0], null, false, false, null]],
        '/api/game/color-order/new' => [[['_route' => 'api_game_color_order_new', '_controller' => 'App\\Controller\\Api\\GameApiController::generateNewColorOrder'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/color-order/reorder' => [[['_route' => 'api_game_color_order_reorder', '_controller' => 'App\\Controller\\Api\\GameApiController::reorderColors'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/color-order/confirm' => [[['_route' => 'api_game_color_order_confirm', '_controller' => 'App\\Controller\\Api\\GameApiController::confirmColorOrder'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/values-order' => [[['_route' => 'api_game_values_order', '_controller' => 'App\\Controller\\Api\\GameApiController::getValuesOrder'], null, ['GET' => 0], null, false, false, null]],
        '/api/game/values-order/new' => [[['_route' => 'api_game_values_order_new', '_controller' => 'App\\Controller\\Api\\GameApiController::generateNewValuesOrder'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/values-order/reorder' => [[['_route' => 'api_game_values_order_reorder', '_controller' => 'App\\Controller\\Api\\GameApiController::reorderValues'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/values-order/confirm' => [[['_route' => 'api_game_values_order_confirm', '_controller' => 'App\\Controller\\Api\\GameApiController::confirmValuesOrder'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/cards-number' => [[['_route' => 'api_game_cards_number', '_controller' => 'App\\Controller\\Api\\GameApiController::confirmCardsNumber'], null, ['POST' => 0], null, false, false, null]],
        '/api/game/unsorted-hand' => [[['_route' => 'api_game_unsorted_hand', '_controller' => 'App\\Controller\\Api\\GameApiController::getUnsortedHand'], null, ['GET' => 0], null, false, false, null]],
        '/api/game/sorted-hand' => [[['_route' => 'api_game_sorted_hand', '_controller' => 'App\\Controller\\Api\\GameApiController::getSortedHand'], null, ['GET' => 0], null, false, false, null]],
        '/api/game/reset' => [[['_route' => 'api_game_reset', '_controller' => 'App\\Controller\\Api\\GameApiController::resetGame'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
