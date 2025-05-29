<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        Language::insert([
            ['name' => 'English', 'code' => 'en', 'is_active' => true],
            ['name' => 'Hindi', 'code' => 'hi', 'is_active' => true],
            ['name' => 'French', 'code' => 'fr', 'is_active' => true],
            ['name' => 'Spanish', 'code' => 'es', 'is_active' => false], // Example inactive
            ['name' => 'Bengali', 'code' => 'bn', 'is_active' => true],
        ]);
    }
}