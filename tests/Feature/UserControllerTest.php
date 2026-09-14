<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Super Admin',
            'email' => 'admin_'.uniqid().'@parish.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ], $overrides));
    }

    public function test_cannot_demote_last_active_super_admin_account(): void
    {
        $onlySuperAdmin = $this->admin(['email' => 'super1@parish.test']);

        $this->actingAs($onlySuperAdmin)
            ->put(route('admin.users.update', $onlySuperAdmin), [
                'name' => 'Super One',
                'email' => $onlySuperAdmin->email,
                'role' => 'staff',
                'is_active' => '1',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $onlySuperAdmin->id,
            'role' => 'super_admin',
        ]);
    }

    public function test_cannot_deactivate_last_active_super_admin_account(): void
    {
        $onlySuperAdmin = $this->admin(['email' => 'super1@parish.test']);

        $this->actingAs($onlySuperAdmin)
            ->put(route('admin.users.update', $onlySuperAdmin), [
                'name' => 'Super One',
                'email' => $onlySuperAdmin->email,
                'role' => 'super_admin',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $onlySuperAdmin->id,
            'is_active' => true,
        ]);
    }

    public function test_can_change_a_super_admin_when_another_one_remains_active(): void
    {
        $acting = $this->admin(['email' => 'superA@parish.test']);
        $target = $this->admin(['email' => 'superB@parish.test']);

        $this->actingAs($acting)
            ->put(route('admin.users.update', $target), [
                'name' => 'Super B',
                'email' => $target->email,
                'role' => 'staff',
                'is_active' => '0',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'role' => 'staff',
            'is_active' => false,
        ]);
    }

    public function test_can_deactivate_another_super_admin_when_one_remains_active(): void
    {
        $acting = $this->admin(['email' => 'superA@parish.test']);
        $other = $this->admin(['email' => 'superB@parish.test']);
        $target = $this->admin(['email' => 'superC@parish.test']);

        $this->actingAs($acting)
            ->delete(route('admin.users.destroy', $target))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'is_active' => false,
        ]);
    }

    public function test_cannot_delete_or_deactivate_own_account(): void
    {
        $acting = $this->admin(['email' => 'super1@parish.test']);
        $this->admin(['email' => 'super2@parish.test']);

        $this->actingAs($acting)
            ->delete(route('admin.users.destroy', $acting))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $acting->id,
            'is_active' => true,
        ]);
    }
}
