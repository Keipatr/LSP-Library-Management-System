<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalDetail extends Model
{
    use HasFactory;
    protected $table = 'rental_detail';

    protected $primaryKey = 'id';
    protected $fillable = ['rental_id', 'book_id'];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id', 'rental_id');
    }

    public function book()
    {
        return $this->belongsTo(Books::class, 'book_id', 'book_id');
    }
}
