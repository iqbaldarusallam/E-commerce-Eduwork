@extends('layouts.userNavbar')

@section('content')
    <section>
        <div class="bg-gray-50 lg:py-8">
            <div class="max-w-7xl mx-auto border px-4">

                {{-- Breadcrumb --}}
                <div class="text-sm text-gray-500 mb-10 py-2">
                    Home / <span class="text-black">About Us</span>
                </div>
                <div class="flex flex-col lg:flex-row-reverse gap-10 lg:items-center lg:justify-between">
                    <img src="{{ asset('img/side-about.png') }}" alt="about image">
                    <article class="mt-5">
                        <h1 class="text-3xl font-bold">Our Story</h1>
                        <p class="mt-5 font-light">
                            Launced in 2015, Exclusive is South Asia’s premier online shopping makterplace with an active
                            presense in Bangladesh. Supported by wide range of tailored marketing, data and service
                            solutions, Exclusive has 10,500 sallers and 300 brands and serves 3 millioons customers across
                            the region.
                        </p>
                        <p class="mt-5 font-light">
                            Exclusive has more than 1 Million products to offer, growing at a very fast. Exclusive offers a
                            diverse assotment in categories ranging from consumer.
                        </p>
                    </article>
                </div>

                <div class="flex flex-col lg:flex-row mt-5 lg:mt-40 gap-10 lg:items-center lg:justify-between">
                    <div class="flex w-full flex-col justify-center items-center shadow-lg rounded-lg pb-4 hover:bg-red-700  hover:text-white transition-colors">
                        <div class="border-white bg-black rounded-full w-20 h-20 lg:w-40  lg:h-40 mt-10 flex items-center justify-center mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 lg:w-16 lg:h-16" viewBox="0 0 24 24" fill="none"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-store-icon lucide-store">
                                <path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5" />
                                <path
                                    d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244" />
                                <path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-center mt-4">10.5 K</h2>
                        <p class="text-center">seller active</p>
                    </div>

                     <div class="flex w-full flex-col justify-center items-center shadow-lg rounded-lg pb-4 hover:bg-red-700  hover:text-white transition-colors">
                        <div class="border-white bg-black rounded-full w-20 h-20 lg:w-40  lg:h-40 mt-10 flex items-center justify-center mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 lg:w-16 lg:h-16" viewBox="0 0 24 24" fill="none"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-store-icon lucide-store">
                                <path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5" />
                                <path
                                    d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244" />
                                <path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-center mt-4">10.5 K</h2>
                        <p class="text-center">seller active</p>
                    </div>

                     <div class="flex w-full flex-col justify-center items-center shadow-lg rounded-lg pb-4 hover:bg-red-700  hover:text-white transition-colors">
                        <div class="border-white bg-black rounded-full w-20 h-20 lg:w-40  lg:h-40 mt-10 flex items-center justify-center mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 lg:w-16 lg:h-16" viewBox="0 0 24 24" fill="none"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-store-icon lucide-store">
                                <path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5" />
                                <path
                                    d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244" />
                                <path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-center mt-4">10.5 K</h2>
                        <p class="text-center">seller active</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
