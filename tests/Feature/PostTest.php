<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $test = factory(User::class)->states('test')->create();

        $post = factory(Post::class)->create(['author_id' => $test->id]);
        factory(Post::class, 2)->create();
        factory(Comment::class, 3)->create(['post_id' => $post->id]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Les derniers articles')
            ->assertSee(e($post->content))
            ->assertSee(e($post->title))
            ->assertSee(humanize_date($post->posted_at))
            ->assertSee('3')
            ->assertSee('test');
    }

    public function testSearch()
    {
        factory(Post::class, 3)->create();
        $post = factory(Post::class)->create(['title' => 'Hello Obiwan']);

        $this->get('/?q=Hello')
            ->assertStatus(200)
            ->assertSee('1 article trouvé')
            ->assertSee(e($post->content))
            ->assertSee(e($post->title))
            ->assertSee(humanize_date($post->posted_at));
    }

    public function testShow()
    {
        $post = factory(Post::class)->create();
        factory(Comment::class, 2)->create(['post_id' => $post->id]);
        factory(Comment::class)->create(['post_id' => $post->id]);

        $this->actingAsUser()
            ->get("/posts/{$post->slug}")
            ->assertStatus(200)
            ->assertSee(e($post->content))
            ->assertSee(e($post->title))
            ->assertSee(humanize_date($post->posted_at))
            ->assertSee('3 commentaires')
            ->assertSee('Commenter');
    }

    public function testShowUnauthenticated()
    {
        $post = factory(Post::class)->create();

        $this->get("/posts/{$post->slug}")
            ->assertStatus(200)
            ->assertSee('Vous devez vous connecter pour commenter.');
    }
}
