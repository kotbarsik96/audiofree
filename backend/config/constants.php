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
    // типы таксономий, доступные для фильтров в каталоге
    'catalog_taxonomies' => [
      'brand',
      'category',
      'type',
      'product_status'
    ],
    'description' => [
      'maxlength' => 300
    ],
    'variation' => [
      'image_group' => 'product_variation_image',
      'gallery_group' => 'product_variation_gallery',
      'max_gallery_images' => 5
    ]
  ],
  'order' => [
    'statuses' => [
      'Ожидает оплаты' => 'waiting_payment',
      'В доставке, оплачен' => 'in_delivery_paid',
      'В доставке, не оплачен' => 'in_delivery_not_paid',
      'Получен' => 'received',
      'Оплачен' => 'paid',
      'Отменён' => 'canceled',
      'Ожидается возврат' => 'waiting_return',
      'Возвращен' => 'returned'
    ]
  ]
];
