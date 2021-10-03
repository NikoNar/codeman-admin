<?php
namespace Codeman\Admin\Database\Seeds; 

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Avatar;
use Illuminate\Support\Str;
use Codeman\Admin\Models\User;
use Spatie\Permission\Models\Permission;

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
        // $profile_pic_filename = Str::random(32).'.png';
        // $profile_pic = Avatar::create('Super Admin')->save(public_path().'/images/users/'.$profile_pic_filename);

        DB::table('languages')->insert([
            [
                'name' =>'English',
                'code' => 'en',
                'script' => 'Latn',
                'native' => 'English',
                'regional' => 'en_EN',
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
            'remember_token' => \Illuminate\Support\Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' =>'Admin',
            'email' => 'admin@codeman.am',
            'profile_pic' => 'aa.png',
            'email_verified_at' => now(),
            'password' => bcrypt('nimda123'),
            'remember_token' =>\Illuminate\Support\Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Permission::create(['name' => 'create-page']);
        Permission::create(['name' => 'edit-page']);
        Permission::create(['name' => 'delete-page']);


        if(null == \Spatie\Permission\Models\Role::where('name', 'SuperAdmin')->first()){
            $role = \Spatie\Permission\Models\Role::create(['name' => 'SuperAdmin']);
        }

        if(null == \Spatie\Permission\Models\Role::where('name', 'Admin')->first()){
            $role = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
        }

        if(null == \Spatie\Permission\Models\Role::where('name', 'Regular')->first()){
            $role = \Spatie\Permission\Models\Role::create(['name' => 'Regular']);
        }

        if(null == \Spatie\Permission\Models\Role::where('name', 'Customer')->first()){
            $role = \Spatie\Permission\Models\Role::create(['name' => 'Customer']);
        }

        $superadmin = User::whereEmail('superadmin@codeman.am')->first();
        $admin = User::whereEmail('admin@codeman.am')->first();
        $superadmin->assignRole('SuperAdmin');
        $admin->assignRole('Admin');

        // DB::table('user_role')->insert([
        //     'user_id' => $user_id,
        //     'role_id' => $role_id,
        // ]);
    }
}

       
    
       