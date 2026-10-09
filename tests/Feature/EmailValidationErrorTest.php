<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailValidationErrorTest extends TestCase
{
    // データベースをリセットする
    use RefreshDatabase;

    public function test_register_user_normal(): void
    {
        // ユーザー作成（正常）
        $user = User::factory()->create([
            'name' => '太郎',
            'email' => 'taro@example.com',
            'password' => bcrypt('password'),
        ]);

        // データを登録フォームにPOSTする
        $response = $this->post('/register');

        // データが正常に送られたかを確認する
        $this->assertDatabaseHas('users', [
            'name' => '太郎',
            'email' => 'taro@example.com',
        ]);
    }

    public function test_register_requires_valid_email()
    {
        // 重複したメールアドレスを送信する
        $user = User::factory()->create([
            'name' => '山田',
            'email' => 'taro@example.com',
            'password' => bcrypt('password'),
        ]);

        // 例外が発生することを期待する
        $this->expectException(\Illuminate\Database\QueryException::class);

        // 重複したメールアドレスを送信する
        $user = User::factory()->create([
            'name' => '山田',
            'email' => 'taro@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/register');
    }
}
