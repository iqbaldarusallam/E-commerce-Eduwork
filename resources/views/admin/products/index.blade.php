@extends('layouts.adminNavbar')

@section('content')
<div class="p-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Products</h1>
        <div class="flex items-center gap-3">
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Export</a>
            <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium flex items-center gap-2">
                <i class="bi bi-plus-lg"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Filter and Search Bar -->
    <div class="bg-white rounded-lg shadow mb-6 p-4 flex items-center gap-4">
        <div class="flex-1 flex items-center gap-4">
            <div class="relative">
                <select id="filterStatus" class="px-8 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Filter</option>
                    <option value="instock">In Stock</option>
                    <option value="outofstock">Out of Stock</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Search..." value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Category</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Price</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Stock</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Weight</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                @if($product->images && $product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->url) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover">
                                @else
                                    <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                                        <i class="bi bi-image text-gray-400"></i>
                                    </div>
                                @endif
                                <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $product->category?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($product->price) }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($product->stock > 0)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    {{ $product->stock }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $product->weight ? $product->weight . ' g' : '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 {{ $product->active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} rounded-full text-xs font-semibold">
                                {{ $product->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm flex items-center gap-1">
                            <a href="{{ route('admin.products.edit', $product) }}" title="Edit" class="p-1 hover:bg-gray-100 rounded-lg text-gray-500 transition">
                                <i class="bi bi-pencil-square text-lg"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete" class="p-1 hover:bg-gray-100 rounded-lg text-gray-500 transition" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash-fill text-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-600">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    let searchValue = this.value;
    let filterValue = document.getElementById('filterStatus').value;

    let url = new URL(window.location);
    url.searchParams.set('search', searchValue);
    if (filterValue) {
        url.searchParams.set('filter', filterValue);
    } else {
        url.searchParams.delete('filter');
    }

    window.location.href = url.toString();
});

document.getElementById('filterStatus').addEventListener('change', function() {
    let filterValue = this.value;
    let searchValue = document.getElementById('searchInput').value;

    let url = new URL(window.location);
    if (filterValue) {
        url.searchParams.set('filter', filterValue);
    } else {
        url.searchParams.delete('filter');
    }
    if (searchValue) {
        url.searchParams.set('search', searchValue);
    } else {
        url.searchParams.delete('search');
    }

    window.location.href = url.toString();
});
</script>
@endsection
