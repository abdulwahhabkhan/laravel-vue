<?php

use App\Models\Auth\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create(['email'=>'abdulwahhabkhan@webequator.com', 'password' => Hash::make('testlaravue')]);
        factory(User::class, 10)->create();
    }
}
