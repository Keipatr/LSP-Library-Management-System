<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar untuk mengambil buku
        $query = Books::where('status_delete', '0') // Ambil buku yang tidak dihapus
            ->orderBy('title', 'asc'); // Mengurutkan berdasarkan judul

        // Filter berdasarkan pencarian (judul atau penulis)
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('author', 'like', "%{$searchTerm}%");
            });
        }

        // Filter berdasarkan status stok buku
        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            if ($status == 'available') {
                $query->where('stock', '>', 0); // Buku tersedia (stok > 0)
            } elseif ($status == 'unavailable') {
                $query->where('stock', 0); // Buku tidak tersedia (stok = 0)
            }
        }

        // Mengambil data buku dengan pagination
        $books = $query->paginate(10);

        return view('catalog.index', compact('books'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|digits:4|integer|min:1000|max:' . date('Y'),
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'stock' => 'required|integer|min:0',
        ]);
        try {
            Books::create($validated);
            return redirect()->route('catalog.index')->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $book = Books::findOrFail($request->book_id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|digits:4|integer|min:1901|max:' . date('Y'),
            'isbn' => 'nullable|string|unique:books,isbn,' . $request->book_id . ',book_id', // Abaikan unik jika ISBN tidak berubah
            'stock' => 'required|integer|min:0',
        ]);
        try {
            $book->update($validated);
            return redirect()->route('catalog.index')->with('success', 'Buku berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui buku: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $book = Books::findOrFail($id);
        $book->update(['status_delete' => '1']);

        return redirect()->route('catalog.index')->with('success', 'Buku berhasil dihapus!');
    }

}
