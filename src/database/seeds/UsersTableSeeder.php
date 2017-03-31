<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Luodaan opettaja
        factory('App\User')->create([
            'name' => 'Opettaja 1',
            'email' => 'opettaja1@example.com',
            'password' => bcrypt('salasana')
        ])->roles()->attach(Role::find(2)); // Lisätään opettajan rooli

        // Luodaan pari opiskelijaa
        factory('App\User', 2)->create()->each(function ($u) {
            $u->studentId = rand(100000, 999999);   // Arvo opiskelijanumero
            $subjects = [
                'Tietojenkäsittelytiede',
                'Matematiikka ja tilastotiede',
                'Informaatiotutkimus ja interaktiivinen media',
                'Filosofia',
                'Historia',
                'Logopedia',
                'Psykologia',
                'Sosiaalityö',
                'Yhteiskuntatutkimus',
                'Hallintotieteet',
                'Kauppatieteet',
                'Kasvatustieteet'
            ];
            $u->major = $subjects[array_rand($subjects)];   // Arvo pääaine
            $u->roles()->attach(Role::find(3)); // Lisätään opiskelijan rooli
            $u->save();
        });

        // Luodaan admin
        factory('App\User')->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('salasana')
        ])->roles()->attach(Role::first()); // Lisätään pääkäyttäjän rooli
    }
}
