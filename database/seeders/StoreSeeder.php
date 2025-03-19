<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\UserProfile;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fashionStore = Store::factory(1)
                        ->hasAttached (
                            User::factory()->count(2)
                                ->has(UserProfile::factory(1))
                                ->create()
                                ->each (
                                    function ($user) {
                                        $user->assignRole ('store-admin');
                                    }
                                )
                            );

        User::factory()->count(1)
            ->has (UserProfile::factory(1))
            ->has ($fashionStore)
            ->create()
            ->each (
                function ($user) {
                    $user->assignRole('store-owner');
                }
            );

        $luxuryPhoneStore = Store::factory(1)
                                ->hasAttached (
                                    User::factory()->count(2)
                                        ->has(UserProfile::factory(1))
                                        ->create()
                                        ->each (
                                            function ($user) {
                                                $user->assignRole ('store-admin');
                                            }
                                        )
                                    );
        $budgetPhoneStore = Store::factory(1)
                                ->hasAttached (
                                    User::factory()->count(2)
                                        ->has(UserProfile::factory(1))
                                        ->create()
                                        ->each (
                                            function ($user) {
                                                $user->assignRole ('store-admin');
                                            }
                                        )
                                    );

        User::factory()->count(1)
            ->has (UserProfile::factory(1))
            ->has ($luxuryPhoneStore)
            ->has ($budgetPhoneStore)
            ->create()
            ->each (
                function ($user) {
                    $user->assignRole('store-owner');
                }
            );
    }
}
