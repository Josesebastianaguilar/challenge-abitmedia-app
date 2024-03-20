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
}