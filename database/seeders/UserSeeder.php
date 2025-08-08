<?php

namespace Database\Seeders;

use App\Enums\UserTypes;
use App\Models\User;
use Database\Factories\UserChildFactory;
use Database\Factories\UserParentFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Keyhanweb\Subsystem\Enums\UserStatus;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->data() as $data) {
            $child = [];
            if (!empty($data['child'])) {
                $child = $data['child'];
            }
            unset($data['child']);

            $parent = User::where('mobile', $data['mobile'])
                ->exists();
            if (!$parent) {
                $user = User::create($data);

                if ($child) {
                    User::create(
                        $child + [
                            'parentID' => $user->ID,
                        ]
                    );
                }
            }
        }


        for ($i = 0; $i < 10; $i++) {
            $parent = User::factory()->make(UserParentFactory::new()->definition())->toArray();
            $child = User::factory()->make(UserChildFactory::new()->definition())->toArray();

            User::updateOrCreate($parent);
            User::updateOrCreate($child);
        }
    }

    public function data(): array
    {
        return [
            [
                'name' => 'Amir',
                'family' => 'Doe',
                'password' => Hash::make('1234'),
                'countryCode' => 98,
                'mobile' => '+989360000000',
                'gender' => 'male',
                'nationalCode' => '1234567890',
                'birthDate' => 631152000, // example timestamp for 1990-01-01
//                'avatarSID' => 'dA000000-0000-0000-0000-00000avatar1',
                'status' => UserStatus::Active->value,
                'username' => 'amir',
                'registerDate' => time(),
                'lastActivity' => time(),
//                'child' => [
//                    'name' => 'child',
//                    'gender' => 'male',
//                    'nationalCode' => '1234567890',
//                    'birthDate' => 631152000, // example timestamp for 1990-01-01
//                    'avatarSID' => 'dA000000-0000-0000-0000-00000avatar1',
//                    'status' => UserStatus::Active->value,
//                    'username' => 'amirChild',
//                    'type' => UserTypes::Child->value,
//                    'registerDate' => time(),
//                    'lastActivity' => time(),
//                ],
            ],
            [
                'name' => 'Amir',
                'family' => 'jp',
                'password' => Hash::make('1234'),
                'countryCode' => 98,
                'mobile' => '+989390784088',
                'gender' => 'male',
                'nationalCode' => '1234567890',
                'birthDate' => 631152000, // example timestamp for 1990-01-01
//                'avatarSID' => 'dA000000-0000-0000-0000-00000avatar1',
                'status' => UserStatus::Active->value,
                'username' => 'amir',
                'registerDate' => time(),
                'lastActivity' => time(),
//                'child' => [
//                    'name' => 'child',
//                    'gender' => 'male',
//                    'nationalCode' => '1234567890',
//                    'birthDate' => 631152000, // example timestamp for 1990-01-01
//                    'avatarSID' => 'dA000000-0000-0000-0000-00000avatar1',
//                    'status' => UserStatus::Active->value,
//                    'username' => 'amirChild',
//                    'type' => UserTypes::Child->value,
//                    'registerDate' => time(),
//                    'lastActivity' => time(),
//                ],
            ],
        ];
    }
}
