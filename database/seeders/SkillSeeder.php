<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = ['PHP', 'Laravel', 'Javascript', 'SQL', 'Python', 'node.js', 'Flutter', 'vue.js', 'Solidity', 'AWS'];

        $this->command->info("Creating Skills : \n " . implode(", ", $skills) . "\n");
        foreach( $skills as $skill )
        {
            Skill::create([
                'name' => $skill
            ]);
        }
    }
}
