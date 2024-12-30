<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Books::where('status_delete', '0')->orderBy('title', 'asc');

        // Filter pencarian (judul atau penulis)
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('author', 'like', "%{$searchTerm}%");
            });
        }

        // Filter status stok
        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            if ($status == 'available') {
                $query->where('stock', '>', 0);
            } elseif ($status == 'unavailable') {
                $query->where('stock', 0);
            }
        }

        $books = $query->paginate(10);

        return view('home', compact('books'));
    }
}
