<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasFactory;
    protected $table = 'rental';
    protected $primaryKey = 'rental_id';
    protected $fillable = ['user_id', 'borrowed_at', 'due_date', 'returned_at', 'rental_status', 'status_delete'];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function rentalBooks()
    {
        return $this->hasMany(RentalDetail::class, 'rental_id');
    }
}
