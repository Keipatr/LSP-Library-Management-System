<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Books extends Model
{
    use HasFactory;

    protected $table = "books";
    protected $primaryKey = 'book_id';
    protected $fillable = ['title', 'author', 'publisher', 'publication_year', 'isbn', 'book_status', 'stock', 'status_delete'];
    public function rentalDetails()
    {
        return $this->hasMany(RentalDetail::class, 'book_id', 'book_id');
    }
}
