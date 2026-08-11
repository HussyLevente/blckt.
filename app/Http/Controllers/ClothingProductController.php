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
            'placeholderCount' => 3,
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

    /**
     * @return string[]
     */
    public function slugs(): array
    {
        return array_keys($this->products());
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
                'thumbnail' => 'assets/imgs/clothing/ratio/ratio_showroom_back.png',
                'images' => [
                    'assets/imgs/clothing/ratio/ratio_ps_front.png',
                    'assets/imgs/clothing/ratio/ratio_ps_back.png',
                    'assets/imgs/clothing/ratio/ratio_showroom_front.png',
                    'assets/imgs/clothing/ratio/ratio_showroom_back.png',
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
                'thumbnail' => 'assets/imgs/clothing/hollyweed/hollyweed_showroom_front.png',
                'images' => [
                    'assets/imgs/clothing/hollyweed/hollyweed_ps_front.png',
                    'assets/imgs/clothing/hollyweed/hollyweed_ps_back.png',
                    'assets/imgs/clothing/hollyweed/hollyweed_showroom_front.png',
                    'assets/imgs/clothing/hollyweed/hollyweed_showroom_back.png',
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
                'thumbnail' => 'assets/imgs/clothing/agapiti/agapiti_showroom_back.png',
                'images' => [
                    'assets/imgs/clothing/agapiti/agapiti_ps_front.png',
                    'assets/imgs/clothing/agapiti/agapiti_ps_back.png',
                    'assets/imgs/clothing/agapiti/agapiti_showroom_front.png',
                    'assets/imgs/clothing/agapiti/agapiti_showroom_back.png',
                ],
            ],
            'prodigy' => [
                'slug' => 'prodigy',
                'name' => 'PRODIGY.',
                'subtitle' => [
                    'en' => 'Unisex Photo Print T-shirt',
                    'hu' => 'Unisex fotónyomott póló',
                ],
                'price' => '14.99€',
                'is_new' => true,
                'color' => [
                    'en' => 'White',
                    'hu' => 'Fehér',
                ],
                'thumbnail' => 'assets/imgs/clothing/prodigy/prodigy_showroom_back.png',
                'images' => [
                    'assets/imgs/clothing/prodigy/prodigy_ps_front.png',
                    'assets/imgs/clothing/prodigy/prodigy_ps_back.png',
                    'assets/imgs/clothing/prodigy/prodigy_showroom_front.png',
                    'assets/imgs/clothing/prodigy/prodigy_showroom_back.png',
                ],
            ],
            'miamivice' => [
                'slug' => 'miamivice',
                'name' => 'MIAMI VICE',
                'subtitle' => [
                    'en' => 'Unisex Retro Poster T-shirt',
                    'hu' => 'Unisex retro poszteres póló',
                ],
                'price' => '14.99€',
                'is_new' => true,
                'color' => [
                    'en' => 'White',
                    'hu' => 'Fehér',
                ],
                'thumbnail' => 'assets/imgs/clothing/miamivice/miamivice_showroom_front.png',
                'images' => [
                    'assets/imgs/clothing/miamivice/miamivice_ps_front.png',
                    'assets/imgs/clothing/miamivice/miamivice_ps_back.png',
                    'assets/imgs/clothing/miamivice/miamivice_showroom_front.png',
                    'assets/imgs/clothing/miamivice/miamivice_showroom_back.png',
                ],
            ],
        ];
    }
}
