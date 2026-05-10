<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'supplier_id',
        'name', 
        'barcode', 
        'purchase_price', 
        'selling_price', 
        'stock_quantity', 
        'unit',
        'image_path',
        'is_stockable'
    ];

    protected $casts = [
        'is_stockable' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
