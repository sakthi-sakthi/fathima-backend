<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\Slug;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['Homeslider','testimonial'];
        foreach($categories as $category){
            $cat = new Category;
            $cat->title = $category;
            $cat->type = $category;
            $cat->parent = $category;
            $cat->media_id = 1;
            $cat->save();
        }
    }
}
