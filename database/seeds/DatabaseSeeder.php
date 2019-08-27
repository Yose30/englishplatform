<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //3 Roles, administrator, teacher y student
        \DB::table('roles')->insert([
            0 => [
                'id'    => 1,
                'role'  => 'administrator'
            ],
            1 => [
                'id'    => 2,
                'role'  => 'teacher'
            ],
            2 => [
                'id'    => 3,
                'role'  => 'student'
            ],
        ]);

        \DB::table('users')->insert([
            0 => [
                'id'    => 1,
                'role_id' => 1,
                'name' => 'Ana',
                'last_name' => 'Iturbe',
                'email' => 'ana@gmail.com',
                'email_verified_at' => now(),
                'telephone' => '5596458896',
                'username' => 'ana-4568',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10)
            ],
        ]);

        \DB::table('administrators')->insert([
            0 => [
                'id'    => 1,
                'user_id' => 1,
            ],
        ]);

        $users = factory(App\User::class, 20)->create();

        foreach($users as $user){
            factory(App\Teacher::class, 1)->create(['user_id' => $user->id]);
        }

        // $groups = factory(App\Group::class, 5)->create();

        $lessons = factory(App\Lesson::class, 40)->create();
    }
}
