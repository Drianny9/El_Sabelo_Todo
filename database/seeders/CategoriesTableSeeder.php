<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        //Borra lo que hay y reinicia autoincrement
        DB::table('categories')->delete();

        $now = now();
        
        DB::table('categories')->insert([
            ['name' => 'Ciencia', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Historia', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Geografía', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Deportes', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arte', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Entretenimiento', 'created_at' => $now, 'updated_at' => $now],
        ]);
        
        
    }
}