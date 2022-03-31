<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalArchive extends Model
{
    use HasFactory;

    protected $dateFormat = "Y-m-d h:i:s";

    protected $fillable = [
        'total_categories',
        'total_products',
        'total_users',
        'issue_date'
    ];
}
