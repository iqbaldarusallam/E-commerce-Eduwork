@extends('layouts.adminNavbar')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Images Carousel -->
                <div>
                    <div class="relative aspect-square bg-gray-200 rounded-lg overflow-hidden mb-4 group">
                        <img id="carouselImage"
                            src="{{ asset('storage/' . ($product->images->first()->url ?? '')) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover">

                       

                    </div>

                    @if($product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $idx => $image)
                                <img src="{{ asset('storage/' . $image->url) }}"
                                    alt="{{ $image->alt_text }}"
                                    class="w-full aspect-square object-cover rounded cursor-pointer hover:opacity-75 thumbnail-img"
                                    data-index="{{ $idx }}"
                                    onclick="goToImage({{ $idx }})">
                            @endforeach
                        </div>
                    @endif
                </div>

                <script>
                    let currentImageIndex = 0;
                    const totalImages = {{ $product->images->count() }};

                    function goToImage(index) {
                        currentImageIndex = index;
                        updateImage();
                    }

                    function updateImage() {
                        const images = document.querySelectorAll('.thumbnail-img');
                        const img = images[currentImageIndex];
                        if (img) {
                            document.getElementById('carouselImage').src = img.src;
                        }
                    }

                    // Initialize display if thumbnails exist
                    if (totalImages > 1) {
                        updateImage();
                    }
                </script>

                <!-- Details -->
                <div>
                    <p class="text-sm text-gray-600 mb-2">
                        <span class="font-medium">Category:</span>
                        {{ $product->category?->name ?? 'N/A' }}
                    </p>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                    <p class="text-sm text-gray-600 mb-2">
                        <span class="font-medium">SKU:</span> {{ $product->sku ?? 'N/A' }}
                    </p>

                    <p class="text-sm text-gray-600 mb-2">
                        <span class="font-medium">Weight:</span>
                        {{ $product->weight ? $product->weight . ' g (' . number_format($product->weight / 1000, 2) . ' kg)' : 'N/A' }}
                    </p>

                    <p class="text-3xl font-bold text-blue-600 mb-6">Rp {{ number_format($product->price) }}</p>

                    <p class="text-lg font-semibold text-gray-700 mb-2">Stock:
                        <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock }}
                        </span>
                    </p>

                    <p class="text-sm text-gray-600 mb-4">
                        <span class="font-medium">Status:</span>
                        <span class="{{ $product->active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>

                    @if($product->short_description)
                        <p class="text-gray-700 mb-6">{{ $product->short_description }}</p>
                    @endif

                    @if($product->description)
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('admin.products.edit', $product) }}" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
                    Edit
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded hover:bg-red-700">
                        Delete
                    </button>
                </form>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 bg-gray-600 text-white font-semibold rounded hover:bg-gray-700">
                    Back
                </a>
            </div>
        </div>
    </div>
@endsection
