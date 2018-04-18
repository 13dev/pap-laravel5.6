<?php

namespace Tests\Feature\Admin;

use App\Post;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $test = factory(User::class)->states('test')->create();
        factory(User::class, 3)->create();
        factory(Post::class, 3)->create(['author_id' => $test->id]);

        $this->actingAsAdmin()
            ->get('/admin/users')
            ->assertStatus(200)
            ->assertSee('5 utilisateurs')
            ->assertSee('3')
            ->assertSee('test@skywalker.st')
            ->assertSee('test')
            ->assertSee('Nom')
            ->assertSee('Email')
            ->assertSee('Enregistré le');
    }

    public function testEdit()
    {
        $test = factory(User::class)->states('test')->create();

        $this->actingAsAdmin()
            ->get("/admin/users/{$test->id}/edit")
            ->assertStatus(200)
            ->assertSee('test')
            ->assertSee('Voir le profil')
            ->assertSee('test@skywalker.st')
            ->assertSee('Confirmation du mot de passe')
            ->assertSee('R&ocirc;les')
            ->assertSee('Mettre à jour')
            ->assertSee('Administrateur');
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $params = $this->validParams();

        $this->actingAsAdmin()
            ->patch("/admin/users/{$user->id}", $params)
            ->assertRedirect("/admin/users/{$user->id}/edit");

        $user->refresh();
        $this->assertDatabaseHas('users', $params);
        $this->assertEquals($params['email'], $user->email);
    }

    public function testUpdateRoles()
    {
        $user = factory(User::class)->create();

        $role_editor = factory(Role::class)->states('editor')->create();
        $params = $this->validParams(['roles' => ['editor' => $role_editor->id]]);

        $this->actingAsAdmin()
            ->patch("/admin/users/{$user->id}", $params)
            ->assertRedirect("/admin/users/{$user->id}/edit");

        $user->refresh();
        $this->assertTrue($user->roles->pluck('id')->contains($role_editor->id));
    }

    /**
     * Valid params for updating or creating a resource
     *
     * @param  array  $overrides new params
     * @return array  Valid params for updating or creating a resource
     */
    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'test',
            'email' => 'test@skywalker.st',
        ], $overrides);
    }
}
