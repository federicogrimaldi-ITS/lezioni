<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $alpha2
 * @property string $alpha3
 * @property string $name
 * @property string $name_it
 * @property string $capital
 * @property string $region
 * @property string|null $subregion
 * @property int $population
 * @property array<int, string> $languages
 * @property array<int, string> $currencies
 */
class Country extends Model
{
    protected $fillable = [
        'alpha2', 'alpha3', 'name', 'name_it', 'capital',
        'region', 'subregion', 'population', 'languages', 'currencies',
    ];

    protected $casts = [
        'population' => 'integer',
        'languages' => 'array',
        'currencies' => 'array',
    ];

    private const REGIONI_IT = [
        'Africa' => 'Africa',
        'Americas' => 'Americhe',
        'Asia' => 'Asia',
        'Europe' => 'Europa',
        'Oceania' => 'Oceania',
        'Polar' => 'Regioni polari',
    ];

    public function regioneIt(): Attribute
    {
        return Attribute::get(fn () => self::REGIONI_IT[$this->region] ?? $this->region);
    }

    public function flagUrl(): Attribute
    {
        return Attribute::get(fn () => sprintf('https://flagcdn.com/w320/%s.png', strtolower($this->alpha2)));
    }

    public function popolazioneFormattata(): Attribute
    {
        return Attribute::get(fn () => number_format($this->population, 0, ',', '.') . ' ab.');
    }

    public function linguaCasuale(): string
    {
        return $this->languages[array_rand($this->languages)];
    }

    public function valutaCasuale(): string
    {
        return $this->currencies[array_rand($this->currencies)];
    }
}
