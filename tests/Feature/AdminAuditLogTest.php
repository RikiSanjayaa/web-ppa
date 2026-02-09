<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_audit_logs_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('admin.audit-logs.index'));

        $response->assertOk();
    }

    public function test_non_admin_is_forbidden_from_audit_logs_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get(route('admin.audit-logs.index'));

        $response->assertForbidden();
    }

    public function test_admin_page_access_is_logged_automatically(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();

        $latestLog = ActivityLog::query()->latest()->first();

        $this->assertNotNull($latestLog);
        $this->assertSame('admin.page_accessed', $latestLog->action);
        $this->assertSame($admin->id, $latestLog->user_id);
        $this->assertSame('admin.dashboard', $latestLog->properties['route_name'] ?? null);
        $this->assertSame('GET', $latestLog->properties['method'] ?? null);
    }
}
