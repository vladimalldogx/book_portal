<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\usertype;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
            User::create([
                'firstname' => 'Brian',
                'lastname' => 'Oconner',
                'email' => 'meme@xlink.com.ph',
                'email_verified_at' => now(),
                'usertype'=> '1',
                'department'=>'OpEx',
                'password' => Hash::make('1234'),
            ]);
       
        User::create([
            'firstname' => 'Kemberlie',
            'lastname' => 'SAMPLE',
            'email' => 'kemberliesabellano@smaple.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '2',
            'department'=>'OpEx',
            'password' => Hash::make('1234')
        ]);
      
        User::create([
            'firstname' => 'Grace',
            'lastname' => 'Fantonial',
            'email' => 'grace@readersmagnet.com',
            'email_verified_at' => now(),
            'usertype'=> '4',
            'department'=>'SALES',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Grace',
            'lastname' => 'Fantonial',
            'email' => 'gfantonial@gmail.com',
            'email_verified_at' => now(),
            'usertype'=> '1',
            'department'=>'OpEx',
            'password' => Hash::make('1234')
        ]);
       
        User::create([
            'firstname' => 'Ryan',
            'lastname' => 'Vindo',
            'email' => 'hey3x@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '4',
            'department'=>'ARO',
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Willa Mae',
            'lastname' => 'Hiyoca',
            'email' => 'hiyoca@elink.com.ph',
            'email_verified_at' => now(),
            'usertype'=> '4',
            'department'=>'ARO',
            'password' => Hash::make('1234')
        ]);
        usertype::create([
            'usertype' => 'superadmin'       
        ]);
        usertype::create([
            'usertype' => 'admin'       
        ]);
        usertype::create([
            'usertype' => 'manager'       
        ]);
        usertype::create([
            'usertype' => 'reguser'       
        ]);
        Department::create([
            'deptcode' =>'SALES',
            'deptname' =>'SALES',
            
        ]);
        Department::create([
            'deptcode' =>'OpEx',
            'deptname' =>'Operations Excellence',
            
        ]);
        Department::create([
            'deptcode' =>'Publishing',
            'deptname' =>'Publishing Production',
            
        ]);
        Department::create([
            'deptcode' =>'LM',
            'deptname' =>'Lead Management',
            
        ]);
        Department::create([
            'deptcode' =>'ARO',
            'deptname' =>'Auhtor Relation Officer',
            
        ]);

    }
}
