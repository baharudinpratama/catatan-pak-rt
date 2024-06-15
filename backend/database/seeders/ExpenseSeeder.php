<?php

namespace Database\Seeders;

use App\Models\Expense;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        foreach (range(1, 20) as $index) {
            $expense = new Expense();
            $expense->description = $faker->sentence;
            $expense->amount = $faker->numberBetween(50000, 300000);
            $expense->date = $faker->dateTimeThisYear()->format('Y-m-d');

            $expense->save();
        }
    }
}
