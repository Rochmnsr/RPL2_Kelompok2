<div class="w-full h-screen max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-6">
        <h1 class="text-2xl font-semibold mb-4">Keranjang</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white flex overflow-x-auto rounded-lg shadow-md p-6 mb-4" style="max-height: 850px;">
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">Menu</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-semibold text-gray-600 uppercase tracking-wider">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cart_items as $item)
                                <tr wire:key="{{ $item['menu_id'] }}">
                                    <td class="px-6 py-4 whitespace-normal">
                                        <div class="flex-shrink-0">
                                            <img class="h-16 w-16 mr-4 object-cover" src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                            <span class="font-semibold">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ Number::currency($item['unit_amount'], 'IDR') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <button wire:click="decreaseQty({{ $item['menu_id'] }})" class="border rounded-md py-2 px-4 mr-2">-</button>
                                            <span class="text-center w-8">{{ $item['quantity'] }}</span>
                                            <button wire:click="increaseQty({{ $item['menu_id'] }})" class="border rounded-md py-2 px-4 ml-2">+</button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ Number::currency($item['total_amount'], 'IDR') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button wire:click="removeItem({{ $item['menu_id'] }})" class="bg-red-500 text-white border-2 border-red-500 rounded-lg px-3 py-1 hover:bg-red-700">
                                            <span wire:loading.remove wire:target="removeItem({{ $item['menu_id'] }})">Remove</span>
                                            <span wire:loading wire:target="removeItem({{ $item['menu_id'] }})">Removing...</span>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-4xl font-semibold text-gray-500">No items available in cart!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Detail</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ Number::currency($grand_total, 'IDR') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Pengiriman</span>
                        <span>{{ Number::currency(0, 'IDR') }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">{{ Number::currency($grand_total, 'IDR') }}</span>
                    </div>
                    @if ($cart_items)
                    <a href="/checkout" class="bg-blue-500 block text-center text-white py-2 px-4 rounded-lg mt-4 w-full">Checkout</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
