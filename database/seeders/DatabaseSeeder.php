<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VolunteerProfile;
use App\Models\Activity;
use App\Models\ActivitySession;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@cse.org',
            'phone' => '0900000000',
            'preferred_help' => 'System Administration and Oversight',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'must_change_password' => false,
        ]);

        $volunteersData = [
            //1
            [
                'name' => 'Hasan Ali Daoud',
                'email' => 'hasandaoud28@gmail.com',
                'phone' => '0991123128',
                'preferred_help' => '',
            ],
            //2
            [
                'name' => 'Riman Hamzeh Katbeh',
                'email' => 'remankat@gmail.com',
                'phone' => '0947772493',
                'preferred_help' => 'Free media - Monitoring and Evaluation Unit',
            ],
            //3
            [
                'name' => 'Naseem Khalil',
                'email' => 'nasemkhalel401@gmail.com',
                'phone' => '0953408978',
                'preferred_help' => 'Free media',
            ],
            //4
            [
                'name' => 'Naamah Matrak Hamsho',
                'email' => 'naameh69@gmail.com',
                'phone' => '0997200642',
                'preferred_help' => '',
            ],
            //5
            [
                'name' => 'Firas Hamadeh Al-Assaf',
                'email' => 'ferasassaf5@gmail.com',
                'phone' => '0964992019',
                'preferred_help' => 'Psychological and social support - Social activities',
            ],
            //6
            [
                'name' => 'Aton Azzam Al-Jundi',
                'email' => 'atoonaljndi1@gmail.com',
                'phone' => '0930752596',
                'preferred_help' => 'Social activities',
            ],
            //7
            [
                'name' => 'Hammam Wael Al-Matni',
                'email' => 'humammatni@gmail.com',
                'phone' => '0954231862',
                'preferred_help' => 'Social activities',
            ],
            //8
            [
                'name' => 'Anas Youssef Youssef',
                'email' => 'anassyousef76@gmail.com',
                'phone' => '0938948070',
                'preferred_help' => '',
            ],
            //9
            [
                'name' => 'Ayoub Bassam Al-Jaramani',
                'email' => 'aljramane.ayoub@gmail.com',
                'phone' => '0938268930',
                'preferred_help' => 'Psychological and social support',
            ],
            //10
            [
                'name' => 'Hadi Walid Shalash',
                'email' => 'hadishalsh3@gmail.com',
                'phone' => '0968242964',
                'preferred_help' => '',
            ],
            //11
            [
                'name' => 'Majd Shadi Al-Laham',
                'email' => 'majodallaham@gmail.com',
                'phone' => '0992263353',
                'preferred_help' => 'Psychological and social support',
            ],
            //12
            [
                'name' => 'Faris Wahbeh Al-Sabbagh',
                'email' => 'alsabbaghxfares@gmail.com',
                'phone' => '0947260440',
                'preferred_help' => 'Social activities',
            ],
            //13
            [
                'name' => 'Yumna Ghassan Al-Dakkak',
                'email' => '7youmnashidakkak@gmail.com',
                'phone' => '0962431021',
                'preferred_help' => 'IT Department',
            ],
            //14
            [
                'name' => 'Diya Hisham Monther',
                'email' => 'deyaamonther98@gmail.com',
                'phone' => '0962352319',
                'preferred_help' => '',
            ],
            //15
            [
                'name' => 'Ashraf Al-Afif',
                'email' => 'alafifashooo@gmail.com',
                'phone' => '0991546367',
                'preferred_help' => 'Social activities - Monitoring and Evaluation Unit',
            ],
            //16
            [
                'name' => 'Salam Munzer',
                'email' => 'salam.monther99@gmail.com',
                'phone' => '0993492895',
                'preferred_help' => 'Social activities - Psychological and social support',
            ],
            //17
            [
                'name' => 'Ashraf Badi Al-Wais',
                'email' => 'Lwesashraf@gmail.com',
                'phone' => '0964546227',
                'preferred_help' => 'Psychological and social support',
            ],
            //18
            [
                'name' => 'Obaida Rabee Raad',
                'email' => 'obaidaraad84@gmail.com',
                'phone' => '0988543269',
                'preferred_help' => 'Social activities',
            ],
            //19
            [
                'name' => 'Hamsa Muaid Al-Shaibani',
                'email' => 'hamsaalshipany@gmail.com',
                'phone' => '0957860110',
                'preferred_help' => 'IT Department',
            ],
            //20
            [
                'name' => 'Rebal Nemr Daoud',
                'email' => 'dawoodrebal@gmail.com',
                'phone' => '0998994291',
                'preferred_help' => 'IT Department',
            ],
        ];

        foreach ($volunteersData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'preferred_help' => $data['preferred_help'],
                'password' => Hash::make('Password123!'),
                'role' => 'volunteer',
                'must_change_password' => true,
            ]);

            VolunteerProfile::create([
                'user_id' => $user->id,
            ]);
        }

        $activity = Activity::create([
            'title' => 'CSE Unit Community Support Program',
            'description' => 'Weekly sessions for guidance, cultural events, and community activities.',
            'is_active' => true,
        ]);

        $firstVolunteer = User::where('role', 'volunteer')->first();

        ActivitySession::create([
            'activity_id' => $activity->id,
            'supervisor_id' => $firstVolunteer ? $firstVolunteer->id : null,
            'day_of_week' => 'Sunday',
            'time_slot' => '16:00 - 18:00',
        ]);
    }
}
