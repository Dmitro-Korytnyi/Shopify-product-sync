<?php   

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShopifyProduct extends Model
{
    protected $table = 'shopify_products';
    protected $fillable = ['shopify_id','title','handle','description_html','variants','image_url','image_alt','price'];
    protected $casts = ['variants' => 'array'];
}
