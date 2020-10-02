<?php

use Illuminate\Database\Seeder;

class BotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        \App\Bot::truncate();

        \App\Bot::create([
            'bot_url'=>"lotus",
            'token_prod'=>"917320832:AAHcaThvQ8oASgd0EngVASuMC_d2LC-Ft6o",
            'token_dev'=>"917320832:AAHcaThvQ8oASgd0EngVASuMC_d2LC-Ft6o",
            'description'=>"Бот модельного агенства",
            'bot_pic'=>"",
            'is_active'=>true,
            'money'=>310,
            'money_per_day'=>10,
        ]);

        \App\Bot::create([
            'bot_url'=>"test_lotus",
            'token_prod'=>"1010474333:AAHPLSlhAiPh1kDaZvgKAwKYjf3E-9JELvA",
            'token_dev'=>"1010474333:AAHPLSlhAiPh1kDaZvgKAwKYjf3E-9JELvA",
            'description'=>"Бот ТЕСТ зеркало бота модельного агенства",
            'bot_pic'=>"",
            'is_active'=>true,
            'money'=>310,
            'money_per_day'=>10,
        ]);
    }
}
