<div class="flex flex-col items-baseline justify-between border-b-2 border-dashed pt-10 pb-6 sm:flex-row no-print">
    <div>
        <h1 class="text-4xl font-bold tracking-tight text-gray-900 flex items-center">
            <img src="{{ asset('assets/img/logo-black.png') }}" alt="" srcset="" width="100">
        </h1>
        <p class="mt-2 text-base text-gray-500">Temukan koleksi produk lunaray pilihan terbaru kami.</p>
    </div>

    <div class="mt-4 items-center sm:mt-0 flex space-x-4">
        {{-- 🔍 Search --}}
        <div class="relative">
            <form class="lg:w-80 w-64 border border-dashed border-2 rounded-full" id="search-form"
                action="{{ route('home') }}" method="GET">
                @foreach (request()->except('search') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <i class="fa fa-search text-gray-500"></i>
                    </div>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                        id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 rounded-full" />
                    <button type="submit"
                        class="text-white absolute end-2.5 bottom-2.5 bg-indigo-700 hover:bg-blue-800 font-medium rounded-full text-sm px-4 py-2">Search</button>
                </div>
            </form>
        </div>

        <div class="relative">
            <a href="/cart" type="button" id="cart-btn"
                class="text-gray-600 hover:text-indigo-600 text-2xl relative">
                <i class="fa-solid fa-cart-shopping"></i>
                <span id="cart-count"
                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5">0</span>
            </a>
        </div>

        <button type="button" command="show-modal" commandfor="mobile-filters"
            class=" lg:hidden text-gray-600 hover:text-indigo-600 text-2xl relative">
            <span class="sr-only">Filter</span>
            <i class="fa-solid fa-filter"></i>
        </button>
    </div>
</div>
