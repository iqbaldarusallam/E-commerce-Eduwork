@extends('layouts.adminNavbar')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 font-semibold transition">Back</a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl border border-gray-100">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Admin Profile</h1>
        <p class="text-gray-600 text-sm mb-6">Update your profile information and password</p>

        {{-- FORM --}}
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Full Name</label>
                <input type="text" name="name"
                       class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }} rounded-lg focus:outline-none focus:ring-2 transition text-gray-900 placeholder-gray-400"
                       value="{{ old('name', auth()->user()->name) }}"
                       placeholder="Enter your full name">
                @error('name') <p class="text-red-600 text-sm mt-2 flex items-center"><span class="mr-1">⚠</span>{{ $message }}</p> @enderror
            </div>

            {{-- PHONE --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Phone</label>
                <input type="text" name="phone"
                       class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('phone') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }} rounded-lg focus:outline-none focus:ring-2 transition text-gray-900 placeholder-gray-400"
                       value="{{ old('phone', auth()->user()->phone) }}"
                       placeholder="0812xxxxxxx">
                @error('phone') <p class="text-red-600 text-sm mt-2 flex items-center"><span class="mr-1">⚠</span>{{ $message }}</p> @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Email</label>
                <input type="email" name="email"
                       class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }} rounded-lg focus:outline-none focus:ring-2 transition text-gray-900 placeholder-gray-400"
                       value="{{ old('email', auth()->user()->email) }}"
                       placeholder="Enter your email">
                @error('email') <p class="text-red-600 text-sm mt-2 flex items-center"><span class="mr-1">⚠</span>{{ $message }}</p> @enderror
            </div>

            {{-- PASSWORD SECTION --}}
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Change Password</h3>

                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Current Password</label>
                    <input type="password" name="current_password"
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('current_password') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }} rounded-lg focus:outline-none focus:ring-2 transition text-gray-900 placeholder-gray-400"
                           placeholder="Enter current password">
                    @error('current_password') <p class="text-red-600 text-sm mt-2 flex items-center"><span class="mr-1">⚠</span>{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-800 mb-2">New Password</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }} rounded-lg focus:outline-none focus:ring-2 transition text-gray-900 placeholder-gray-400"
                           placeholder="Enter new password">
                    @error('password') <p class="text-red-600 text-sm mt-2 flex items-center"><span class="mr-1">⚠</span>{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('password_confirmation') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }} rounded-lg focus:outline-none focus:ring-2 transition text-gray-900 placeholder-gray-400"
                           placeholder="Confirm new password">
                    @error('password_confirmation') <p class="text-red-600 text-sm mt-2 flex items-center"><span class="mr-1">⚠</span>{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition shadow-sm hover:shadow-md">
                    Save Changes
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition shadow-sm hover:shadow-md">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>
@endsection
