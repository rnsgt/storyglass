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
            background-color: #c4e2e0; /* warna yang kamu minta */
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
            <!-- 🔍 Form Pencarian -->
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
                <li class="nav-item"><a class="nav-link" href="#">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item ms-3">
                    @php 
                        use App\Models\Cart;
                        $cart = Cart::where('session_id', session()->getId())->with('items')->first();
                        $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                    @endphp

                    <a href="{{ route('cart.index') }}" class="position-relative">
                        <i class="bi bi-cart fs-4 text-dark"></i>
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
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
                    // Update badge angka di ikon keranjang
                    document.getElementById('cart-count').textContent = data.cartCount;

                    // Notifikasi kecil (opsional)
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>

</html>
