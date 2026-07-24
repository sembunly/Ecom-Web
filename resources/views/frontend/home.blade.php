@extends('layouts.frontend')

@section('title', 'Store Electronics')
@section('custom_storefront', true)
@section('main_class', 'storefront-main min-vh-70')

@section('content')
  <section class="store-promo">
    <p class="mb-0">
      Discover the latest electronics, selected for work, play, and everything in between.
      <a href="{{ route('products.index') }}">Shop now <i class="bi bi-arrow-right-short"></i></a>
    </p>
  </section>

  <div class="storefront-container">
    <section class="store-intro">
      <h1><span>Store.</span> The best way to buy the products you love.</h1>

      <div class="store-intro__help">
        <div class="store-intro__help-icon"><i class="bi bi-chat-dots-fill"></i></div>
        <div>
          <strong>Need shopping help?</strong>
          <a href="#store-footer">Connect with us <i class="bi bi-arrow-up-right"></i></a>
        </div>
      </div>
    </section>

    <section class="store-categories" aria-labelledby="category-title">
      <div class="store-section-heading">
        <h2 id="category-title"><span>Categories.</span> Find what you’re looking for.</h2>
        <a href="{{ route('categories.index') }}">View all <i class="bi bi-chevron-right"></i></a>
      </div>

      <div class="store-category-row">
        @forelse($categories as $category)
          <a class="store-category" href="{{ route('category.products', $category->id) }}">
            <span class="store-category__image">
              @if($category->image)
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
              @else
                <i class="bi bi-laptop" aria-hidden="true"></i>
              @endif
            </span>
            <span class="store-category__name">{{ $category->name }}</span>
          </a>
        @empty
          <div class="store-empty">Categories will appear here when they are added.</div>
        @endforelse
      </div>
    </section>

    <section class="store-products" aria-labelledby="latest-title">
      <div class="store-section-heading store-section-heading--products">
        <h2 id="latest-title"><span>The latest.</span> Take a look at what’s new right now.</h2>
      </div>

      @if(request('q'))
        <div class="store-search-result">
          Results for “{{ request('q') }}”
          <a href="{{ route('home') }}">Clear search</a>
        </div>
      @endif

      <div class="row g-4">
        @forelse($products as $product)
          <div class="col-6 col-lg-3">
            <article class="store-product-card">
              <a class="store-product-card__link" href="{{ route('products.show', $product->id) }}"
                aria-label="View {{ $product->name }}"></a>

              <div class="store-product-card__media">
                @if($product->image)
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                @else
                  <i class="bi bi-laptop" aria-hidden="true"></i>
                @endif
              </div>

              <div class="store-product-card__content">
                <div class="store-product-card__eyebrow">
                  {{ $loop->first && $products->currentPage() === 1 ? 'NEW' : ($product->category?->name ?? 'FEATURED') }}
                </div>
                <h3>{{ $product->name }}</h3>
                <p class="store-product-card__availability {{ $product->stock > 0 ? 'is-available' : 'is-unavailable' }}">
                  {{ $product->stock > 0 ? 'In stock • Fast delivery' : 'Out of stock' }}
                </p>

                <dl class="store-product-card__specs">
                  @if($product->brand)
                    <div><dt>Brand:</dt><dd>{{ $product->brand }}</dd></div>
                  @endif
                  @if($product->model)
                    <div><dt>Model:</dt><dd>{{ $product->model }}</dd></div>
                  @endif
                  @if($product->ram)
                    <div><dt>RAM:</dt><dd>{{ $product->ram }}</dd></div>
                  @endif
                  @if($product->storage)
                    <div><dt>Storage:</dt><dd>{{ $product->storage }}</dd></div>
                  @endif
                  @if($product->processor)
                    <div><dt>Processor:</dt><dd>{{ $product->processor }}</dd></div>
                  @endif
                  @if($product->screen_size)
                    <div><dt>Screen:</dt><dd>{{ $product->screen_size }}</dd></div>
                  @endif
                </dl>

                <div class="store-product-card__price">${{ number_format($product->price, 2) }}</div>
              </div>

              <button type="button" class="store-product-card__add js-add-to-cart"
                data-url="{{ route('cart.add', $product->id) }}"
                data-name="{{ $product->name }}"
                aria-label="Add {{ $product->name }} to cart"
                @disabled($product->stock < 1)>
                <i class="bi {{ $product->stock > 0 ? 'bi-plus-lg' : 'bi-x-lg' }}"></i>
              </button>
            </article>
          </div>
        @empty
          <div class="col-12">
            <div class="store-empty store-empty--products">
              <h3>No products found.</h3>
              <p>Try another search or browse all categories.</p>
              <a href="{{ route('home') }}">View all products</a>
            </div>
          </div>
        @endforelse
      </div>

      @if($products->hasPages())
        <div class="store-pagination">
          {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </section>
  </div>
@endsection

@push('scripts')
  <script>
    document.querySelectorAll('.js-add-to-cart').forEach((button) => {
      button.addEventListener('click', async () => {
        const oldHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        try {
          const response = await fetch(button.dataset.url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ qty: 1 })
          });
          const data = await response.json().catch(() => ({}));

          if (!response.ok) {
            throw new Error(data.message || 'Cannot add this product.');
          }

          showCartToast(data.message || `${button.dataset.name} added to cart!`);
          if (data.cart_count !== undefined) updateCartBadge(data.cart_count);
        } catch (error) {
          showCartToast(error.message || 'Cannot add to cart. Please try again.');
        } finally {
          button.disabled = false;
          button.innerHTML = oldHtml;
        }
      });
    });
  </script>
@endpush
