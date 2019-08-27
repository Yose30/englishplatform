<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Group;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
    $teacher_id = $faker->numberBetween($min = 1, $max = 10);
    $num = $faker->unique()->numberBetween($min = 1, $max = 5);
    $name = 'Grupo '.$num;
    return [
        'name' => $name,
        'teacher_id' => $teacher_id,
        'slug' => str_slug($name, '-')
    ];
});
