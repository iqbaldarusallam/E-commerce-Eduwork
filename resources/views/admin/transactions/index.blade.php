@extends('layouts.adminNavbar')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Transactions</h1>
        <div class="flex items-center gap-3">
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Export</a>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow mb-6 p-4 flex items-center gap-4">
        <div class="flex-1 flex items-center gap-4">
            <div>
                <select id="filterStatus" class="px-9 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                <input id="searchInput" type="text" placeholder="Search by customer or order #..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900">Order #</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">#{{ $transaction->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $transaction->user->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->total_amount + $transaction->shipping_fee) }}</td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $s = $transaction->status;
                                $statusClass = match($s) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-blue-100 text-blue-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">{{ ucfirst($s) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $transaction->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm flex items-center gap-1">
                            <a href="{{ route('admin.transactions.show', $transaction) }}" title="View" class="p-1 hover:bg-gray-100 rounded-lg text-gray-500 transition">
                                <i class="bi bi-eye-fill text-lg"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-600">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    let searchValue = this.value;
    let filterValue = document.getElementById('filterStatus').value;
    let url = new URL(window.location);
    if (searchValue) url.searchParams.set('search', searchValue); else url.searchParams.delete('search');
    if (filterValue) url.searchParams.set('status', filterValue); else url.searchParams.delete('status');
    window.location.href = url.toString();
});

document.getElementById('filterStatus').addEventListener('change', function() {
    let filterValue = this.value;
    let searchValue = document.getElementById('searchInput').value;
    let url = new URL(window.location);
    if (filterValue) url.searchParams.set('status', filterValue); else url.searchParams.delete('status');
    if (searchValue) url.searchParams.set('search', searchValue); else url.searchParams.delete('search');
    window.location.href = url.toString();
});
</script>
@endsection
