<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'product',
        'shop',
        'category_id',
        'district_id',
        'sub_district_id',
        'address',
        'phone',
        'website',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function subDistrict()
    {
        return $this->belongsTo(SubDistrict::class, 'sub_district_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reportFiles()
    {
        return $this->hasMany(ReportFile::class, 'report_id');
    }
}