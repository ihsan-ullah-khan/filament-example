<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'country_id', 'upload_id'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function image()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
