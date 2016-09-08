<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->email,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->email,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\SiteConfiguration::class, function (Faker\Generator $faker) {
    return [
        'locale'                => 'ja',
        'name'                  => $faker->name,
        'title'                 => $faker->sentence,
        'keywords'              => join(',', $faker->words(5)),
        'description'           => $faker->sentences(3, true),
        'ogp_image_id'          => 0,
        'twitter_card_image_id' => 0,
    ];
});

$factory->define(App\Models\Article::class, function (Faker\Generator $faker) {
    return [
        'slug'               => $faker->word,
        'title'              => $faker->sentence,
        'keywords'           => join(',', $faker->words(5)),
        'description'        => $faker->sentences(3, true),
        'content'            => $faker->sentences(3, true),
        'cover_image_id'     => 0,
        'locale'             => 'ja',
        'is_enabled'         => true,
        'publish_started_at' => $faker->dateTime,
        'publish_ended_at'   => $faker->dateTime,
    ];
});

$factory->define(App\Models\UserNotification::class, function (Faker\Generator $faker) {
    return [
        'user_id'       => \App\Models\UserNotification::BROADCAST_USER_ID,
        'category_type' => \App\Models\UserNotification::CATEGORY_TYPE_SYSTEM_MESSAGE,
        'type'          => \App\Models\UserNotification::TYPE_GENERAL_MESSAGE,
        'data'          => [],
        'locale'        => 'en',
        'content'       => 'TEST',
        'read'          => false,
    ];
});

$factory->define(App\Models\AdminUserNotification::class, function (Faker\Generator $faker) {
    return [
        'user_id'       => \App\Models\AdminUserNotification::BROADCAST_USER_ID,
        'category_type' => \App\Models\AdminUserNotification::CATEGORY_TYPE_SYSTEM_MESSAGE,
        'type'          => \App\Models\AdminUserNotification::TYPE_GENERAL_MESSAGE,
        'data'          => [],
        'locale'        => 'en',
        'content'       => 'TEST',
        'read'          => false,
    ];
});

/* NEW MODEL FACTORY */
