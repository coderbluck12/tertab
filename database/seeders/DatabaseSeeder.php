<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create admin users if they don't exist
        if (!User::where('email', 'admin.one@tertab.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin One',
                'role' => 'admin',
                'phone' => '09000000000',
                'address' => 'Tertab HQ',
                'status' => 'approved',
                'email' => 'admin.one@tertab.com',
            ]);
        }

        if (!User::where('email', 'admin.two@tertab.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin Two',
                'role' => 'admin',
                'phone' => '09000000000',
                'address' => 'Tertab HQ',
                'status' => 'approved',
                'email' => 'admin.two@tertab.com',
            ]);
        }

        if (!User::where('email', 'super.admin@tertab.com')->exists()) {
            User::factory()->create([
                'name' => 'Super Admin',
                'role' => 'super admin',
                'phone' => '09000000000',
                'address' => 'Tertab HQ',
                'status' => 'approved',
                'email' => 'super.admin@tertab.com',
            ]);
        }

        // Add a test student if it doesn't exist
        if (!User::where('email', 'test.student@tertab.com')->exists()) {
            User::factory()->create([
                'name' => 'Test Student',
                'role' => 'student',
                'phone' => '09000000001',
                'address' => 'Student Address',
                'status' => 'approved',
                'email' => 'test.student@tertab.com',
            ]);
        }

        // Create default platform settings if they don't exist
        if (!\App\Models\PlatformSetting::exists()) {
            \App\Models\PlatformSetting::create([
                'reference_request_price' => 1000.00
            ]);
        }

        $this->call([
            StateSeeder::class,
            InstitutionSeeder::class,
            InstitutionAttendedSeeder::class,
            // Add other seeders here
        ]);
    }
}
