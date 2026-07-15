<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

/**
 * Popola la tabella countries a partire dalla fixture generata dal dump originale
 * (database/seeders/data/countries.json).
 */
class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = json_decode(
            file_get_contents(__DIR__ . '/data/countries.json'),
            associative: true,
            flags: JSON_THROW_ON_ERROR
        );

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['alpha2' => $country['alpha2']],
                [
                    'alpha3' => $country['alpha3'],
                    'name' => $country['name'],
                    'name_it' => $country['name_it'],
                    'capital' => $country['capital'],
                    'region' => $country['region'],
                    'subregion' => $country['subregion'],
                    'population' => $country['population'],
                    'languages' => $country['languages'],
                    'currencies' => $country['currencies'],
                ]
            );
        }

        $this->command->info(count($countries) . ' paesi importati.');
    }
}
