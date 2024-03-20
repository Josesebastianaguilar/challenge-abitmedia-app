<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class SoftwareProductPrice extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'software_product_sku',
        'operative_system_slug',
        // Add other fillable attributes as needed
    ];

    protected $appends = [
        'software_product',
        'operative_system',
        // Add other fillable attributes as needed
    ];
    
    /**
     * Get the software product name asociated with the license.
     *
     * @return SoftwareProduct|null
     */
    public function softwareProduct()
    {
        if ($this) {
            return softwareProduct::where('sku', $this->software_product_sku)->first();
        } else {
            return null;
        }
    }

    /**
     * Get the software product name asociated with the license.
     *
     * @return OperativeSystem|null
     */
    public function operativeSystem()
    {
        if ($this) {
            return OperativeSystem::where('slug', $this->operative_system_slug)->first();
        } else {
            return null;
        }
    }

    /**
     * Get the software product name asociated with the license.
     *
     * @return String
     */
    public function getSoftwareProductAttribute()
    {
        if ($this->softwareProduct()) {
            return $this->softwareProduct()->name;
        } else {
            return '';
        }
    }

    public function getOperativeSystemAttribute()
    {
        if ($this->operativeSystem()) {
            return $this->operativeSystem()->name;
        } else {
            return '';
        }
    }
}