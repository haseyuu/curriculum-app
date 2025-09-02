<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params=[
            [
                'user_id'=>'root',
                'name'=>'管理者用',
                'email'=>'root@root.com',
                'password'=>'root',
                'authority'=>0,
                'state'=>2,
            ],
            [
                'user_id'=>'user1',
                'name'=>'user1',
                'email'=>'user1@test.com',
                'password'=>'user1test',
                'authority'=>1,
                'state'=>0,
            ],
            [
                'user_id'=>'user2',
                'name'=>'user2',
                'email'=>'user2@test.com',
                'password'=>'user2test',
                'authority'=>1,
                'state'=>1,
            ],
        ];
        foreach($params as $param){
            DB::table('users')->insert($param);
        }
    }
}
