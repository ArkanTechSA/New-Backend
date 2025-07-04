<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'phone_code', 'status'];

    public function users()
    {
        return $this->hasMany(User::class, 'country', 'id');
    }
}
