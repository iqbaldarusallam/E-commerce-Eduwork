@extends('layouts.userNavbar')

@section('content')
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">

        {{-- Breadcrumb --}}
        <div class="text-sm text-gray-500 mb-10">
            Home / <span class="text-black">Contact</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Info --}}
            <div class="bg-white p-8 rounded shadow-sm space-y-8">
                <div>
                    <h3 class="font-semibold text-lg mb-2">Call To Us</h3>
                    <p class="text-sm text-gray-500">We are available 24/7, 7 days a week.</p>
                    <p class="mt-2 text-sm">Phone: +8801611112222</p>
                </div>

                <hr>

                <div>
                    <h3 class="font-semibold text-lg mb-2">Write To Us</h3>
                    <p class="text-sm text-gray-500">
                        Fill out our form and we will contact you within 24 hours.
                    </p>
                    <p class="mt-2 text-sm">Emails: customer@exclusive.com</p>
                    <p class="text-sm">Emails: support@exclusive.com</p>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2 bg-white p-8 rounded shadow-sm">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" placeholder="Your Name *" class="border p-3 rounded focus:outline-red-500">
                        <input type="email" placeholder="Your Email *" class="border p-3 rounded focus:outline-red-500">
                        <input type="text" placeholder="Your Phone *" class="border p-3 rounded focus:outline-red-500">
                    </div>

                    <textarea rows="6" placeholder="Your Message" class="border p-3 rounded w-full focus:outline-red-500"></textarea>

                    <div class="text-right">
                        <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded hover:bg-red-600 transition-colors">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Google Maps Section (Added) --}}
        <div class="mt-12 bg-white p-4 rounded shadow-sm">
            <div class="w-full h-[450px] overflow-hidden rounded">
                <iframe 
                    src="https://www.google.com/maps?q=-7.824052,110.363782&z=17&output=embed" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        {{-- End Google Maps Section --}}

    </div>
</div>
@endsection