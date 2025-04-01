<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capital',
        'upload_id',
        'description',
        'is_visible',
    ];


    public function image()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

}
