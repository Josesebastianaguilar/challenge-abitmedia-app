<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class SoftwareProductLicense extends Model
{
    use HasFactory;
    

    protected $table = "software_product_licenses";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial',
        'software_product_sku',
        'operative_system_slug',
        // Add other fillable attributes as needed
    ];

    protected $appends = [
        'software_product',
        'price'
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
            return SoftwareProduct::where('sku', $this->software_product_sku)->first();
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
            OperativeSystem::where('slug', $this->operative_system_slug)->first();
        } else {
            return null;
        }
    }

    /**
     * Get the software product price name asociated with the license.
     *
     * @return SoftwareProductPrice
     */
    public function price()
    {
        if (!$this->softwareProduct() || !$this->operativeSystem()) {
            return null;
        } else {
            return SoftwareProductPrice::where('software_product_sku', $this->softwareProduct()->sku)
            ->where('operative_system_slug', $this->operativeSystem()->slug)
            ->first();
        }
    }

    /**
     * Get the software product name asociated with the license.
     *
     * @return string
     */
    public function getSoftwareProductAttribute()
    {
        if ($this->softwareProduct()) {
            return $this->softwareProduct()->name;
        }  else {
            return '';
        }
    }

    /**
     * Get the operative system name asociated with the license.
     *
     * @return string
     */
    /* public function getOperativeSystemAttribute()
    {
        if ($this->operativeSystem()) {
            return $this->operativeSystem()->name;
        } else {
            return '';
        }
    } */

    /**
     * Generate a serial number for the license.
     *
     * @param String $sku
     * @param String $os_slug
     * @param int $licenses_count
     * @return void
     */
    public function generateSerialNumber(String $sku, String $os_slug, int $licenses_count)
    {
        $maxOsSlugLength = 20; // Adjust this according to your requirements
        // Truncate SKU and OS slug if they exceed maximum lengths
        $truncatedOsSlug = substr($os_slug, 0, $maxOsSlugLength);
        // Calculate the remaining length for the license count
        $remainingLength = 100 - strlen($sku) - strlen($truncatedOsSlug) - strlen($licenses_count);
        $randomString = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
        // Trim the random string to fit the remaining length
        $trimmedRandomString = substr($randomString, 0, $remainingLength);
        return $sku . $truncatedOsSlug . $trimmedRandomString . $licenses_count;
    }

    /**
     * Get the operative system name asociated with the license.
     *
     * @return float
     */
    public function getPriceAttribute()
    {
        if ($this->price()) {
            return $this->price()->value;
        } else {
            return 0;
        }
    }
}