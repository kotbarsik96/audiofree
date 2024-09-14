<?php

namespace Database\Factories\Product;

use App\Models\Product;
use App\Models\Taxonomy\TaxonomyValue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
  protected $model = Product::class;

  public static $productNames = [
    'AirPods Pro',
    'QuietComfort Earbuds',
    'Studio3 Wireless',
    'Elite Active 75t',
    'SoundSport Free',
    'Momentum True Wireless 3',
    'Hesh ANC',
    'Galaxy Buds Pro',
    'Life P3',
    'Studio Buds',
    'Air Pulse A100',
    'Powerbeats Pro',
    'Liberty Air 2 Pro',
    'Live Pro+ TWS',
    'Soundcore Life Q30',
    'Solo Pro',
    'Pixel Buds A-Series',
    'Max ANC',
    'Go Air Pop',
    'WH-1000XM5',
    'TWS NB2',
    'FreeBuds 4i',
    'Indy Evo',
    'TrueAir2',
    'ZenBuds',
    'Sport Earbuds',
    'Flow ANC',
    'Aeropex',
    'PurePlay Z7',
    'FlyBuds 3',
  ];

  public static $descriptions = [
    '<ol><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span><strong>Технология 4D-аудио</strong> с <em>интеллектуальным отслеживанием движения</em> головы создает объёмное и динамичное звучание, словно музыка вращается вокруг вас. </li><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span><strong>Специальные датчики</strong> анализируют акустику помещения и <em>автоматически подстраивают параметры эквалайзера</em>, чтобы вы всегда слышали музыку наилучшим образом. </li><li data-list="ordered"><span class="ql-ui" contenteditable="false"></span><strong>Встроенный микрочип второго поколения</strong> уменьшает задержку и обеспечивает стабильную связь, даже в условиях городской суеты.</li></ol>',
    '<p><strong>Звук без ограничений</strong></p><p><br></p><p>Система активного шумоподавления Adaptive Noise-X автоматически блокирует до 98% внешних звуков, предоставляя вам полный контроль над тем, что вы слышите. Модуль быстрой зарядки Lightning Boost даёт до 3 часов воспроизведения музыки всего за 7 минут подзарядки, а компактный кейс с ультранизким энергопотреблением обеспечит до 40 часов работы без подзарядки.</p><p><br></p><p>Технология 4D-аудио с интеллектуальным отслеживанием движения головы создает объёмное и динамичное звучание, словно музыка вращается вокруг вас. Специальные датчики анализируют акустику помещения и автоматически подстраивают параметры эквалайзера, чтобы вы всегда слышали музыку наилучшим образом. Встроенный микрочип второго поколения уменьшает задержку и обеспечивает стабильную связь, даже в условиях городской суеты.</p>',
    '<p><strong>Идеальный баланс между комфортом и звуком</strong></p><p><br></p><p>Эргономичные вставки с памятью формы подстраиваются под индивидуальные контуры уха, обеспечивая идеальное прилегание и изоляцию от внешних шумов. Улучшенные неодимовые драйверы создают чистые высокие частоты и глубокие басы, не искажая звучание на высоких уровнях громкости. Встроенный биосенсор автоматически подстраивает аудио под уровень окружающего шума, чтобы сохранить идеальное качество звука даже в шумных условиях.</p><p><br></p><p><strong>Звук без ограничений</strong></p><p>Система активного шумоподавления Adaptive Noise-X автоматически блокирует до 98% внешних звуков, предоставляя вам полный контроль над тем, что вы слышите. Модуль быстрой зарядки Lightning Boost даёт до 3 часов воспроизведения музыки всего за 7 минут подзарядки, а компактный кейс с ультранизким энергопотреблением обеспечит до 40 часов работы без подзарядки.</p><p><br></p><p><strong>Музыка, которая чувствует вас</strong></p><p>Технология SoundSense AI адаптирует эквалайзер под ваши предпочтения на основе анализа того, как долго вы слушаете разные жанры музыки. Специальные датчики давления и температуры кожи автоматически переключают режимы прослушивания для максимального комфорта. Полностью беспроводная конструкция с водонепроницаемой защитой IP68 позволяет не переживать за погоду или активные тренировки.</p>',
    '<p><strong>Музыка, которая чувствует вас</strong></p><p>Технология SoundSense AI адаптирует эквалайзер под ваши предпочтения на основе анализа того, как долго вы слушаете разные жанры музыки. Специальные датчики давления и температуры кожи автоматически переключают режимы прослушивания для максимального комфорта. Полностью беспроводная конструкция с водонепроницаемой защитой IP68 позволяет не переживать за погоду или активные тренировки.</p><p><br></p><ol><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Полное погружение в звук</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Звук без ограничений</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Идеальный баланс между комфортом и звуком</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Музыка, которая чувствует вас</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Управление жестами и голосом</li></ol><p><br></p><p><strong>Интеллектуальные сенсоры</strong> реагируют на простые касания или жесты, позволяя мгновенно переключаться между треками, настраивать громкость или отвечать на звонки. Благодаря встроенному голосовому ассистенту с функцией «всегда на связи», вы можете легко управлять устройствами, задавая простые команды, не доставая телефон. Беспроводное подключение с низкой задержкой обеспечит плавную работу как во время игр, так и при просмотре видео.</p><p><br></p><p><strong>Технология SoundSense AI</strong> адаптирует эквалайзер под ваши предпочтения на основе анализа того, как долго вы слушаете разные жанры музыки. Специальные датчики давления и температуры кожи автоматически переключают режимы прослушивания для максимального комфорта. Полностью беспроводная конструкция с водонепроницаемой защитой IP68 позволяет не переживать за погоду или активные тренировки.</p>',
    '<p><strong><em>Управление жестами и голосом</em></strong></p><p><br></p><p>Интеллектуальные сенсоры реагируют на простые касания или жесты, позволяя мгновенно переключаться между треками, настраивать громкость или отвечать на звонки. Благодаря встроенному голосовому ассистенту с функцией «всегда на связи», вы можете легко управлять устройствами, задавая простые команды, не доставая телефон. Беспроводное подключение с низкой задержкой обеспечит плавную работу как во время игр, так и при просмотре видео.</p><p><br></p><ol><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Система активного шумоподавления</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Технология 4D-аудио</li><li data-list="bullet"><span class="ql-ui" contenteditable="false"></span>Эргономичные вставки </li></ol><p><br></p><p><strong><em>Технология SoundSense AI</em></strong> адаптирует эквалайзер под ваши предпочтения на основе анализа того, как долго вы слушаете разные жанры музыки. Специальные датчики давления и температуры кожи автоматически переключают режимы прослушивания для максимального комфорта. Полностью беспроводная конструкция с водонепроницаемой защитой IP68 позволяет не переживать за погоду или активные тренировки.</p>'
  ];

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $names = self::$productNames;
    $descriptions = self::$descriptions;
    $statusId = TaxonomyValue::where('slug', 'product_status')
      ->where('value_slug', 'active')
      ->first()
      ->id;
    $brands = TaxonomyValue::where('slug', 'brand')->get()->pluck('id');
    $categories = TaxonomyValue::where('slug', 'category')->get()->pluck('id');
    $types = TaxonomyValue::where('slug', 'type')->get()->pluck('id');

    return [
      'name' => fake()->unique()->randomElement($names),
      'description' => fake()->randomElement($descriptions),
      'status_id' => $statusId,
      'brand_id' => fake()->randomElement($brands),
      'category_id' => fake()->randomElement($categories),
      'type_id' => fake()->randomElement($types),
      'created_by' => User::first()->id,
      'updated_by' => User::first()->id,
    ];
  }

  public function clearDuplicateVariations(Product $product)
  {
    $variations = $product->variations()->get();

    foreach ($variations as $variation) {
      $withSameName = $variations->filter(fn($v) => $v->name === $variation->name);
      if ($withSameName->count() > 1) {
        $takenNames = $product->variations()->get()->pluck('name')->toArray();

        foreach ($withSameName->slice(1) as $dupVariation) {
          $elementsWithoutDuplicates = collect(ProductVariationFactory::$names)
            ->filter(fn($name) => !array_search($name, $takenNames));

          $newName = fake()->randomElement($elementsWithoutDuplicates);
          array_push($takenNames, $newName);

          $dupVariation->update([
            'name' => $newName
          ]);
        }
      }
    }
  }

  public function clearDuplicateInfo(Product $product)
  {
    $info = $product->info()->get();

    foreach($info as $item) {
      $withSameName = $info->filter(fn($i) => $i->name === $item->name);
      if($withSameName->count() > 1) {
        $takenNames = $info->pluck('name')->toArray();

        foreach($withSameName->slice(1) as $dupInfo) {
          $elementsWithoutDuplicates = collect(ProductInfoFactory::$namesAndValues)
            ->filter(
              fn($nameAndValue) => array_search($nameAndValue['name'], $takenNames) === false
            );
          
          $newName = fake()->randomElement($elementsWithoutDuplicates)['name'];

          $dupInfo->update([
            'name' => $newName,
            'value' => ProductInfoFactory::getRandomValue($newName)
          ]);
        }
      }
    }
  }

  public function configure()
  {
    return $this->afterCreating(function (Product $product) {
      $this->clearDuplicateVariations($product);
      $this->clearDuplicateInfo($product);
    });
  }
}
