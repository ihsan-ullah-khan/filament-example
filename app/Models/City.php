<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
    ];

    public function State()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function Image()
    {
        return $this->hasOne(Upload::class, 'upload_id');
    }
}
