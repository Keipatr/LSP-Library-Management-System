<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LSP. Library Management System.">
    <meta name="keywords" content="LSP, Library, Management, System, Library Management System">
    <meta name="author" content="Kei">
    <title>Library Management</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>`
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Perpustakaan</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('collections.index') }}">Koleksi Buku</a>
                    </li>
                    @if (Auth::check())
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="borrowedBooksLink">Buku yang Dipinjam</a>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>


    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const borrowedBooksLink = document.querySelector('#borrowedBooksLink');

            if (borrowedBooksLink) {
                borrowedBooksLink.addEventListener('click', function(event) {
                    event.preventDefault(); // Menghentikan default action link
                    fetch('{{ route('user.borrowed.books') }}')
                        .then(response => response.json())
                        .then(data => {
                            const tableBody = document.getElementById('borrowedBooksList');
                            tableBody.innerHTML = ''; // Kosongkan tabel sebelum menampilkan data
                            data.forEach((rental, index) => {
                                rental.rental_details.forEach(detail => {
                                    const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${detail.book.title}</td>
                                    <td>${detail.book.author}</td>
                                    <td>${rental.due_date}</td>
                                </tr>
                            `;
                                    tableBody.insertAdjacentHTML('beforeend', row);
                                });
                            });
                            // Tampilkan modal
                            new bootstrap.Modal(document.getElementById('borrowedBooksModal')).show();
                        })
                        .catch(error => console.error('Error fetching borrowed books:', error));
                });
            }
        });
    </script>
</body>

</html>
