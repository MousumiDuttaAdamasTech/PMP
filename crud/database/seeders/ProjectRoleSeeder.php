<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectRole;

class ProjectRoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'member_role_type' => 'Project Manager',
            ],
            [
                'member_role_type' => 'Product Manager',
            ],
            [
                'member_role_type' => 'Developer',
            ],
            [
                'member_role_type' => 'QA Tester',
            ],
        ];
        ProjectRole::insert($data);
    }
}
