<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstitutionAttended;
use App\Models\User;
use App\Models\State;
use App\Models\Institution;
use Carbon\Carbon;

class InstitutionAttendedSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $states = State::all();

        foreach ($users as $user) {
            // Create 1-3 institution records for each user
            $numInstitutions = rand(1, 3);
            
            for ($i = 0; $i < $numInstitutions; $i++) {
                $state = $states->random();
                $institution = Institution::where('state_id', $state->id)->inRandomOrder()->first();
                
                if ($institution) {
                    $startDate = Carbon::now()->subYears(rand(1, 10));
                    $endDate = $startDate->copy()->addYears(rand(1, 5));
                    
                    InstitutionAttended::create([
                        'user_id' => $user->id,
                        'state_id' => $state->id,
                        'institution_id' => $institution->id,
                        'type' => ['primary', 'secondary', 'tertiary'][rand(0, 2)],
                        'field_of_study' => ['Computer Science', 'Engineering', 'Business', 'Arts', 'Medicine'][rand(0, 4)],
                        'position' => ['Student', 'Staff', 'Alumni'][rand(0, 2)],
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ]);
                }
            }
        }
    }
} 