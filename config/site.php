<?php

/*
|--------------------------------------------------------------------------
| Website settings served by GET /api/settings and GET /api/home
|--------------------------------------------------------------------------
| Header / footer / contact page details, hero slider, about block and
| homepage counters. Values marked "env" can be changed in .env without
| touching code. Run `php artisan config:clear` after editing.
*/

return [
    'name' => 'Sanjay Sales',
    'tagline' => 'Trusted supplier of welding, cutting and industrial solutions.',

    'contact' => [
        'phones' => array_values(array_filter([
            env('SITE_PHONE_1', '+91 98250 47028'),
            env('SITE_PHONE_2', '+91 93749 91103'),
        ])),
        'emails' => array_values(array_filter([
            env('SITE_EMAIL_1', 'san_shah120@yahoo.com'),
            env('SITE_EMAIL_2'),
        ])),
        // digits only, with country code – used for https://wa.me/<number>
        'whatsapp' => env('SITE_WHATSAPP', '919825047028'),
        'address' => env('SITE_ADDRESS', '10, Mangal Estate, Nr. Chakudia Mahadev Temple, Rakhial, Ahmedabad - 380023'),
        'map_embed_url' => env('SITE_MAP_EMBED_URL', 'https://www.google.com/maps?q=Sanjay+Sales+Rakhial+Ahmedabad&output=embed'),
        'map_link' => env('SITE_MAP_LINK', 'https://maps.google.com/?q=Sanjay+Sales+Rakhial+Ahmedabad'),
        'business_hours' => env('SITE_HOURS', 'Mon – Sat: 10:00 AM – 7:00 PM'),
    ],

    // leave a value empty to hide that icon
    'social' => [
        'facebook' => env('SOCIAL_FACEBOOK', ''),
        'instagram' => env('SOCIAL_INSTAGRAM', ''),
        'linkedin' => env('SOCIAL_LINKEDIN', ''),
        'youtube' => env('SOCIAL_YOUTUBE', ''),
        'twitter' => env('SOCIAL_TWITTER', ''),
    ],

    // Inquiry form submissions are e-mailed here (needs MAIL_* set in .env). Empty = don't e-mail.
    'inquiry_email' => env('INQUIRY_EMAIL', env('SITE_EMAIL_1', 'san_shah120@yahoo.com')),

    // Homepage hero slider. "image" is a path/URL on the front-end; null = front-end default.
    'hero_slides' => [
        [
            'title' => 'Powering Industrial Performance.',
            'subtitle' => 'Reliable welding and cutting solutions for every workshop and industry.',
            'button_text' => 'Explore Products',
            'button_link' => '/products',
            'image' => null,
        ],
        [
            'title' => 'Everything Your Workshop Needs.',
            'subtitle' => 'Cutting systems, power tools, safety products, measuring tools and accessories under one roof.',
            'button_text' => 'Explore Product Range',
            'button_link' => '/products',
            'image' => null,
        ],
        [
            'title' => 'Cut Smarter. Work Faster. Build Better.',
            'subtitle' => 'CNC and plasma cutting machines built for precision and speed.',
            'button_text' => 'View Cutting Range',
            'button_link' => '/products',
            'image' => null,
        ],
    ],

    'about' => [
        'since' => 1981,
        'title' => 'Built On Experience. Driven By Trust.',
        'description' => 'Sanjay Sales is a distributor, exporter and importer of welding consumables, inverter based welding equipment and accessories, with a strong distribution network across Gujarat.',
        'highlights' => [
            ['value' => '40+', 'label' => 'Years of Industry Experience'],
            ['value' => 'Gujarat', 'label' => 'Strong Distribution Presence'],
        ],
    ],

    // "Numbers That Define Us" – update these to the real figures.
    'stats' => [
        ['value' => (int) env('STAT_YEARS', 40), 'suffix' => '+', 'label' => 'Years Experience'],
        ['value' => (int) env('STAT_PRODUCTS', 500), 'suffix' => '+', 'label' => 'Products Supplied'],
        ['value' => (int) env('STAT_CLIENTS', 1000), 'suffix' => '+', 'label' => 'Satisfied Clients'],
        ['value' => (int) env('STAT_BRANDS', 25), 'suffix' => '+', 'label' => 'Trusted Brands'],
    ],
];
