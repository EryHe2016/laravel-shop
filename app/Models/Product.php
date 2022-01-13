<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'on_sale',
        'rating', 'sold_count', 'review_count', 'price'
    ];

    protected $casts = [
        'on_sale' => 'boolean',
    ];

    public function skus()
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        if(Str::startsWith($this->attributes['image'], ['http://', 'https://'])){
            return $this->attributes['image'];
        }
        //\Storage::disk()的参数public要和config/admin.php里的upload.disk配置一致
        return \Storage::disk('public')->url($this->attributes['image']);
    }

}
