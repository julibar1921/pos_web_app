<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'Mon Épicerie', 'group' => 'general'],
            ['key' => 'company_email', 'value' => 'contact@epicerie.com', 'group' => 'contact'],
            ['key' => 'company_phone', 'value' => '+213 555 00 00 00', 'group' => 'contact'],
            ['key' => 'company_address', 'value' => '123 Rue de la Liberté, Alger', 'group' => 'contact'],
            ['key' => 'currency', 'value' => 'DT', 'group' => 'general'],
            ['key' => 'tax_number', 'value' => '000123456789', 'group' => 'general'],
            ['key' => 'footer_text', 'value' => 'Merci de votre confiance ! À bientôt.', 'group' => 'receipt'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
