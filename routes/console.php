<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

Artisan::command('admin:criar', function () {
    // Symfony switches to the Windows OEM code page while reading the first answer.
    // Load its input helper while Composer can still resolve accented directory names.
    if (PHP_OS_FAMILY === 'Windows') {
        class_exists(\Symfony\Component\Console\Helper\TerminalInputHelper::class);
    }
    $dados = ['name' => $this->ask('Nome'), 'email' => strtolower(trim((string)$this->ask('E-mail'))),
        'password' => $this->secret('Senha (mínimo 12 caracteres)'),
        'password_confirmation' => $this->secret('Repita a senha')];
    $validator = Validator::make($dados, ['name' => 'required|string|max:120', 'email' => 'required|email|max:254|unique:users,email', 'password' => 'required|string|min:12|confirmed']);
    if ($validator->fails()) {
        $this->error($validator->errors()->first());
        return 1;
    }
    User::create($validator->validated());
    $this->info('Administrador criado. Acesse /admin.');
    return 0;
})->purpose('Criar um administrador da loja');

Artisan::command('inspire', function () {
    $this->comment('Keep building.');
})->purpose('Display a short message');
