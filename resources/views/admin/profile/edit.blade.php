@extends('layouts.adminNavbar')

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-white shadow-lg rounded-xl p-10 border border-gray-200">
            <h2 class="text-xl font-semibold text-red-500 mb-8">
                Edit Admin Profile
            </h2>

            {{-- FORM --}}
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-10">
                @csrf
                @method('PATCH')

                {{-- NAME --}}
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name"
                           class="border border-gray-300 bg-gray-100 rounded-md px-3 py-2"
                           value="{{ old('name', auth()->user()->name) }}">
                    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- PHONE --}}
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone"
                           class="border border-gray-300 bg-gray-100 rounded-md px-3 py-2"
                           value="{{ old('phone', auth()->user()->phone) }}" placeholder="0812xxxxxxx">
                    @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- EMAIL --}}
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email"
                           class="border border-gray-300 bg-gray-100 rounded-md px-3 py-2"
                           value="{{ old('email', auth()->user()->email) }}">
                    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- PASSWORD SECTION --}}
                <div class="flex flex-col space-y-4 mt-6">
                    <p class="font-medium text-gray-700">Password Changes</p>

                    <input type="password" name="current_password"
                           class="border border-gray-300 bg-gray-100 rounded-md px-3 py-2"
                           placeholder="Current Password">

                    <input type="password" name="password"
                           class="border border-gray-300 bg-gray-100 rounded-md px-3 py-2"
                           placeholder="New Password">

                    <input type="password" name="password_confirmation"
                           class="border border-gray-300 bg-gray-100 rounded-md px-3 py-2"
                           placeholder="Confirm New Password">
                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-end items-center gap-6 pt-4">

                    <a href="{{ route('admin.dashboard') }}"
                       class="text-gray-600 font-medium hover:text-gray-800">
                        Cancel
                    </a>

                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-md">
                        Save Changes
                    </button>

                </div>

            </form>
        </div>

        {{-- DELETE ACCOUNT --}}
        <div class="p-6 bg-white shadow sm:rounded-lg mt-6">
            <div class="max-w-xl">
                @include('admin.profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection
