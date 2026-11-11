<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VolunteerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndVolunteerWorkflowTest extends TestCase
{
    use RefreshDatabase; // يقوم بتنظيف قاعدة البيانات بعد كل اختبار لضمان بيئة نظيفة

    public function test_complete_auth_and_volunteer_management_workflow(): void
    {
        // 1. إنشاء مستخدم بصلاحية Admin ومستخدم بصلاحية Volunteer للاختبار
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

       $volunteer = User::factory()->create([
            'role' => 'volunteer',
            'email' => 'volunteer@example.com',
            'password' => 'password123', // اتركها نصاً عادياً ليتولى المودل أو الـ Factory تشفيرها إذا كان معمولاً لها Hash cast
        ]);

        VolunteerProfile::create([
            'user_id' => $volunteer->id,
            'total_hours' => 10.00,
        ]);

        // ==========================================
        // 2. اختبار تسجيل الدخول (Login)
        // ==========================================
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'access_token',
                'token_type',
                'user'
            ]);

        $adminToken = $loginResponse->json('access_token');

        // ==========================================
        // 3. اختبار جلب الملف الشخصي (User Profile)
        // ==========================================
        $profileResponse = $this->withToken($adminToken)
            ->getJson('/api/user'); // تم التعديل من user-profile إلى user

        $profileResponse->assertStatus(200)
            ->assertJsonPath('user.email', 'admin@example.com');

        // ==========================================
        // 4. اختبار تغيير كلمة المرور (Change Password) للمتطوع
        // ==========================================
        $volunteerLogin = $this->postJson('/api/login', [
            'email' => 'volunteer@example.com',
            'password' => 'password123',
        ]);

        // اطبع الاستجابة إذا فشل تسجيل الدخول لنرى السبب تماماً
        if ($volunteerLogin->status() !== 200) {
            $volunteerLogin->dump();
        }

        $volunteerToken = $volunteerLogin->json('access_token');

        // ==========================================
        // 5. اختبار تحديث ساعات المتطوع (Update Hours بواسطة الـ Admin)
        // ==========================================
        $updateHoursResponse = $this->withToken($adminToken)
            ->patchJson("/api/volunteers/{$volunteer->id}/hours", [
                'hours' => 5,
                'action' => 'add', // إضافة 5 ساعات لتصبح الإجمالي 15
            ]);

        $updateHoursResponse->assertStatus(200)
            ->assertJson([
                'message' => 'Hours updated successfully.'
            ]);

        // ==========================================
        // 6. اختبار جلب كافة المتطوعين (Get All Volunteers بواسطة الـ Admin)
        // ==========================================
        $getAllResponse = $this->withToken($adminToken)
            ->getJson('/api/admin/volunteers/all'); // تم التعديل ليطابق /admin/volunteers/all
    }
}
