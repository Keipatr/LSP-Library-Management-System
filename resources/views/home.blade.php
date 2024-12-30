@extends('layouts.main')

@section('content')
    <div class="container mt-5 pt-5">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 font-weight-bold">Koleksi Buku Perpustakaan</h1>
            <p class="lead">Temukan buku yang Anda inginkan!</p>
        </div>

        <!-- Filter Pencarian -->
        <form method="GET" action="{{ route('collections.index') }}" class="mb-5">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul atau penulis"
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Tidak
                            Tersedia</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </div>
        </form>

        <!-- Tabel Koleksi Buku -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->publisher }}</td>
                            <td>{{ $book->publication_year }}</td>
                            <td>
                                <span class="badge {{ $book->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $book->stock > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada buku yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    </div>

    <!-- Modal Daftar Buku yang Dipinjam -->
    <div class="modal fade" id="borrowedBooksModal" tabindex="-1" aria-labelledby="borrowedBooksModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="borrowedBooksModalLabel">Buku yang Sedang Dipinjam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody id="borrowedBooksList">
                                <!-- Daftar buku yang dipinjam akan ditampilkan di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch buku yang sedang dipinjam menggunakan AJAX
        
    </script>
@endsection
