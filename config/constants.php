<?php

return [
  'paths' => [
    'images' => [
      'products' => env('APP_NAME_SLUG').'/images/products',
      'orders' => env('APP_NAME_SLUG').'/images/orders',
      'taxonomies' => env('APP_NAME_SLUG').'/images/taxonomies'
    ],
  ],
  'roles' => [
    'S_ADMINISTRATOR' => 1,
    'ADMINISTRATOR' => 5,
    'MANAGER' => 11,
    'USER' => 21,
  ],
  'product' => [
    'rating' => [
      'max' => 5,
      'min_description_length' => 20,
      'max_description_length' => 400,
    ],
    'image_group' => 'product_image',
    'description' => [
      'maxlength' => 2000
    ],
    'description_seo' => [
      'maxlength' => 1000
    ],
    'variation' => [
      'gallery_group' => 'product_variation_gallery',
      'max_gallery_images' => 5
    ]
  ],
  'taxonomy' => [
    'brands' => [
      'Apple',
      'Samsung',
      'Huawei',
      'Xiaomi',
      'JBL',
      'Sony',
      'Phillips',
      'Sennheiser',
      'Pioneer'
    ],
    'types' => [
      'Проводные' => 'wired',
      'Беспроводные' => 'wireless',
      'Беспроводные с шейным ободом' => 'wireless_neckband'
    ],
    'product_statuses' => [
      'Активен' => 'active',
      'Неактивен' => 'inactive'
    ],
    'categories' => [
      'Наушники' => 'headphones'
    ],
  ],
  'order' => [
    'image_group' => 'product_order'
  ]
];
