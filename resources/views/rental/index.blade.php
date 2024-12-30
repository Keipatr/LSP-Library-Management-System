@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Daftar Peminjaman</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">Transaksi Peminjaman</div>
        <div class="card-body">
            <!-- Button untuk Peminjaman Baru -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addRentalModal">Tambah
                Peminjaman</button>

            <form method="GET" action="{{ route('rental.index') }}">
                <div class="row my-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari Anggota atau Buku"
                                value="{{ request()->search }}">
                        </div>
                    </div>
                    <div class="col-md-2 my-1">
                        <div class="form-group">
                            <select class="form-control" name="status">
                                <option value="semua" {{ request()->status == 'semua' ? 'selected' : '' }}>Semua Status
                                </option>
                                <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Dikembalikan
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Peminjam</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Waktu</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rentals as $rental)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $rental->user->name }}</td>
                                <td>
                                    <ul>
                                        @foreach ($rental->rentalDetails as $detail)
                                            <li>{{ $detail->book->title }}</li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td>{{ $rental->borrowed_at }}</td>
                                <td>{{ $rental->due_date }}</td>
                                <td>{{ $rental->returned_at }}</td>
                                <td>
                                    <span
                                        class="badge
                                    {{ $rental->rental_status == '1' ? 'bg-success' : ($rental->due_date < now() ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $rental->rental_status == '1' ? 'Dikembalikan' : ($rental->due_date < now() ? 'Terlambat' : 'Dipinjam') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($rental->rental_status == '0')
                                        <form action="{{ route('rental.return', $rental->rental_id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary"
                                                onclick="return confirm('Yakin ingin mengembalikan buku?')">Kembalikan</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('rental.destroy', $rental->rental_id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $rentals->links() }}
        </div>
    </div>
    <!-- Modal Tambah Peminjaman -->
    <div class="modal fade" id="addRentalModal" tabindex="-1" aria-labelledby="addRentalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('rental.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRentalModalLabel">Tambah Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pilih Anggota</label>
                            <select id="user_id" name="user_id" class="form-control">
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="book_ids" class="form-label">Pilih Buku</label>
                            <select id="book_ids" name="book_ids[]" class="form-control" multiple>
                                @foreach ($books as $book)
                                    <option value="{{ $book->book_id }}">{{ $book->title }} - Stok: {{ $book->stock }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Input Borrowed At -->
                        <div class="mb-3">
                            <label for="borrowed_at" class="form-label">Waktu Peminjaman</label>
                            <input type="datetime-local" class="form-control" id="borrowed_at" name="borrowed_at" required>
                        </div>

                        <!-- Tampilkan Due Date -->
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Tanggal Harus Kembali</label>
                            <input type="text" class="form-control" id="due_date" name="due_date_display" readonly>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('borrowed_at').addEventListener('input', function() {
            const borrowedAt = new Date(this.value);
            if (isNaN(borrowedAt)) return;

            // Hitung 7 hari ke depan
            const dueDate = new Date(borrowedAt);
            dueDate.setDate(dueDate.getDate() + 7);
            dueDate.setHours(23, 59, 59, 999); // Set waktu ke 23:59:59

            // Format tanggal ke string sesuai kebutuhan
            const formattedDueDate = dueDate.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            }) + " 23:59";

            // Tampilkan nilai due_date di input readonly
            document.getElementById('due_date').value = formattedDueDate;
        });
    </script>
@endsection
