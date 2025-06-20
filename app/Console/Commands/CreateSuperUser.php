<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperUser extends Command
{
    protected $signature = 'make:superuser {email?} {password?}';
    protected $description = 'Tạo mới tài khoản quản trị viên (superuser)';

    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Nhập email cho quản trị viên:');
        $password = $this->argument('password') ?? $this->secret('Nhập mật khẩu cho quản trị viên:');
        $name = $this->ask('Nhập tên hiển thị cho quản trị viên:');
        $username = $this->ask('Nhập tên đăng nhập cho quản trị viên:');

        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'username' => $username,
                'status' => 'Online',
                'type' => 'admin'
            ]);

            $this->info('Tạo tài khoản quản trị viên thành công!');
            $this->table(
                ['Tên', 'Email', 'Tên đăng nhập', 'Loại tài khoản'],
                [[$user->name, $user->email, $user->username, $user->type]]
            );
        } catch (\Exception $e) {
            $this->error('Lỗi khi tạo tài khoản quản trị viên: ' . $e->getMessage());
        }
    }
}
