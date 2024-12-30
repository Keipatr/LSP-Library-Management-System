<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\RentalDetail;
use App\Models\Books;
use App\Models\User;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar untuk rental
        $query = Rental::with(['user', 'rentalDetails.book'])
            ->where('status_delete', '0')
            ->orderBy('due_date', 'desc');

        // Filter berdasarkan pencarian (anggota atau buku)
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Pencarian berdasarkan nama anggota atau judul buku
                $q->whereHas('user', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('rentalDetails.book', function ($query) use ($searchTerm) {
                        $query->where('title', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter berdasarkan status peminjaman, jika status tidak kosong atau tidak 'Semua'
        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            if ($status !== 'semua') {
                $query->where('rental_status', $status);
            }
        }

        // Menampilkan data dengan pagination
        $rentals = $query->paginate(10);

        // Ambil semua pengguna aktif
        $users = User::where('status_delete', '0')->get();

        // Ambil buku dengan stok tersedia
        $books = Books::where('status_delete', '0')->where('stock', '>', 0)->get();

        // Kembalikan ke view dengan data yang sudah difilter
        return view('rental.index', compact('rentals', 'users', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,book_id',
        ]);

        // Create Rental
        $rental = Rental::create([
            'user_id' => $request->user_id,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(7),
            'rental_status' => '0', // Dipinjam
        ]);

        // Attach Books
        foreach ($request->book_ids as $book_id) {
            RentalDetail::create([
                'rental_id' => $rental->rental_id,
                'book_id' => $book_id,
            ]);

            // Kurangi stok buku
            Books::where('book_id', $book_id)->decrement('stock');
        }

        return redirect()->route('rental.index')->with('success', 'Peminjaman berhasil dibuat!');
    }

    public function return($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->update([
            'returned_at' => now(),
            'rental_status' => '1', // Dikembalikan
        ]);

        // Tambah stok buku kembali
        foreach ($rental->rentalDetails as $detail) {
            Books::where('book_id', $detail->book_id)->increment('stock');
        }

        return redirect()->route('rental.index')->with('success', 'Buku berhasil dikembalikan!');
    }
}
