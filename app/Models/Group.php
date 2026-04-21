<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];

    public function users() { return $this->hasMany(User::class); }
    public function novels() { return $this->hasMany(Novel::class); }
}