<?php

return [
    // Number of items we can pack per box (used to estimate boxes and cost)
    'items_per_box' => env('SHIPPING_ITEMS_PER_BOX', 10),

    // Shop -> transport baseline cost (per item)
    'shop_to_transport_per_item' => env('SHOP_TO_TRANSPORT_PER_ITEM', 50),

    // Default base transport per item range (used if delivery charge not set)
    'default_transport_per_item' => env('DEFAULT_TRANSPORT_PER_ITEM', 80),

    // Dhaka specific settings
    'dhaka' => [
        // base per-box for in-Dhaka deliveries (our own delivery man)
        'per_box_base' => env('DHAKA_PER_BOX_BASE', 30),
        // items per box inside Dhaka (usually more dense deliveries)
        'items_per_box' => env('DHAKA_ITEMS_PER_BOX', 10),
    ],
];
