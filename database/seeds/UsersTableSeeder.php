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
        factory(App\User::class, 50)->create()->each(function($u){
            for ($i = 0; $i < mt_rand(0,5); ++$i)
            {
                $u->objects()->save(factory(App\Object::class)->make());
            }
        });
    }
}
