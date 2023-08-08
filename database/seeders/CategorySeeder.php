<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Define the data you want to seed into the category table
         $categories = [
            ['cat_id'=> 1,'name' => 'health'],
            ['cat_id'=> 2,'name' => 'legal'],
            ['cat_id'=> 3,'name' => 'printing'],
            ['cat_id'=> 4,'name' => 'photography'],
            ['cat_id'=> 5,'name' => 'animals'],
            ['cat_id'=> 6,'name' => 'events'],
            ['cat_id'=> 7,'name' => 'manufacturing'],
            ['cat_id'=> 8,'name' => 'cleaning'],
            ['cat_id'=> 9,'name' => 'fitness'],
            ['cat_id'=> 10,'name' => 'automotive'],
            ['cat_id'=> 11,'name' => 'building'],
            ['cat_id'=> 12,'name' => 'chaffeur'],
            ['cat_id'=> 13,'name' => 'childcare'],
            ['cat_id'=> 14,'name' => 'computer'],
            ['cat_id'=> 15,'name' => 'landscaping'],
            ['cat_id'=> 16,'name' => 'other'],
           
        ];

        // Insert the data into the category table
        DB::table('category')->insert($categories);
    }
}

