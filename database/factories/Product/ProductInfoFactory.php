<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductInfoFactory extends Factory
{
  public static $namesAndValues = [
    [
      'name' => 'Тип наушников',
      'value' => 'Внутриканальные / Накладные / Полноразмерные',
    ],
    [
      'name' => 'Тип подключения',
      'value' => 'Проводные / Беспроводные / Гибридные',
    ],
    [
      'name' => 'Поддержка Bluetooth',
      'value' => 'Да, 5.0 / Да, 5.1 / Да, 5.2 / Нет',
    ],
    [
      'name' => 'Активное шумоподавление (ANC)',
      'value' => 'Да / Нет',
    ],
    [
      'name' => 'Частотный диапазон',
      'value' => '20 Гц - 20 000 Гц',
    ],
    [
      'name' => 'Импеданс',
      'value' => '16 Ом / 32 Ом / 64 Ом',
    ],
    [
      'name' => 'Чувствительность',
      'value' => '100 дБ / 105 дБ / 110 дБ',
    ],
    [
      'name' => 'Диаметр динамиков',
      'value' => '10 мм / 40 мм / 50 мм',
    ],
    [
      'name' => 'Тип драйвера',
      'value' => 'Динамический / Арматурный / Планарный',
    ],
    [
      'name' => 'Время автономной работы',
      'value' => '8 часов / 24 часа (с кейсом) / 40 часов',
    ],
    [
      'name' => 'Время зарядки',
      'value' => '1 час / 2 часа',
    ],
    [
      'name' => 'Материал амбушюр',
      'value' => 'Искусственная кожа / Мемори-фоам / Ткань',
    ],
    [
      'name' => 'Вес',
      'value' => '150 г / 250 г / 300 г',
    ],
    [
      'name' => 'Водонепроницаемость',
      'value' => 'IPX4 / IPX5 / IPX7',
    ],
    [
      'name' => 'Поддержка кодеков',
      'value' => 'SBC / AAC / aptX / LDAC',
    ],
    [
      'name' => 'Микрофон',
      'value' => 'Встроенный / Съемный / Отсутствует',
    ],
    [
      'name' => 'Управление жестами',
      'value' => 'Да / Нет',
    ],
    [
      'name' => 'Регулировка громкости',
      'value' => 'Да / Нет',
    ],
    [
      'name' => 'Световая индикация',
      'value' => 'Да / Нет',
    ],
    [
      'name' => 'Складная конструкция',
      'value' => 'Да / Нет',
    ],
  ];

  public static function getRandomValue(string $name)
  {
    $item = self::$namesAndValues[array_search($name, array_column(self::$namesAndValues, 'name'))];
    return fake()->randomElement(explode(' / ', $item['value']));
  }

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->randomElement(array_column(self::$namesAndValues, 'name')),
      'slug' => fn(array $attributes) => Str::slug($attributes['name']),
      'value' => fn(array $attributes) => self::getRandomValue($attributes['name']),
    ];
  }
}
