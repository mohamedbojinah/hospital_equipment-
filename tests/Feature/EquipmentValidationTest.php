<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentValidationTest extends TestCase
{
    protected $admin;
    protected $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@hospital.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '0910000999',
            ]
        );

        $this->type = EquipmentType::firstOrCreate(
            ['name' => 'نوع تجريبي للاختبار'],
            ['icon' => 'fa-gear']
        );
    }

    public function test_cannot_create_equipment_with_duplicate_serial_number(): void
    {
        $existing = Equipment::first();
        if (!$existing) {
            $existing = Equipment::create([
                'name' => 'جهاز فحص أولي للمختبر',
                'serial_number' => 'TEST-SN-UNIQUE-001',
                'equipment_type_id' => $this->type->id,
                'department' => 'المختبر',
                'location' => 'غرفة 10',
                'created_by' => $this->admin->id,
            ]);
        }

        $response = $this->actingAs($this->admin)->post(route('equipment.store'), [
            'name' => 'جهاز باسم مختلف جديد تماماً',
            'serial_number' => $existing->serial_number,
            'equipment_type_id' => $this->type->id,
            'department' => 'قسم الطوارئ',
            'location' => 'غرفة 1',
        ]);

        $response->assertSessionHasErrors(['serial_number']);
        $errors = session('errors')->get('serial_number');
        $this->assertStringContainsString('الرقم التسلسلي مسجل مسبقاً', $errors[0]);
    }

    public function test_cannot_create_equipment_with_duplicate_name(): void
    {
        $existing = Equipment::first();

        $response = $this->actingAs($this->admin)->post(route('equipment.store'), [
            'name' => $existing->name,
            'serial_number' => 'BRAND-NEW-SERIAL-' . uniqid(),
            'equipment_type_id' => $this->type->id,
            'department' => 'قسم الأشعة',
            'location' => 'غرفة 5',
        ]);

        $response->assertSessionHasErrors(['name']);
        $errors = session('errors')->get('name');
        $this->assertStringContainsString('اسم الجهاز مسجل مسبقاً', $errors[0]);
    }

    public function test_can_update_equipment_keeping_same_name_and_serial_number(): void
    {
        $existing = Equipment::first();

        $response = $this->actingAs($this->admin)->put(route('equipment.update', $existing), [
            'name' => $existing->name,
            'serial_number' => $existing->serial_number,
            'equipment_type_id' => $existing->equipment_type_id,
            'department' => $existing->department,
            'location' => 'موقع محدث جديد',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('equipment.show', $existing));
        $response->assertSessionHas('success');
    }

    public function test_cannot_update_equipment_to_duplicate_serial_number_of_another(): void
    {
        $all = Equipment::take(2)->get();
        if ($all->count() < 2) {
            $first = Equipment::first();
            $second = Equipment::create([
                'name' => 'جهاز ثان للاختبار ' . uniqid(),
                'serial_number' => 'TEST-SN-' . uniqid(),
                'equipment_type_id' => $this->type->id,
                'department' => 'قسم الطوارئ',
                'location' => 'غرفة 2',
                'created_by' => $this->admin->id,
            ]);
        } else {
            $first = $all[0];
            $second = $all[1];
        }

        $response = $this->actingAs($this->admin)->put(route('equipment.update', $second), [
            'name' => $second->name,
            'serial_number' => $first->serial_number, // محاولة أخذ الرقم التسلسلي للجهاز الأول
            'equipment_type_id' => $second->equipment_type_id,
            'department' => $second->department,
            'location' => $second->location,
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['serial_number']);
        $errors = session('errors')->get('serial_number');
        $this->assertStringContainsString('الرقم التسلسلي مسجل مسبقاً', $errors[0]);
    }

    public function test_cannot_update_equipment_to_duplicate_name_of_another(): void
    {
        $all = Equipment::take(2)->get();
        $first = $all[0];
        $second = $all[1];

        $response = $this->actingAs($this->admin)->put(route('equipment.update', $second), [
            'name' => $first->name, // محاولة أخذ اسم الجهاز الأول
            'serial_number' => $second->serial_number,
            'equipment_type_id' => $second->equipment_type_id,
            'department' => $second->department,
            'location' => $second->location,
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['name']);
        $errors = session('errors')->get('name');
        $this->assertStringContainsString('اسم الجهاز مسجل مسبقاً', $errors[0]);
    }
}
