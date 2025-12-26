@extends('layouts.userNavbar')

{{-- JQUERY CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-8">

            {{-- HEADER --}}
            <div class="flex items-center justify-center mb-4 gap-3">
                <div class="flex items-center justify-center w-11 h-11 rounded-full border border-red-200 bg-red-50">
                    <i class="bi bi-pin-map-fill text-red-600 text-lg"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">My Address</h2>
            </div>

            <form action="{{ isset($address) ? route('user.addresses.update', $address) : route('user.addresses.store') }}" method="POST">
                @csrf
                @if(isset($address))
                    @method('PUT')
                @endif

                {{-- ROW 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Address Label</label>
                        <select name="label" class="w-full bg-gray-100 px-3 py-3 rounded border">
                            <option value="Home" {{ (isset($address) && $address->label === 'Home') || old('label') === 'Home' ? 'selected' : '' }}>Home</option>
                            <option value="Office" {{ (isset($address) && $address->label === 'Office') || old('label') === 'Office' ? 'selected' : '' }}>Office</option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Recipient Name</label>
                        <input type="text" name="recipient_name"
                            value="{{ $address->recipient_name ?? old('recipient_name') }}"
                            class="w-full bg-gray-100 px-3 py-3 rounded border">
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Number Phone</label>
                        <input type="text" name="phone"
                            value="{{ $address->phone ?? old('phone') }}"
                            class="w-full bg-gray-100 px-3 py-3 rounded border">
                    </div>
                </div>

                {{-- ROW 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-4">
                        <label for="province" class="block text-xs font-semibold text-gray-500 mb-2">Province</label>
                        <select id="province" name="province" class="w-full bg-gray-100 px-3 py-3 rounded border">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province['id'] }}"
                                    {{ (isset($address) && $address->province == $province['id']) || old('province') == $province['id'] ? 'selected' : '' }}>
                                    {{ $province['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label for="city" class="block text-xs font-semibold text-gray-500 mb-2">City</label>
                        <select id="city" name="city" class="w-full bg-gray-100 px-3 py-3 rounded border">
                            <option value="">-- Select City --</option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label for="district" class="block text-xs font-semibold text-gray-500 mb-2">
                            District (Kecamatan)
                        </label>
                        <select id="district" name="district_id" class="w-full bg-gray-100 px-3 py-3 rounded border">
    <option value="">-- Select District --</option>
</select>

<input type="hidden" name="district" id="district_name">

                    </div>
                </div>

                {{-- ROW 3 --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Subdistrict (Kelurahan)</label>
                        <input type="text" name="subdistrict"
                            value="{{ $address->subdistrict ?? old('subdistrict') }}"
                            class="w-full bg-gray-100 px-3 py-3 rounded border" placeholder="Kelurahan">
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Postal Code</label>
                        <input type="text" name="postal_code"
                            value="{{ $address->postal_code ?? old('postal_code') }}"
                            class="w-full bg-gray-100 px-3 py-3 rounded border">
                    </div>
                </div>

                {{-- ROW 4 --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-12">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Addresses</label>
                        <textarea name="address_text" rows="6"
                            class="w-full bg-gray-100 px-4 py-4 rounded border">{{ $address->address_text ?? old('address_text') }}</textarea>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_primary" value="1"
                            {{ (isset($address) && $address->is_primary) || old('is_primary') ? 'checked' : '' }}>
                        <span>Set Primary address</span>
                    </label>

                    <button type="submit"
                        class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        {{ isset($address) ? 'Update Address' : 'Add Address' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

{{-- AJAX JQUERY --}}
@push('scripts')
<script>
$(document).ready(function () {

    function loadCities(provinceId, selectedCity = null) {
        $('#city').html('<option>Loading...</option>');
        $('#district').html('<option value="">-- Select District --</option>');
        $('#district_name').val('');

        if (!provinceId) return;

        $.get(`/user/address/cities/${provinceId}`, function (data) {
            $('#city').html('<option value="">-- Select City --</option>');

            $.each(data, function (i, city) {
                $('#city').append(`
                    <option value="${city.id}" ${city.id == selectedCity ? 'selected' : ''}>
                        ${city.name}
                    </option>
                `);
            });
        });
    }

    function loadDistricts(cityId, selectedDistrictId = null) {
        $('#district').html('<option>Loading...</option>');
        $('#district_name').val('');

        if (!cityId) return;

        $.get(`/user/address/districts/${cityId}`, function (data) {
            $('#district').html('<option value="">-- Select District --</option>');

            $.each(data, function (i, district) {
                $('#district').append(`
                    <option value="${district.id}"
                            data-name="${district.name}"
                            ${district.id == selectedDistrictId ? 'selected' : ''}>
                        ${district.name}
                    </option>
                `);
            });

            // MODE EDIT
            if (selectedDistrictId) {
                let selected = $('#district option:selected');
                $('#district_name').val(selected.data('name'));
            }
        });
    }

    // Province change
    $('#province').on('change', function () {
        loadCities($(this).val());
    });

    // City change
    $('#city').on('change', function () {
        loadDistricts($(this).val());
    });

    // District change
    $('#district').on('change', function () {
        let districtName = $(this).find(':selected').data('name');
        $('#district_name').val(districtName);
    });

    // INITIAL LOAD FOR EDIT MODE
    @if(isset($address))
        loadCities("{{ $address->province }}", "{{ $address->city }}");
        loadDistricts("{{ $address->city }}", "{{ $address->district_id }}");
        $('#district_name').val("{{ $address->district }}");
    @endif

});
</script>
@endpush

