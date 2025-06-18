<?php

namespace Database\Seeders;

use App\Models\TypeMembership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeMembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeMembership::factory()->count(50)->create();
    }
}
