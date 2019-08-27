<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lesson;
use Faker\Generator as Faker;

$factory->define(Lesson::class, function (Faker $faker) {
    $lesson = $faker->unique()->sentence;
    return [
        'lesson' => $lesson,
        'description' => $faker->paragraph,
        'slug' => str_slug($lesson, '-')
    ];
});
