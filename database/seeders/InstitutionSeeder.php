<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\State;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $jsonPath = database_path('seeders/institutions.json');
            if (!file_exists($jsonPath)) {
                Log::error('Institutions JSON file not found at: ' . $jsonPath);
                return;
            }

            $jsonContent = file_get_contents($jsonPath);
            if ($jsonContent === false) {
                Log::error('Failed to read institutions JSON file');
                return;
            }

            $institutions = json_decode($jsonContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to decode JSON: ' . json_last_error_msg());
                return;
            }

            foreach ($institutions as $stateData) {
                foreach ($stateData as $stateName => $institutionsList) {
                    // Find or create the state
                    $state = State::firstOrCreate(
                        ['name' => $stateName],
                        ['name' => $stateName]
                    );

                    foreach ($institutionsList as $institutionName) {
                        if (!empty($institutionName)) {
                            // Determine ownership type
                            $ownership = 'private';
                            if (stripos($institutionName, 'Federal') !== false) {
                                $ownership = 'federal';
                            } elseif (stripos($institutionName, 'State') !== false) {
                                $ownership = 'state';
                            }

                            try {
                                Institution::firstOrCreate(
                                    [
                                        'state_id' => $state->id,
                                        'name' => $institutionName
                                    ],
                                    [
                                        'slug' => Str::slug($institutionName),
                                        'ownership' => $ownership
                                    ]
                                );
                            } catch (\Exception $e) {
                                Log::error("Failed to create institution {$institutionName}: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in InstitutionSeeder: ' . $e->getMessage());
            throw $e;
        }
    }
}
