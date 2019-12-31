<?php

use App\Models\Value;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedRowCount = 100000;

        // factory(Value::class, $seedRowCount)->create(); // Super Slow

        // Note: 'LOAD DATA INFILE' can be used to insert millions of data, if needed

        $count = 0;
        $faker = Faker::create();
        $expiresAt = now()->addMinutes($faker->biasedNumberBetween(0, 5));

        $columns = "`id`, `key`, `value`, `expires_at`";
        $values = [];

        echo ">>>>>  Seeding $seedRowCount rows";

        DB::unprepared("SET autocommit=0; SET unique_checks=0; SET foreign_key_checks=0;"); // if ENGINE=InnoDB
        DB::statement("ALTER TABLE `values` DISABLE KEYS;");

        foreach(range(1, $seedRowCount) as $id) {
            $count++;
            $value = $faker->sentence($nbWords = 4, $variableNbWords = true);
            $values[] = "($id, '$id', '$value', '$expiresAt')";

            if ($count == 10000) {
                $values = implode(", ", $values);
                \DB::insert("INSERT INTO `values` ($columns) VALUES $values");
                echo '.';
                $count = 0;
                $values = [];
                $expiresAt = now()->addMinutes($faker->biasedNumberBetween(0, 5));
            }
        }

        DB::unprepared("COMMIT; SET unique_checks=1; SET foreign_key_checks=1;");
        DB::statement("ALTER TABLE `values` ENABLE KEYS;");

        echo 'Complete  <<<<<'.PHP_EOL;
    }
}
