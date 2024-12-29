<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'email', 'password', 'role', 'status_delete'];
    protected $hidden = [
        "password",
    ];
    public function rental()
    {
        return $this->hasMany(Rental::class, 'user_id');
    }
}
