<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | StoryGlass</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #c4e2e0;
            font-family: 'Poppins', sans-serif;
        }
        nav {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-link:hover {
            color: #84a9ac !important;
        }
        footer {
            background-color: #ffffff;
            text-align: center;
            padding: 20px;
            color: #333;
            margin-top: 60px;
            box-shadow: 0 -1px 8px rgba(0,0,0,0.1);
        }
        .card img {
            transition: transform 0.3s ease;
        }
        .card:hover img {
            transform: scale(1.05);
        }
        .bi-cart3 {
            color: #2a3a3a;
        }
        .bi-cart3:hover {
            color: #84a9ac;
        }
        form.d-flex input {
            border-radius: 20px;
            padding-left: 15px;
        }
        form.d-flex button {
            border-radius: 20px;
            background-color: #84a9ac;
            color: white;
            border: none;
            transition: 0.3s;
        }
        form.d-flex button:hover {
            background-color: #558b8b;
        }
        .logo {
            width: 100px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">StoryGlass</a>
            <form id="searchForm" action="{{ route('produk.index') }}" method="GET" class="d-flex w-50 mx-3" onsubmit="return validateSearch()">
                <input id="searchInput" type="text" name="search" class="form-control me-2" placeholder="Cari produk..." value="{{ request('search') }}">
                <button type="submit" class="btn">Cari</button>
            </form>

            <script>
            function validateSearch() {
                const input = document.getElementById('searchInput').value.trim();
                if (!input) {
                    alert('Masukkan nama produk yang ingin dicari terlebih dahulu.');
                    return false;
                }
                return true;
            }
            </script>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('produk.index') }}">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('tentang') }}">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('chatbot') }}">Chatbot</a></li>

                @guest
                    @if (Route::has('register'))
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest

                <li class="nav-item ms-3">
                    <a href="{{ route('cart.index') }}" class="position-relative">
                        <i class="bi bi-cart3 fs-4"></i>
                        @php
                            $cartCount = session()->has('cart') ? count(session('cart')) : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $cartCount }}
                            </span>
                        @else
                            <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="display: none;">0</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer>
        <p>© 2025 StoryGlass. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.add-to-cart-form');
            const productId = form.getAttribute('data-id');
            const csrfToken = form.querySelector('input[name="_token"]').value;

            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('cart-count');
                    badge.textContent = data.cartCount;
                    
                    if (data.cartCount > 0) {
                        badge.style.display = 'inline-block';
                    }

                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

</html>
