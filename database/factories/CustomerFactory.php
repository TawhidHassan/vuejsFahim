<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'email'=>$faker->unique()->safeEmail,
        'phone'=>$faker->phoneNumber,
        'address'=>$faker->address,
        'total'=>$faker->randomFloat($nbMaxDecimal=2,$min=100.00,$max=100.00)

    ];
});
