<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class SoftwareProductLicense extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'serial',
        'software_product_sku',
        'operative_system_slug',
        // Add other fillable attributes as needed
    ];

    protected $appends = [
        'software_product',
        'operative_system',
        'price'
        // Add other fillable attributes as needed
    ];
    
    /**
     * Get the software product name asociated with the license.
     *
     * @return belongsTo
     */
    public function softwareProduct()
    {
        return $this->belongsTo(SoftwareProduct::class);
    }

    /**
     * Get the software product name asociated with the license.
     *
     * @return belongsTo
     */
    public function operativeSystem()
    {
        return $this->belongsTo(OperativeSystem::class);
    }

    /**
     * Get the software product price name asociated with the license.
     *
     * @return SoftwareProductPrice
     */
    public function price()
    {
        return SoftwareProductPrice::where('software_product_sku', $this->softwareProduct->sku)
            ->where('operative_system_slug', $this->operativeSystem->slug)
            ->first();
    }

    /**
     * Get the software product name asociated with the license.
     *
     * @return string
     */
    public function getSoftwareProductAttribute()
    {
        return $this->softwareProduct;
    }

    /**
     * Get the operative system name asociated with the license.
     *
     * @return string
     */
    public function getOperativeSystemNameAttribute()
    {
        return $this->operativeSystem;
    }

    /**
     * Get the operative system name asociated with the license.
     *
     * @return float
     */
    public function getPriceAttribute()
    {
        return $this->price()->value;
    }
}