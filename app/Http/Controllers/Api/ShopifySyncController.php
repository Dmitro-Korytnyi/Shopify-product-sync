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
        foreach ($edges as $edge) {
            $node = $edge['node'];
            ShopifyProduct::updateOrCreate(
                ['shopify_id' => $node['id']],
                [
                    'title' => $node['title'],
                    'handle' => $node['handle'] ?? null,
                    'description_html' => $node['descriptionHtml'] ?? null,
                    'variants' => $node['variants']['edges'] ?? [],
                ]
            );
            $count++;
        }
        return response()->json(['synced' => $count]);
    }
}
