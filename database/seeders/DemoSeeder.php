<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0910000001',
        ]);

        $supervisor = User::create([
            'name' => 'مشرف الصيانة',
            'email' => 'supervisor@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'phone' => '0910000002',
        ]);

        $employee = User::create([
            'name' => 'موظف الأشعة',
            'email' => 'employee@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'phone' => '0910000003',
        ]);

        // 2. Create Equipment Types
        $types = [
            ['name' => 'جهاز أشعة مقطعية (CT Scan)', 'icon' => 'fa-x-ray'],
            ['name' => 'جهاز رنين مغناطيسي (MRI)', 'icon' => 'fa-magnet'],
            ['name' => 'جهاز تخطيط القلب (ECG)', 'icon' => 'fa-heart-pulse'],
            ['name' => 'جهاز تنفس صناعي', 'icon' => 'fa-lungs'],
            ['name' => 'سرير طبي كهربائي', 'icon' => 'fa-bed-pulse'],
        ];

        foreach ($types as $type) {
            EquipmentType::create($type);
        }

        // 3. Create Equipment
        $typeIds = EquipmentType::pluck('id')->toArray();

        Equipment::create([
            'name' => 'جهاز أشعة مقطعية سيمنز',
            'serial_number' => 'CT-SIEM-2023-001',
            'equipment_type_id' => $typeIds[0],
            'department' => 'قسم الأشعة والتصوير',
            'location' => 'الغرفة 101 - الطابق الأول',
            'status' => 'active',
            'purchase_date' => '2023-05-10',
            'warranty_expiry' => '2026-05-10',
            'qr_code' => Str::uuid()->toString(),
            'created_by' => $admin->id,
        ]);

        Equipment::create([
            'name' => 'جهاز رنين مغناطيسي فيليبس',
            'serial_number' => 'MRI-PHIL-2022-045',
            'equipment_type_id' => $typeIds[1],
            'department' => 'قسم الأشعة والتصوير',
            'location' => 'الغرفة 102 - الطابق الأول',
            'status' => 'active',
            'purchase_date' => '2022-11-20',
            'qr_code' => Str::uuid()->toString(),
            'created_by' => $admin->id,
        ]);
        
        Equipment::create([
            'name' => 'جهاز تخطيط قلب متنقل',
            'serial_number' => 'ECG-MOB-9932',
            'equipment_type_id' => $typeIds[2],
            'department' => 'قسم الطوارئ',
            'location' => 'غرفة الطوارئ 3',
            'status' => 'maintenance',
            'qr_code' => Str::uuid()->toString(),
            'created_by' => $admin->id,
        ]);
    }
}
