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
    'statuses' => [
      'active',
      'inactive',
      'moderation',
    ],
    'image_group' => 'product_image',
    // типы таксономий, доступные для фильтров в каталоге
    'catalog_taxonomies_types' => [
      'brand',
      'category',
      'type',
      'product_status'
    ],
    'description' => [
      'maxlength' => 300
    ]
  ],
  'order' => [
    'statuses' => [
      'waiting_payment',
      'in_delivery_paid',
      'in_delivery_not_paid',
      'received',
      'paid',
      'rejected',
      'waiting_return',
      'returned',
      'canceled'
    ]
  ]
];
