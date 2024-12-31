<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'currency_code' => 'USD'],
            ['name' => 'Euro', 'currency_code' => 'EUR'],
            ['name' => 'British Pound', 'currency_code' => 'GBP'],
            ['name' => 'Japanese Yen', 'currency_code' => 'JPY'],
            ['name' => 'Australian Dollar', 'currency_code' => 'AUD'],
            ['name' => 'Canadian Dollar', 'currency_code' => 'CAD'],
            ['name' => 'Swiss Franc', 'currency_code' => 'CHF'],
            ['name' => 'Chinese Yuan', 'currency_code' => 'CNY'],
            ['name' => 'Swedish Krona', 'currency_code' => 'SEK'],
            ['name' => 'New Zealand Dollar', 'currency_code' => 'NZD'],
            ['name' => 'Mexican Peso', 'currency_code' => 'MXN'],
            ['name' => 'Singapore Dollar', 'currency_code' => 'SGD'],
            ['name' => 'Hong Kong Dollar', 'currency_code' => 'HKD'],
            ['name' => 'Norwegian Krone', 'currency_code' => 'NOK'],
            ['name' => 'South Korean Won', 'currency_code' => 'KRW'],
            ['name' => 'Turkish Lira', 'currency_code' => 'TRY'],
            ['name' => 'Russian Ruble', 'currency_code' => 'RUB'],
            ['name' => 'Indian Rupee', 'currency_code' => 'INR'],
            ['name' => 'Brazilian Real', 'currency_code' => 'BRL'],
            ['name' => 'South African Rand', 'currency_code' => 'ZAR'],
            ['name' => 'Philippine Peso', 'currency_code' => 'PHP'],
            ['name' => 'Indonesian Rupiah', 'currency_code' => 'IDR'],
            ['name' => 'Malaysian Ringgit', 'currency_code' => 'MYR'],
            ['name' => 'Thai Baht', 'currency_code' => 'THB'],
            ['name' => 'Vietnamese Dong', 'currency_code' => 'VND'],
            ['name' => 'Czech Koruna', 'currency_code' => 'CZK'],
            ['name' => 'Polish Zloty', 'currency_code' => 'PLN']
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::create($currency);
        }

    }
}
