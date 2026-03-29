<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Production;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Production::class, function (Faker $faker) {
    $produced = $faker->numberBetween(100, 500);
    $defects = $faker->numberBetween(0, 15);
    $efficiency = number_format((($produced - $defects) / $produced) * 100, 2);

    return [
        'product_line' => $faker->randomElement([
            'Linha TV OLED',
            'Linha Mobile',
            'Linha White Goods',
            'Linha Monitor Gaming',
            'Linha Refrigeradores',
            'Linha Lavadoras de Roupas',
            'Linha Ar Condicionado',
            'Linha Micro-ondas',
            'Linha Aspiradores de Pó',
            'Linha Celulares',
            'Linha Tablets',
            'Linha Smartwatches',
            'Linha Monitores',
            'Linha Notebooks',
            'Linha Impressoras',
            'Linha Projetores',
            'Linha Soundbars',
            'Linha Fones de Ouvido',
            'Linha Caixas de Som',
            'Linha TVs LED'
        ]),
        'quantity_produced' => $produced,
        'quantity_defects' => $defects,
        'efficiency' => $efficiency,
        'production_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
    ];
});
