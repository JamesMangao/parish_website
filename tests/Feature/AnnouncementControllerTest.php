<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementControllerTest extends TestCase
{
    use RefreshDatabase;

    private function superAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@parish.test',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_store_strips_html_from_content_to_prevent_stored_xss(): void
    {
        $this->actingAs($this->superAdmin());

        $this->post('/internal/announcements', [
            'title' => 'Weekend Schedule',
            'content' => 'Mass at 6 AM <script>alert("xss")</script> on <b>Sunday</b>.',
            'category' => 'Parish Life',
            'is_published' => '1',
        ])->assertRedirect();

        $content = Announcement::firstOrFail()->content;

        $this->assertStringNotContainsString('<script>', $content);
        $this->assertStringNotContainsString('<b>', $content);
        $this->assertStringContainsString('Mass at 6 AM alert("xss") on Sunday.', $content);
    }

    public function test_public_show_escapes_content(): void
    {
        $announcement = Announcement::create([
            'title' => 'Title',
            'content' => 'Hello <img src=x onerror=alert(1)> world',
            'category' => 'Parish Life',
            'is_published' => true,
        ]);

        $this->get(route('announcements.show', $announcement))
            ->assertOk()
            ->assertSee('Hello &lt;img src=x onerror=alert(1)&gt; world', false);
    }
}
