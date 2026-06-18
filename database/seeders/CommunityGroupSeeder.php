<?php

namespace Database\Seeders;

use App\Models\CommunityGroup;
use Illuminate\Database\Seeder;

class CommunityGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Zwangerschap', 'members' => 248, 'color_from' => '#F9C8D0', 'color_to' => '#F7B3C0'],
            ['name' => 'Eerste jaar', 'members' => 312, 'color_from' => '#B7E1C0', 'color_to' => '#9AD3AC'],
            ['name' => 'Peuters & kleuters', 'members' => 176, 'color_from' => '#CFE3F5', 'color_to' => '#A9CCE8'],
            ['name' => 'Creatief met kids', 'members' => 204, 'color_from' => '#F6DDB0', 'color_to' => '#E9C788'],
            ['name' => 'Mama\'s die ondernemen', 'members' => 98, 'color_from' => '#E6D6F0', 'color_to' => '#D3BCE6'],
        ];

        foreach ($groups as $group) {
            CommunityGroup::updateOrCreate(['name' => $group['name']], $group);
        }
    }
}
