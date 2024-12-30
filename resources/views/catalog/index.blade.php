@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Katalog Buku</h1>
    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">Daftar Buku</div>
        <div class="card-body">
            <!-- Button untuk Menambah Buku -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addBookModal">Tambah Buku</button>
            <form method="GET" action="{{ route('catalog.index') }}">
                <div class="row my-2">
                    <div class="col-md-6">

                        <div class="form-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari Buku"
                                value="{{ request()->search }}">
                        </div>
                    </div>
                    <div class="col-md-3 my-1">
                        <div class="form-group">
                            <select class="form-control" name="status">
                                <option value="">Semua Status</option>
                                <option value="available" {{ request()->status == 'available' ? 'selected' : '' }}>Tersedia
                                </option>
                                <option value="unavailable" {{ request()->status == 'unavailable' ? 'selected' : '' }}>Tidak
                                    Tersedia</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Stock</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
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
                                <td>
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editBookModal" data-bs-book-id="{{ $book->book_id }}"
                                        data-bs-title="{{ $book->title }}" data-bs-author="{{ $book->author }}"
                                        data-bs-publisher="{{ $book->publisher }}"
                                        data-bs-publication-year="{{ $book->publication_year }}"
                                        data-bs-isbn="{{ $book->isbn }}" data-bs-stock="{{ $book->stock }}">
                                        Edit
                                    </button>
                                    <!-- Delete Button -->
                                    <form action="{{ route('catalog.delete', $book->book_id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $books->links() }}
        </div>
    </div>

    <!-- Modal untuk Edit Buku -->
    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">Edit Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('catalog.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="book_id" name="book_id">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_author" class="form-label">Pengarang</label>
                            <input type="text" class="form-control" id="edit_author" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_publisher" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="edit_publisher" name="publisher" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_publication_year" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="edit_publication_year"
                                name="publication_year" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="edit_isbn" name="isbn" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_stock" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" required
                                min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Buku</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Menambah Buku -->
    <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookModalLabel">Tambah Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('catalog.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="add_title" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="add_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_author" class="form-label">Pengarang</label>
                            <input type="text" class="form-control" id="add_author" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_publisher" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="add_publisher" name="publisher" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_publication_year" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="add_publication_year" name="publication_year"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="add_isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="add_isbn" name="isbn" required>
                        </div>
                        <div class="mb-3">
                            <label for="add_stock" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="add_stock" name="stock" required
                                min="0">
                        </div>
                        <button type="submit" class="btn btn-success">Tambah Buku</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editBookModal')
            editModal.addEventListener('show.bs.modal', function(event) {
                // Button yang diklik
                var button = event.relatedTarget

                // Ambil data dari button
                var bookId = button.getAttribute('data-bs-book-id') // Perhatikan ini
                var title = button.getAttribute('data-bs-title')
                var author = button.getAttribute('data-bs-author')
                var publisher = button.getAttribute('data-bs-publisher')
                var isbn = button.getAttribute('data-bs-isbn')
                var publicationYear = button.getAttribute('data-bs-publication-year')
                var stock = button.getAttribute('data-bs-stock')

                // Set nilai ke form
                editModal.querySelector('#book_id').value = bookId // Perhatikan ini
                editModal.querySelector('#edit_title').value = title
                editModal.querySelector('#edit_author').value = author
                editModal.querySelector('#edit_publisher').value = publisher
                editModal.querySelector('#edit_isbn').value = isbn
                editModal.querySelector('#edit_publication_year').value = publicationYear
                editModal.querySelector('#edit_stock').value = stock

                console.log(isbn);
                console.log(stock);
            })
        })
    </script>
@endsection
