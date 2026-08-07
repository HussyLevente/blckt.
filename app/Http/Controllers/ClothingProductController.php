<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class ClothingProductController extends Controller
{
    public function index()
    {
        $locale = App::getLocale();

        $products = array_map(
            fn (array $product) => $this->resolveLocale($product, $locale),
            $this->products()
        );

        return view('clothing.index', [
            'products' => $products,
            'placeholderCount' => 5,
        ]);
    }

    public function show(string $product)
    {
        $products = $this->products();

        abort_unless(isset($products[$product]), 404);

        return view('clothing.show', [
            'product' => $this->resolveLocale($products[$product], App::getLocale()),
        ]);
    }

    private function resolveLocale(array $product, string $locale): array
    {
        $pick = fn (array $values) => $values[$locale] ?? $values['en'];

        $product['subtitle'] = $pick($product['subtitle']);
        $product['color'] = $pick($product['color']);

        return $product;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function products(): array
    {
        return [
            'ratio' => [
                'slug' => 'ratio',
                'name' => 'RATIO',
                'subtitle' => [
                    'en' => 'Unisex Latin Oversized T-shirt',
                    'hu' => 'Unisex latin feliratos oversize póló',
                ],
                'price' => '14.99€',
                'is_new' => true,
                'color' => [
                    'en' => 'White',
                    'hu' => 'Fehér',
                ],
                'thumbnail' => 'assets/imgs/ratio_showroom_back.png',
                'images' => [
                    'assets/imgs/ratio_ps_front.png',
                    'assets/imgs/ratio_ps_back.png',
                    'assets/imgs/ratio_showroom_front.png',
                    'assets/imgs/ratio_showroom_back.png',
                ],
            ],
            'hollyweed' => [
                'slug' => 'hollyweed',
                'name' => 'HOLLYWEED',
                'subtitle' => [
                    'en' => 'Unisex Graphic T-shirt',
                    'hu' => 'Unisex mintás póló',
                ],
                'price' => '14.99€',
                'is_new' => false,
                'color' => [
                    'en' => 'Black',
                    'hu' => 'Fekete',
                ],
                'thumbnail' => 'assets/imgs/hollyweed_showroom_front.png',
                'images' => [
                    'assets/imgs/hollyweed_ps_front.png',
                    'assets/imgs/hollyweed_ps_back.png',
                    'assets/imgs/hollyweed_showroom_front.png',
                    'assets/imgs/hollyweed_showroom_back.png',
                ],
            ],
            'agapiti' => [
                'slug' => 'agapiti',
                'name' => 'AGAPITÍ SKÓPELOS',
                'subtitle' => [
                    'en' => 'Unisex Oversized T-shirt',
                    'hu' => 'Unisex oversize póló',
                ],
                'price' => '14.99€',
                'is_new' => false,
                'color' => [
                    'en' => 'White',
                    'hu' => 'Fehér',
                ],
                'thumbnail' => 'assets/imgs/agapiti_showroom_back.png',
                'images' => [
                    'assets/imgs/agapiti_ps_front.png',
                    'assets/imgs/agapiti_ps_back.png',
                    'assets/imgs/agapiti_showroom_front.png',
                    'assets/imgs/agapiti_showroom_back.png',
                ],
            ],
        ];
    }
}
