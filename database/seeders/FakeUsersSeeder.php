<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FakeUsersSeeder extends Seeder
{
    public function run()
    {
        $genders = ['Male', 'Female'];
        $isActiveStates = [0, 1, 2];
        $isOldStates = [0, 1];
        $roles = [1, 2]; // Lawyer or Client
        $countryIds = range(1, 30);
        $password = bcrypt('01001802203'); // نفس الباسورد الثابت مشفر

        for ($i = 1; $i <= 100; $i++) {
            $firstName = 'FirstName'.$i;
            $latestName = 'LastName'.$i;
            $secondName = 'SecondName'.$i;
            $thirdName = 'ThirdName'.$i;
            $fourthName = 'FourthName'.$i;
            $fullName = "$firstName $secondName $thirdName $fourthName $latestName";

            $gender = $genders[array_rand($genders)];
            $isActive = $isActiveStates[array_rand($isActiveStates)];
            $isOld = $isOldStates[array_rand($isOldStates)];
            $role = $roles[array_rand($roles)];
            $country = $countryIds[array_rand($countryIds)];

            $mobileCountryCode = '+20'; // مثال لكود مصر
            $mobileNumber = '1'.str_pad(mt_rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);

            $email = "user{$i}@example.com";

            User::create([
                'first_name' => $firstName,
                'latest_name' => $latestName,
                'second_name' => $secondName,
                'third_name' => $thirdName,
                'fourth_name' => $fourthName,
                'full_name' => $fullName,
                'country' => $country,
                'gender' => $gender,
                'is_active' => $isActive,
                'is_old' => $isOld,
                'password' => $password,
                'role' => $role,
                'email' => $email,
                'mobile_country_code' => $mobileCountryCode,
                'mobile_number' => $mobileNumber,
            ]);
        }
    }
}
