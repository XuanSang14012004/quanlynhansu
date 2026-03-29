<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User; // <--- Lưu ý: Laravel 10 mặc định User nằm trong thư mục Models

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Tên class Model tương ứng (quan trọng nếu Model của bạn không nằm trong App\Models)
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            // Password mặc định là 'password'. Nếu muốn giống hệt bản cũ là 'secret', hãy sửa string bên trong.
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10), // Laravel 10 dùng Str::random thay vì str_random
        ];
    }

    /**
     * Trạng thái email chưa xác thực (Optional)
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}