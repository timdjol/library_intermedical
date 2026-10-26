<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_sends_security_headers(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_registration_keeps_a_usable_password_and_user_role(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'reader@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/dashboard');

        $user = User::where('email', 'reader@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('user', $user->role);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_regular_user_cannot_open_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_agent_cannot_edit_another_agents_book(): void
    {
        $owner = User::factory()->create(['role' => 'agent']);
        $intruder = User::factory()->create(['role' => 'agent']);
        $category = Category::create(['title' => 'Хирургия', 'code' => 'surgery', 'parent_id' => 0]);
        $post = Post::create([
            'title' => 'Анатомия',
            'code' => 'anatomy',
            'category_id' => $category->id,
            'user_id' => $owner->id,
            'description' => '<p>Текст книги</p>',
            'status' => 1,
            'image' => '',
        ]);

        $this->actingAs($intruder)
            ->get(route('agent-posts.edit', $post))
            ->assertForbidden();
    }

    public function test_book_description_strips_scripts(): void
    {
        $post = Post::create([
            'title' => 'Фармакология',
            'code' => 'pharma',
            'description' => '<p onclick="alert(1)">Текст</p><script>alert(1)</script>',
            'status' => 1,
            'image' => '',
        ]);

        $this->assertStringNotContainsString('<script>', $post->description);
        $this->assertStringNotContainsString('onclick', $post->description);
        $this->assertStringContainsString('<p>Текст</p>', $post->description);
    }

    public function test_guest_cannot_read_a_document_and_agent_cannot_download_a_locked_one(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('documents/protocol.pdf', 'protocol');

        $agent = User::factory()->create(['role' => 'agent']);
        $document = Document::create([
            'title' => 'Протокол',
            'code' => 'protocol',
            'user_id' => $agent->id,
            'path' => 'documents/protocol.pdf',
            'access' => 0,
            'status' => 1,
        ]);

        $this->get(route('documents.file', $document))
            ->assertRedirect(route('login'));

        $this->actingAs($agent)
            ->get(route('documents.file', $document))
            ->assertOk();

        $this->actingAs($agent)
            ->get(route('documents.file', ['document' => $document, 'download' => 1]))
            ->assertForbidden();
    }

    public function test_admin_screens_render_on_php_84(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin-users.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin-events.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin-categories.create'))->assertOk();
    }

    public function test_user_edit_does_not_expose_or_replace_the_password_hash(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $agent = User::factory()->create(['role' => 'agent']);
        $original = $agent->password;

        $this->actingAs($admin)
            ->get(route('admin-users.edit', $agent))
            ->assertOk()
            ->assertDontSee($original, false);

        $this->actingAs($admin)
            ->put(route('admin-users.update', $agent), [
                'name' => 'Новое имя',
                'email' => $agent->email,
                'role' => 'agent',
                'password' => '',
            ])
            ->assertRedirect(route('admin-users.index'));

        $agent->refresh();
        $this->assertSame('Новое имя', $agent->name);
        $this->assertTrue(Hash::check('password', $agent->password));
    }
}
