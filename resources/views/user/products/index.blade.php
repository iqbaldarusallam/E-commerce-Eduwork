@extends('layouts.userNavbar')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Header Title -->
        <h1 class="text-3xl font-bold text-gray-900 mb-10">Explore Our Products</h1>

        <!-- Search + Filter Bar (Figma Style) -->
        <form method="GET" action="{{ route('user.products.index') }}">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-10">

                <div class="flex flex-col md:flex-row md:items-center gap-4">

                    <!-- Search -->
                    <div class="flex-1 relative">
                        <input type="text"
                            name="search"
                            placeholder="Search products..."
                            value="{{ request('search') }}"
                            class="w-full px-5 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition">
                    </div>

                    <!-- Category -->
                    <select name="category"
                        class="px-5 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Sort -->
                    <select name="sort"
                        class="px-5 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition">
                        <option value="">Sort</option>
                        <option value="newest" {{ request('sort')=='newest' ? 'selected':'' }}>Newest</option>
                        <option value="low" {{ request('sort')=='low' ? 'selected':'' }}>Price: Low → High</option>
                        <option value="high" {{ request('sort')=='high' ? 'selected':'' }}>Price: High → Low</option>
                    </select>

                    <!-- Button -->
                    <button type="submit"
                        class="px-8 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition">
                        Apply
                    </button>
                </div>

            </div>
        </form>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-7">
            @forelse($products as $product)

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition p-4">
                    <!-- Wishlist Button -->
                    @auth
                    <form action="{{ route('user.wishlist.store') }}" method="POST"
                        class="absolute top-3 right-3 z-20">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <button type="submit"
                            class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-sm hover:bg-white transition">
                            <svg class="w-5 h-5 text-gray-700"
                                fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </button>
                    </form>
                    @endauth

                    <!-- Image -->
                    <a href="{{ route('user.products.show', $product->slug) }}">
                        <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden">
                            @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->url) }}"
                                     class="w-full h-full object-cover hover:scale-105 transition">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    No Image
                                </div>
                            @endif
                        </div>
                    </a>

                    <!-- Info -->
                    <div class="mt-4">
                        <a href="{{ route('user.products.show', $product->slug) }}"
                           class="text-lg font-semibold text-gray-900 line-clamp-2 hover:text-gray-700 transition">
                            {{ $product->name }}
                        </a>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $product->category?->name }}
                        </p>

                        <p class="text-xl font-bold text-gray-900 mt-3">
                            Rp {{ number_format($product->price) }}
                        </p>

                        <!-- Actions -->
                        <div class="mt-5">
                            <a href="{{ route('user.products.show', $product->slug) }}"
                                class="block w-full text-center py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition">
                                View Details
                            </a>

                            @auth
                                @if($product->stock > 0)
                                    <form action="{{ route('user.cart.store') }}" method="POST" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">

                                        <button type="submit"
                                            class="w-full py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition">
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <div class="mt-3 text-sm text-red-600 font-semibold">Out of stock</div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-4 text-center py-20">
                    <p class="text-gray-500">No products found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $products->links() }}
        </div>

    </div>
</div>
@endsection
