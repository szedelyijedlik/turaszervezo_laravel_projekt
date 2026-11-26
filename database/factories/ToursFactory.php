<?php

namespace Database\Factories;

use App\Enums\TourDifficulty;
use App\Models\Tours;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends Factory<Tours>
 */
class ToursFactory extends Factory
{
    private static array $mockPrice = [
        4900, 7900, 12900, 19900, 29900
    ];

    private static array $mockDesinations = [
        'Pilis – Dobogókő',
        'Mátra – Kékestető',
        'Bükk – Szilvásvárad',
        'Börzsöny – Királyrét',
        'Bakony – Cuha-völgy',
        'Mecsek – Pécs',
        'Balaton-felvidék – Tihany',
        'Őrség – Őriszentpéter',
        'Zemplén – Füzér',
        'Visegrádi-hegység – Prédikálószék',
        'Kiskunság – Bugac',
        'Fertő-táj – Fertőd',
    ];

    private static array $mockDescriptions = [
        'Panorámás kirándulóhely a Pilisben, több jelzett túraútvonallal.',
        'Magyarország legmagasabb pontja, erdei túrákkal és kilátással.',
        'A Szalajka-völgy vízesései és erdei ösvényei várják a látogatókat.',
        'Kedvelt erdei kirándulóhely kisvasúttal és családbarát útvonalakkal.',
        'Sziklás szurdokvölgy patakparti ösvényekkel és vasúti viaduktokkal.',
        'Városi látnivalók és közeli erdei túraútvonalak kombinációja.',
        'Levendulás tájak, kilátópontok és történelmi emlékek várják a látogatókat.',
        'Nyugodt, dombos táj hagyományos falvakkal és erdei sétákkal.',
        'A vár és a környező hegyvidék látványos túrákat kínál.',
        'Népszerű túracél, ahonnan szép kilátás nyílik a Dunakanyarra.',
        'Homokbuckás pusztai táj, ahol a természet és a pásztorkultúra is megismerhető.',
        'A környék kerékpározásra és természetjárásra is alkalmas.',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            "starts_on" => fake()->dateTimeBetween("+3 days", "+30 days")->format('Y-m-d'),
            "description" => fake()->optional(0.7)->randomElement(ToursFactory::$mockDescriptions),
            "destination" => function() {
                static $remaining = [];

                if ($remaining === [])
                {
                    $remaining = array_values(
                        array_unique(ToursFactory::$mockDesinations)
                    );
                    shuffle($remaining);
                }

                return array_pop($remaining);
            },
            "days" => fake()->numberBetween(1, 5),
            "difficulty" => fake()->randomElement(TourDifficulty::cases()),
            "price_per_person" => fake()->randomElement(ToursFactory::$mockPrice),
            "free_places" => fake()->numberBetween(1, 30)
        ];
    }
}
