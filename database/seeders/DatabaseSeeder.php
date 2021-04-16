<?php

namespace Database\Seeders;

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
        $this->call(GenreSeeder::class);
        \App\Models\User::factory(20)->create();
        \App\Models\User::factory()->create([
            'name' => 'Kieran Lacey',
            'email' => 'kieran.lacey@example.com',
            'password' => 'password',
            'is_admin' => true
         ]);
        \App\Models\Movie::factory(100)->create();
        \App\Models\Review::factory(300)->create();
        \App\Models\Celeb::factory(100)->create();
        \App\Models\Watchlist::factory(15)->create();
        \App\Models\CelebMovie::factory(200)->create();
        \App\Models\GenreMovie::factory(200)->create();
        \App\Models\MovieWatchlist::factory(100)->create();
    }
}
