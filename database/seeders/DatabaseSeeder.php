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
                'preferred_help' => 'Theater, Reading Lab, Events, Festivals, Red Cross, Civil Work, Performing Arts',
            ],
            //2
            [
                'name' => 'Riman Hamzeh Katbeh',
                'email' => 'remankat@gmail.com',
                'phone' => '0947772493',
                'preferred_help' => 'Events, Team Coordination, Photography, Cinema Gathering',
            ],
            //3
            [
                'name' => 'Naseem Khalil',
                'email' => 'nasemkhalel401@gmail.com',
                'phone' => '0953408978',
                'preferred_help' => 'Cinema Scene, Acting Facilitator, Theater and Directing, Jaramana Gathering',
            ],
            //4
            [
                'name' => 'Naamah Matrak Hamsho',
                'email' => 'naameh69@gmail.com',
                'phone' => '0997200642',
                'preferred_help' => 'Cinema, Reading Lab, Events, SOS Association Sessions, Family Planning, Psychological Support',
            ],
            //5
            [
                'name' => 'Firas Hamadeh Al-Assaf',
                'email' => 'ferasassaf5@gmail.com',
                'phone' => '0964992019',
                'preferred_help' => 'Cinema, Reading Lab, Events, Entrepreneurship, Tourism College Conferences Organization',
            ],
            //6
            [
                'name' => 'Aton Azzam Al-Jundi',
                'email' => 'atoonaljndi1@gmail.com',
                'phone' => '0930752596',
                'preferred_help' => 'Architecture, Graphic Design, Visual Materials, Event Logistics, Engineering Grouping',
            ],
            //7
            [
                'name' => 'Hammam Wael Al-Matni',
                'email' => 'humammatni@gmail.com',
                'phone' => '0954231862',
                'preferred_help' => 'Cinema, Events, Copywriting, Social Media Management, War-affected Support, Medical Grouping',
            ],
            //8
            [
                'name' => 'Anas Youssef Youssef',
                'email' => 'anassyousef76@gmail.com',
                'phone' => '0938948070',
                'preferred_help' => 'Music, Civil Events, Health Campaigns, Mobile Medical Team with Red Cross, Oxfam Field Support',
            ],
            //9
            [
                'name' => 'Mohammad Mounir Mamo',
                'email' => 'mamo32768@gmail.com',
                'phone' => '0994362122',
                'preferred_help' => 'AI, Excel, Dialogue Session Facilitation, Data Collection, Medical Files, Food Baskets Distribution',
            ],
            //10-email!
            [
                'name' => 'Ayoub Bassam Al-Jaramani',
                'email' => 'aljramane.ayoub@gmail.com',
                'phone' => '0938268930',
                'preferred_help' => 'Educational Support, Vocal Training, Academic Tutoring, Educational Grouping',
            ],
            //11
            [
                'name' => 'Hadi Walid Shalash',
                'email' => 'hadishalsh3@gmail.com',
                'phone' => '0968242964',
                'preferred_help' => 'Monitoring & Evaluation, Media, Project Coordination, JCI Local Manager, Children Psychological Support',
            ],
            //12
            [
                'name' => 'Majd Shadi Al-Laham',
                'email' => 'majodallaham@gmail.com',
                'phone' => '0992263353',
                'preferred_help' => 'Theater Trainer in Womadat Association, Performing Arts',
            ],
            //13
            [
                'name' => 'Faris Wahbeh Al-Sabbagh',
                'email' => 'alsabbaghxfares@gmail.com',
                'phone' => '0947260440',
                'preferred_help' => 'Visual Arts, Creative Artistic Initiatives, Singing, Oriental Percussion, Syrian Scout Alum',
            ],
            //14
            [
                'name' => 'Yumna Ghassan Al-Dakkak',
                'email' => '7youmnashidakkak@gmail.com',
                'phone' => '0962431021',
                'preferred_help' => 'Psychological Support, Reading Lab, Events, Children Activities Organization',
            ],
            //15
            [
                'name' => 'Diya Hisham Monther',
                'email' => 'deyaamonther98@gmail.com',
                'phone' => '0962352319',
                'preferred_help' => 'Cinema Scene, Reading Lab, Events, Team and Individual Work, AI and Excel, Dialogue Session Facilitation, Psychological and Community Issues, Data Collection, Medical Files, Food Baskets Distribution, Material Need Assessment, Entrepreneurship Gathering',
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
