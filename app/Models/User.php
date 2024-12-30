<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'email', 'password', 'role', 'status_delete'];
    protected $hidden = [
        "password",
    ];
    public function rental()
    {
        return $this->hasMany(Rental::class, foreignKey: 'user_id', localKey: 'user_id');
    }
}
