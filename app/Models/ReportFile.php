<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'report_file_id';

    protected $fillable = [
        'report_id',
        'file_name',
        'file_path',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}