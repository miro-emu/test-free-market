<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
  use RefreshDatabase;

  // 会員登録バリデーション
  public function test_register_requires_name()
  {
    $data = [
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post('/register', $data);

    $response->assertSessionHasErrors([
      'name' => 'お名前を入力してください'
    ]);
  }

  public function test_register_requires_email()
  {
    $data = [
      'name' => 'Test User',
      'password' => 'password',
      'password_confirmation' => 'password',
    ];

    $response = $this->post('/register', $data);

    $response->assertSessionHasErrors([
      'email' => 'メールアドレスを入力してください'
    ]);
  }

  public function test_register_requires_password()
  {
    $data = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password_confirmation' => 'password',
    ];

    $response = $this->post('/register', $data);

    $response->assertSessionHasErrors([
      'password' => 'パスワードを入力してください'
    ]);
  }

  public function test_register_min_password()
  {
    $data = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'pass',
      'password_confirmation' => 'password',
    ];

    $response = $this->post('/register', $data);

    $response->assertSessionHasErrors([
      'password' => 'パスワードは8文字以上で入力してください'
    ]);
  }

  public function test_register_requires_password_confirmation()
  {
    $data = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'password',
      'password_confirmation' => 'different-password',
    ];

    $response = $this->post('/register', $data);

    $response->assertSessionHasErrors([
      'password' => 'パスワードと一致しません'
    ]);
  }


  // 会員登録
  public function test_user_can_register()
  {
    $data = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'password',
      'password_confirmation' => 'password',
    ];

    $response = $this->post('/register', $data);

    $response->assertRedirect('/email/verify');

    $this->assertDatabaseHas('users', [
      'name' => 'Test User',
      'email' => 'test@example.com',
    ]);

    $this->assertAuthenticated();
  }

  // ログインバリデーション
  public function test_login_requires_email()
  {
    $data = [
      'name' => 'Test User',
      'password' => 'password',
      'password_confirmation' => 'password',
    ];

    $response = $this->post('/login', $data);

    $response->assertSessionHasErrors([
      'email' => 'メールアドレスを入力してください'
    ]);
  }

  public function test_login_requires_password()
  {
    $data = [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password_confirmation' => 'password',
    ];

    $response = $this->post('/login', $data);

    $response->assertSessionHasErrors([
      'password' => 'パスワードを入力してください'
    ]);
  }

  public function test_user_cannot_login()
  {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password'),
    ]);

    $data = [
      'email' => 'different.test@example.com',
      'password' => 'different-password',
    ];

    $response = $this->post('/login', $data);

    $response->assertSessionHasErrors([
      'email' => 'ログイン情報が登録されていません'
    ]);
  }

  // ログイン
  public function test_user_can_login()
  {
    $user = User::factory()->create([
      'email' => 'test@example.com',
      'password' => bcrypt('password'),
    ]);

    $data = [
      'email' => 'test@example.com',
      'password' => 'password',
    ];

    $response = $this->post('/login', $data);

    $response->assertRedirect('/');

    $this->assertAuthenticatedAs($user);
  }

  // ログアウト
  public function test_user_can_logout()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->post('/logout');

    $response->assertRedirect('/login');

    $this->assertGuest();
  }
}
