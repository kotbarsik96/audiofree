<?php

return [
  'roles' => [
    'S_ADMINISTRATOR' => 1,
    'ADMINISTRATOR' => 5,
    'MANAGER' => 11,
    'USER' => 21,
  ],
  'product' => [
    'rating' => [
      'max' => 5
    ],
    'image_group' => 'product_image',
    'description' => [
      'maxlength' => 2000
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
    'order_statuses' => [
      'Ожидает оплаты' => 'waiting_payment',
      'В доставке, оплачен' => 'in_delivery_paid',
      'В доставке, не оплачен' => 'in_delivery_not_paid',
      'Получен' => 'received',
      'Оплачен' => 'paid',
      'Отменён' => 'canceled',
      'Ожидается возврат' => 'waiting_return',
      'Возвращен' => 'returned'
    ],
  ],
];
