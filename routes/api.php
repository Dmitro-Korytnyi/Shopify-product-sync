<?php

use App\Http\Controllers\Api\ShopifySyncController;
Route::post('/shopify/sync', [ShopifySyncController::class, 'sync']);
Route::get('/mock/shopify/products', fn() => response()->json([
    'data' => [
        'products' => [
            'edges' => [
                [
                    'node' => [
                        'id' => 'gid://shopify/Product/1',
                        'title' => 'Mock Product 1',
                        'handle' => 'mock-prod-1',
                        'descriptionHtml' => '<p>Description here</p>',
                        'variants' => [
                            'edges' => [
                                [
                                    'node' => [
                                        'id' => 'gid://shopify/ProductVariant/1',
                                        'sku' => 'SKU-1',
                                        'price' => '9.99',
                                        'inventoryQuantity' => 10
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                // add more product nodes as needed
            ]
        ]
    ]
]));
