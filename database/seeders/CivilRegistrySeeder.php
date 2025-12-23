<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CivilRegistrySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('civil_registry')->insert([
            [
                'national_id' => '9912345678',
                'full_name' => 'محمد أحمد يوسف العلي',
                'first_name' => 'محمد',
                'father_name' => 'أحمد',
                'mother_name' => 'فاطمة',
                'grandfather_name' => 'يوسف',
                'family_name' => 'العلي',
                'birth_date' => '1990-05-12',
                'age' => 35,
                'gender' => 'ذكر',
                'marital_status' => 'متزوج',
                'religion' => 'مسلم',
                'nationality' => 'أردني',
                'place_of_birth' => 'عمان',
                'current_address' => 'ماركا الشمالية عمان',
                'phone_number' => '0791234567',
                'email' => 'mohammad.alali@example.com',
                'occupation' => 'مهندس مدني',
                'education_level' => 'بكالوريوس هندسة',
                'civil_record_number' => 'CR-2025-00123',
                'record_location' => 'دائرة أحوال عمان',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'national_id' => '9923456789',
                'full_name' => 'سارة خالد محمود الزعبي',
                'first_name' => 'سارة',
                'father_name' => 'خالد',
                'mother_name' => 'ليلى',
                'grandfather_name' => 'محمود',
                'family_name' => 'الزعبي',
                'birth_date' => '1987-11-03',
                'age' => 38,
                'gender' => 'أنثى',
                'marital_status' => 'أرملة',
                'religion' => 'مسلمة',
                'nationality' => 'أردنية',
                'place_of_birth' => 'إربد',
                'current_address' => 'شارع الجامعة إربد',
                'phone_number' => '0787654321',
                'email' => 'sara.zoubi@example.com',
                'occupation' => 'معلمة لغة عربية',
                'education_level' => 'ماجستير أدب عربي',
                'civil_record_number' => 'CR-2025-00456',
                'record_location' => 'دائرة أحوال إربد',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'national_id' => '9934567890',
                'full_name' => 'أحمد سامي عبد الله الطراونة',
                'first_name' => 'أحمد',
                'father_name' => 'سامي',
                'mother_name' => 'نوال',
                'grandfather_name' => 'عبد الله',
                'family_name' => 'الطراونة',
                'birth_date' => '1982-02-28',
                'age' => 43,
                'gender' => 'ذكر',
                'marital_status' => 'متزوج',
                'religion' => 'مسلم',
                'nationality' => 'أردني',
                'place_of_birth' => 'الكرك',
                'current_address' => 'ضاحية المرج الكرك',
                'phone_number' => '0776543210',
                'email' => 'ahmad.tarawneh@example.com',
                'occupation' => 'محامي شرعي',
                'education_level' => 'ليسانس شريعة وقانون',
                'civil_record_number' => 'CR-2025-00789',
                'record_location' => 'دائرة أحوال الكرك',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}