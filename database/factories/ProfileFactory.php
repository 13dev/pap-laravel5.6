<?php

use App\User;
use App\Image;
use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'display_name' => $faker->firstName.' '.$faker->lastName,
        'location' => $faker->country,
        //'image_id' => function () {
        //    return factory(Image::class)->create()->id;
        //},

    ];
});
