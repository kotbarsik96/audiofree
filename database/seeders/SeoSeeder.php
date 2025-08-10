<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoSeeder extends Seeder
{
    private static function getData()
    {
        return [
            [
                'slug' => 'home',
                'title' => 'AudioFree – Наушники с идеальным звуком по лучшей цене',
                'description' => 'Интернет-магазин AudioFree – широкий выбор наушников для музыки, игр и спорта. Гарантия качества, быстрая доставка по всей России.'
            ],
            [
                'slug' => 'catalog',
                'title' => 'Каталог наушников – AudioFree | Купить наушники с доставкой',
                'description' => 'Выбирайте наушники любых типов – беспроводные, проводные, игровые, спортивные. Доступные цены и проверенные бренды в магазине AudioFree.'
            ],
            [
                'slug' => 'delivery-payment',
                'title' => 'Доставка и оплата – AudioFree | Удобные способы получения',
                'description' => 'Подробная информация о доставке и оплате в AudioFree. Быстрая отправка заказов по всей России и удобные способы оплаты.'
            ],
            [
                'slug' => 'warranty-returns',
                'title' => 'Гарантия и возврат – AudioFree | Защита покупателя',
                'description' => 'Узнайте условия гарантии и возврата наушников в AudioFree. Мы заботимся о вашей защите и предлагаем только качественную продукцию.'
            ],
            [
                'slug' => 'contacts',
                'title' => 'Контакты – AudioFree | Связаться с нами',
                'description' => 'Свяжитесь с командой AudioFree по телефону, email или через форму обратной связи. Мы всегда готовы помочь с выбором и заказом наушников.'
            ],
            [
                'slug' => 'favorites',
                'title' => '(%:count) Избранное – AudioFree | Ваш список лучших наушников',
                'description' => 'Сохраняйте понравившиеся наушники в избранное и возвращайтесь к ним в любое время. Легко сравнивайте модели и делайте выгодные покупки.'
            ],
            [
                'slug' => 'cart',
                'title' => '(%:count) Корзина – AudioFree | Оформление заказа на наушники',
                'description' => 'Проверьте выбранные товары и оформите заказ в интернет-магазине AudioFree. Быстро, удобно и с гарантией качества.'
            ],
            [
                'slug' => 'orders',
                'title' => 'Мои заказы – AudioFree | История покупок',
                'description' => 'Просматривайте список и статус своих заказов в магазине AudioFree. Легкий доступ к информации о доставке и оплате.'
            ],
            [
                'slug' => 'order',
                'title' => 'Оформление заказа – AudioFree | Создание заказа на наушники',
                'description' => 'Укажите информацию о доставке для оформления заказа в интернет-магазине AudioFree. Быстро, удобно и с гарантией качества.'
            ],
            [
                'slug' => 'profile',
                'title' => 'Профиль – AudioFree | Управление аккаунтом',
                'description' => 'Изменяйте личные данные, пароль и настройки в своем профиле AudioFree. Полный контроль над информацией и безопасностью аккаунта.'
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = DB::raw("NOW()");

        foreach (static::getData() as $item) {
            DB::table('seo')->insert(array_merge([
                'created_at' => $now,
                'updated_at' => $now,
            ], $item));
        }
    }
}
