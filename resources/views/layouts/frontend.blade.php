<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Store Electronics')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  {{-- Custom styles --}}
  <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

  @stack('styles')
</head>
<body class="bg-light">

  {{-- Apple-inspired global navigation --}}
  @php
    $cart = session()->get('cart', []);
    $cartCount = collect($cart)->sum('qty');
  @endphp
  <nav class="store-nav navbar navbar-expand-lg sticky-top" aria-label="Main navigation">
    <div class="store-nav__inner container-fluid">
      <a class="store-nav__brand" href="{{ route('home') }}" aria-label="Store home">
        <i class="bi bi-laptop" aria-hidden="true"></i>
      </a>

      <div class="store-nav__mobile-actions d-flex d-lg-none align-items-center">
        <a class="store-nav__icon position-relative" href="{{ route('cart.index') }}" aria-label="Shopping bag">
          <i class="bi bi-bag" aria-hidden="true"></i>
          <span id="cartBadgeMobile" class="store-nav__badge {{ $cartCount > 0 ? '' : 'd-none' }}">{{ $cartCount }}</span>
        </a>
        <button class="store-nav__toggle navbar-toggler" type="button" data-bs-toggle="collapse"
          data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
          <span></span><span></span>
        </button>
      </div>

      <div class="collapse navbar-collapse" id="topNav">
        <ul class="store-nav__links navbar-nav">
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Store</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('categories.*', 'category.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}#featured-products">New Arrivals</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#store-footer">Support</a>
          </li>
        </ul>

        <div class="store-nav__actions d-flex align-items-center">
          @auth
            <div class="dropdown">
              <button class="store-nav__icon" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                aria-label="Open account menu">
                @if(auth()->user()->avatar)
                  <img src="{{ auth()->user()->avatar }}" alt="" class="store-nav__avatar">
                @else
                  <i class="bi bi-person" aria-hidden="true"></i>
                @endif
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li class="px-3 pt-2 pb-1 small text-muted">Hello, {{ auth()->user()->name }}</li>
                <li><a class="dropdown-item py-2" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ url('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="dropdown-item py-2"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a class="store-nav__icon" href="{{ url('login') }}" aria-label="Log in">
              <i class="bi bi-person" aria-hidden="true"></i>
            </a>
          @endauth

          <a class="store-nav__icon position-relative d-none d-lg-flex" href="{{ route('cart.index') }}" aria-label="Shopping bag">
            <i class="bi bi-bag" aria-hidden="true"></i>
            <span id="cartBadge" class="store-nav__badge {{ $cartCount > 0 ? '' : 'd-none' }}">{{ $cartCount }}</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  @unless(View::hasSection('custom_storefront'))
    {{-- HEADER / HERO --}}
    <header class="container-fluid px-4 mt-3">
      <div class="card border-0 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
        <div class="card-body py-4 px-4">
          <div class="row align-items-center g-3">
            <div class="col-md-7">
              <h1 class="mb-2 fw-bold text-white">@yield('hero_title','Welcome to Store Electronics')</h1>
              <p class="mb-0 text-white-50">@yield('hero_subtitle','Discover the latest laptops and accessories at great prices')</p>
            </div>
            <div class="col-md-5 text-md-end">
              @yield('hero_action')
            </div>
          </div>
        </div>
      </div>
    </header>

    {{-- BREADCRUMB --}}
    <section class="container-fluid px-4 mt-2">
      @yield('breadcrumb')
    </section>
  @endunless

  {{-- CONTENT --}}
  <main id="featured-products" class="@yield('main_class', 'container-fluid px-4 mt-3 min-vh-70')">
    @if(session('success'))
      <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 shadow-sm border-0" style="background: #d1fae5; color: #065f46;">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @yield('content')
  </main>

  {{-- FOOTER --}}
  <footer id="store-footer" class="mt-5 py-4" style="background: #1f2937;">
    <div class="container-fluid px-4">
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="mb-2 fw-bold fs-5" style="color: #4f46e5;">
            <i class="bi bi-laptop me-2"></i>Store Electronics
          </div>
          <div class="small" style="color: #9ca3af;">
            Your trusted source for laptops and electronics in Cambodia.
          </div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
          <div class="mb-2 fw-semibold" style="color: #f3f4f6;">Quick Links</div>
          <div class="d-grid gap-2 small">
            <a href="{{ route('categories.index') }}" class="text-decoration-none" style="color: #9ca3af;">Categories</a>
            <a href="#" class="text-decoration-none" style="color: #9ca3af;">New Arrivals</a>
            <a href="#" class="text-decoration-none" style="color: #9ca3af;">Contact</a>
          </div>
        </div>
        <div class="col-6 col-md-3 col-lg-3">
          <div class="mb-2 fw-semibold" style="color: #f3f4f6;">Contact Us</div>
          <div class="small" style="color: #9ca3af;">
            <div class="mb-1">
              <i class="bi bi-geo-alt me-1"></i>BELTEI IU Campus 1, Tuol Sleng
            </div>
            <div class="mb-1"><i class="bi bi-telephone me-1"></i>+855 10 800 921</div>
            <div><i class="bi bi-envelope me-1"></i>sembunly2005@gmail.com</div>
          </div>
        </div>
        <div class="col-12 col-lg-4">
          <div class="mb-2 fw-semibold" style="color: #f3f4f6;">Find Us</div>
          <iframe
            class="store-footer__map"
            src="https://maps.google.com/maps?q=BELTEI%20International%20University%20Campus%201%20Tuol%20Sleng%2C%2021%20Street%20360%2C%20Phnom%20Penh&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=&amp;output=embed"
            title="BELTEI International University Campus 1, Tuol Sleng"
            loading="lazy"
            allowfullscreen
            referrerpolicy="strict-origin-when-cross-origin">
          </iframe>
        </div>
      </div>
      <hr class="my-4" style="border-color: #374151;">
      <div class="d-flex justify-content-between small" style="color: #6b7280;">
        <span>© {{ date('Y') }} Store Electronics</span>
        <span>Store Laptop</span>
      </div>
    </div>
  </footer>

  {{-- TOAST --}}
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="cartToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: #4f46e5; border-radius: 12px;">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="bi bi-check-circle-fill"></i>
          <span id="toastMessage">Added to cart!</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Toast helper --}}
  <script>
    function showCartToast(message = "Added to cart!") {
      document.getElementById('toastMessage').textContent = message;
      const el = document.getElementById('cartToast');
      const toast = new bootstrap.Toast(el, { delay: 2000 });
      toast.show();
    }

    // Update cart badge in navbar
    function updateCartBadge(count) {
      const badges = [
        document.getElementById('cartBadge'),
        document.getElementById('cartBadgeMobile')
      ];
      badges.forEach((badge) => {
        if (!badge) return;
        if (count > 0) {
          badge.textContent = count;
          badge.classList.remove('d-none');
        } else {
          badge.classList.add('d-none');
        }
      });
    }

    @if(session('cart_toast'))
      document.addEventListener('DOMContentLoaded', () => {
        showCartToast(@json(session('cart_toast')));
      });
    @endif
  </script>

  @stack('scripts')
</body>
</html>
