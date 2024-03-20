<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class SoftwareProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        // Add other fillable attributes as needed
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'stock'
    ];

    /**
     * Get the linceses for the software product.
     */
    public function licenses()
    {
        return $this->hasMany(SoftwareProductLicense::class);
    }

    
    /**
     * Get the stock count for the software product.
     *
     * @return int
     */
    public function getStockCountAttribute()
    {
        // Use the items relationship to count the number of related items
        return $this->items()->count();
    }
}