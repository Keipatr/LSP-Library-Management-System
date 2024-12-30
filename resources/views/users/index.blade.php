@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Manajemen User</h1>

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
        <div class="card-header bg-primary text-white">Daftar User</div>
        <div class="card-body">
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah User</button>

            <form method="GET" action="{{ route('users.index') }}">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari User"
                                value="{{ request()->search }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" name="role">
                                <option value="">Semua Role</option>
                                <option value="0" {{ request()->role === '0' ? 'selected' : '' }}>Staff</option>
                                <option value="1" {{ request()->role === '1' ? 'selected' : '' }}>Member</option>
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role == '0' ? 'Staff' : 'Member' }}</td>
                                <td>
                                    <span class="badge {{ $user->status_delete == 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->status_delete == 0 ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal" data-bs-user-id="{{ $user->user_id }}"
                                        data-bs-name="{{ $user->name }}" data-bs-email="{{ $user->email }}"
                                        data-bs-role="{{ $user->role }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('users.delete', $user->user_id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="0">Staff</option>
                                <option value="1">Member</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Tambah User</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_user_id" name="user_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password (Kosongkan jika tidak ingin
                                mengubah)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-control" id="edit_role" name="role" required>
                                <option value="0">Staff</option>
                                <option value="1">Member</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editUserModal')
            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget

                var userId = button.getAttribute('data-bs-user-id')
                var name = button.getAttribute('data-bs-name')
                var email = button.getAttribute('data-bs-email')
                var role = button.getAttribute('data-bs-role')

                editModal.querySelector('#edit_user_id').value = userId
                editModal.querySelector('#edit_name').value = name
                editModal.querySelector('#edit_email').value = email
                editModal.querySelector('#edit_role').value = role
            })
        })
    </script>
@endsection
