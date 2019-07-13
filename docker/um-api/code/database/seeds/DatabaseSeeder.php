<?php

use App\Group;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedAdmin();

        factory(\App\User::class, 10)->create();
        factory(\App\Group::class, 10)->create();
    }

    private function seedAdmin(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('user_groups')->truncate();
        DB::table('groups')->truncate();
        DB::table('users')->truncate();


        $role = Group::create(['name' => 'super_admin']);
        $user = User::create(
            [
                'email' => 'superadmin@admin.com',
                'password' => 'password@123',
                'name' => 'Administrator'
            ]
        );

        $user->group()->sync($role->id);

        Schema::enableForeignKeyConstraints();
        return;
    }
}
