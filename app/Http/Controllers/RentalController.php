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
            ->orderBy('created_at', 'desc');

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
            'borrowed_at' => 'required|date',
        ]);
        $dueDate = date('Y-m-d 23:59:59', strtotime($request->borrowed_at . ' +7 days'));

        // Create Rental
        $rental = Rental::create([
            'user_id' => $request->user_id,
            'borrowed_at' => now(),
            'due_date' => $dueDate,
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
    public function destroy($id)
    {
        // Ambil data rental beserta detailnya
        $rental = Rental::with('rentalDetails.book')->findOrFail($id);

        if ($rental->rental_status == 1) {
            return redirect()->route('rental.index')->with('error', 'Tidak dapat menghapus peminjaman yang telah dikembalikan.');
        }

        // Update status_delete menjadi 1
        $rental->update(['status_delete' => '1']);

        // Tambahkan kembali stok buku yang dipinjam
        foreach ($rental->rentalDetails as $detail) {
            $book = $detail->book;
            if ($book) {
                $book->increment('stock'); // Tambah stok
            }
        }

        return redirect()->route('rental.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function showBorrowedBooks()
    {
        $user = auth()->user(); // Ambil pengguna yang sedang login
        $borrowedBooks = Rental::with('rentalDetails.book')
            ->where('user_id', $user->user_id)
            ->where('status_delete', '0')
            ->where('rental_status', '0') // Hanya yang status pinjam (belum dikembalikan)
            ->get();

        return response()->json($borrowedBooks);
    }
}
