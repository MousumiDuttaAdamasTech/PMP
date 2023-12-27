<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = ['jpeg', 'jpg', 'png', 'pdf', 'svg', 'doc', 'docx', 'xls', 'xlsx', 'txt'];

        foreach ($documentTypes as $type) {
            DB::table('doctypes')->insert([
                'doc_type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
