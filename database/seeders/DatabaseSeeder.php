<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@sanjaysales.com');
        $password = env('ADMIN_PASSWORD', 'Admin@123');

        Admin::firstOrCreate(['email' => $email], ['name' => 'Administrator', 'password' => $password]);

        $categories = [
            'Welding Machines', 'CNC Cutting Machines', 'Plasma Cutting', 'Power Tools', 'Hand Tools',
            'Safety PPE', 'Hand Gloves', 'Cut-off Wheels', 'Grinding Wheels', 'Measuring Tools',
            'Plain Wheel', 'Pulling & Lifting Materials', 'Welding Electrodes', 'Accessories',
        ];
        foreach ($categories as $i => $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'sort_order' => $i + 1, 'status' => true]);
        }

        $this->command?->info("Admin login: {$email} / {$password}");
    }
}
