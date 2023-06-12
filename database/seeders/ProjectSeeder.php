<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $newProject = new Project();
            $titleWords = $faker->words($nb = 4, $asText = true);
            $newProject->title = $titleWords;
            $newProject->image = $faker->imageUrl($width = 640, $height = 480);
            $newProject->description = $faker->paragraph;
            $newProject->slug = Str::slug($newProject->title, '-');
            $newProject->save();
        }
    }
}
