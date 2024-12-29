<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Books extends Model
{
    use HasFactory;

    protected $primaryKey = 'book_id';
    protected $fillable = ['title', 'author', 'publisher', 'publication_year', 'isbn', 'book_status', 'status_delete'];
    public function rentalBooks()
    {
        return $this->belongsToMany(Rental::class, 'rental_book', 'book_id', 'rental_id');
    }
}
