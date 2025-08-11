<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShopifyService
{
    protected string $shop;
    protected string $token;
    protected string $apiVersion;

    public function __construct()
    {
        $this->shop = config('services.shopify.shop');
        $this->token = config('services.shopify.token');
        $this->apiVersion = config('services.shopify.version', '2025-01');
    }

    protected function endpoint(): string
    {
        return "https://{$this->shop}/admin/api/{$this->apiVersion}/graphql.json";
    }

    public function query(string $query, array $variables = [])
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->token,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint(), [
                    'query' => $query,
                    'variables' => $variables,
                ]);

        return $response->throw()->json(); // throws if 4xx/5xx
    }

    public function fetchProducts(int $first = 50)
    {
        $query = <<<'GRAPHQL'
        query($first: Int!){
        products(first: $first) {
            edges {
            node {
                id
                title
                handle
                descriptionHtml
                variants(first: 10) {
                edges {
                    node { id sku price inventoryQuantity }
                }
                }
            }
            }
        }
        }
        GRAPHQL;
        $res = $this->query($query, ['first' => $first]);
        return $res['data']['products']['edges'] ?? [];
    }
}
