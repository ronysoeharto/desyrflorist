@props(['product'])

<div class="group flex flex-col bg-white rounded-xl border border-[#EADFD4] overflow-hidden hover:shadow-md transition duration-300">
    <!-- Image & Badge Container -->
    <div class="relative aspect-square overflow-hidden bg-[#F3ECE4]">
        <a href="{{ route('shop.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->primaryImage?->image_path ?? 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=600&q=80' }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-500">
        </a>

        <!-- Badges -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10">
            @if($product->is_best_seller)
                <span class="bg-[#B85D66] text-white text-[9px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full shadow-xs">
                    Best Seller
                </span>
            @endif
            @if($product->discount_price)
                @php
                    $discountPct = round((($product->price - $product->discount_price) / $product->price) * 100);
                @endphp
                <span class="bg-[#7B8B75] text-white text-[9px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full shadow-xs">
                    Hemat {{ $discountPct }}%
                </span>
            @endif
        </div>

        <!-- Wishlist Button -->
        @auth
            @php
                $inWishlist = Auth::user()->wishlists->contains('product_id', $product->id);
            @endphp
            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-full bg-white/90 hover:bg-white {{ $inWishlist ? 'text-[#B85D66]' : 'text-[#2A2626]' }} hover:text-[#B85D66] flex items-center justify-center transition shadow-xs" title="{{ $inWishlist ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}">
                    <svg class="w-4 h-4 {{ $inWishlist ? 'text-[#B85D66]' : '' }}" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </button>
            </form>
        @endauth
    </div>

    <!-- Product Details -->
    <div class="p-4 flex flex-col flex-grow justify-between gap-3">
        <div>
            @if($product->category)
                <span class="text-[10px] uppercase tracking-widest text-[#6B6565] font-semibold block mb-1">
                    {{ $product->category->name }}
                </span>
            @endif

            <h3 class="font-serif text-base text-[#2A2626] group-hover:text-[#B85D66] transition line-clamp-1">
                <a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
            </h3>

            <p class="text-xs text-[#6B6565] line-clamp-1 mt-0.5 font-light">
                {{ $product->description }}
            </p>
        </div>

        <div class="pt-2 border-t border-[#F3ECE4] flex items-center justify-between">
            <div>
                @if($product->discount_price)
                    <div class="text-[10px] text-[#9B9494] line-through leading-none">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    <div class="text-sm font-bold text-[#B85D66]">
                        Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                    </div>
                @else
                    <div class="text-sm font-bold text-[#2A2626]">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                @endif
            </div>

            <!-- Quick View or Detail link -->
            <a href="{{ route('shop.show', $product->slug) }}"
               class="px-3 py-1.5 rounded-lg border border-[#D8BABF] hover:border-[#B85D66] hover:bg-[#F8E8EA] text-xs font-semibold text-[#B85D66] transition">
                Pilih Bunga
            </a>
        </div>
    </div>
</div>
