<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    use HasFactory;
    protected $table = 'sub_districts';
    protected $primaryKey = 'sub_district_id';
    public $timestamps = false;

    // Define the relationship with the District model
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    // Add other model properties or relationships here
}