<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShopifyService;
use App\Models\ShopifyProduct;
use Illuminate\Http\Request;

class ShopifySyncController extends Controller
{
    public function sync(ShopifyService $shopify, Request $request)
    {
        $edges = $shopify->fetchProducts(50);
        $count = 0;
        $syncedProducts = [];

        foreach ($edges as $edge) {
            $node = $edge['node'];
            $firstImage = $node['images']['nodes'][0] ?? null;
            $variants = $node['variants']['edges'] ?? [];
            $prices = collect($variants)->pluck('node.price')->map(fn($p) => (float) $p);

            $product = ShopifyProduct::updateOrCreate(
                ['shopify_id' => $node['id']],
                [
                    'title' => $node['title'],
                    'handle' => $node['handle'] ?? null,
                    'price' => $prices->min(),
                    'description_html' => $node['descriptionHtml'] ?? null,
                    'variants' => $node['variants']['edges'] ?? [],
                    'image_url' => $firstImage['url'] ?? null,
                    // 'image_alt' => isset($firstImage['altText']) ? substr($firstImage['altText'], 0, 255) : null,
                    'image_alt' => $firstImage['altText'] ?? null,
                ]
            );

            $syncedProducts[] = [
                'shopify_id' => $product->shopify_id,
                'title' => $product->title,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'image_alt' => $product->image_alt,
            ];

            $count++;
        }

        return response()->json([
            'synced' => $count,
            'products' => $syncedProducts,
        ]);
    }
}
