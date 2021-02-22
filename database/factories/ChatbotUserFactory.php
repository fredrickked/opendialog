<?php

use Illuminate\Support\Str;
use OpenDialogAi\ConversationLog\ChatbotUser;
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

$factory->define(ChatbotUser::class, function (Faker $faker) {
    $os = ['Mac OS', 'Windows 95', 'Windows 97', 'Ubuntu'];
    $browsers = ['IE 6', 'Netscape Navigator', 'AOL'];
    return [
        'user_id' => Str::random(20),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'ip_address' => $faker->ipv4,
        'os' => $os[array_rand($os)],
        'country' => $faker->country,
        'browser' => $browsers[array_rand($browsers)],
        'timezone' => $faker->timezone,
        'browser_language' => $faker->languageCode,
        'platform' => 'webchat',
        'email' => $faker->email,
        'created_at' => $faker->dateTimeBetween('-30 days'),
        'updated_at' => $faker->dateTimeBetween('-30 days'),
    ];
});
