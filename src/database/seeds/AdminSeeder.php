<?php
namespace Codeman\Admin\Database\Seeds; 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Avatar;
use Illuminate\Support\Str;
use Codeman\Admin\Models\User;
use Codeman\Admin\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        // $this->call(UsersTableSeeder::class);
//        $profile_pic_filename = Str::random(32).'.png';
//        $profile_pic = Avatar::create('Super Admin')->save(public_path().'/images/users/'.$profile_pic_filename);

        DB::table('languages')->insert([
            [
                'name' =>'English',
                'code' => 'en',
                'script' => 'Latn',
                'native' => 'English',
                'regional' => 'en_GB',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' =>'Armenian',
                'code' => 'hy',
                'script' => 'Armn',
                'native' => 'Հայերեն',
                'regional' => 'hy_AM',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);


        DB::table('users')->insert([
            'name' =>'Super Admin',
            'email' => 'superadmin@codeman.am',
            'profile_pic' => 'sa.png',
            'email_verified_at' => now(),
            'password' => bcrypt('nimda_@26'),
            'remember_token' => str_random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $role = Role::create([
            'title' =>'SuperAdmin',
            'description' => 'Has full access to any resource',
        ]);


        $user = User::whereEmail('superadmin@codeman.am')->first();
        $user->roles()->attach($role);

        // DB::table('user_role')->insert([
        //     'user_id' => $user_id,
        //     'role_id' => $role_id,
        // ]);
    }
}

       
    
       