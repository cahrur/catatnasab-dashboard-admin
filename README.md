## Panduan Instalasi

Saya buat gambaran flow databasenya disini https://github.com/cahrur/flow-catatnasab

- Setup Database
- Konfigurasi .env
- DB Migrate
php artisan migrate

- Instalasi Filament
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels

- Buat pengguna admin
php artisan make:filament-user

- Batasi pengguna yang bisa login ke panel admin hanya yang menggunakan domain email tertentu
Edit file App\Models\User.php

    <?php
    namespace App\Models;
 
    use Filament\Models\Contracts\FilamentUser;
    use Filament\Panel;
    use Illuminate\Foundation\Auth\User as Authenticatable;
 
    class User extends Authenticatable implements FilamentUser
    {
     // ...
 
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@namadomain.com') && $this->hasVerifiedEmail();
    }
    }


- Mulai membuat model
php artisan make:model User -m
php artisan make:model Plan -m
php artisan make:model Payment -m
php artisan make:model Order -m

- Konfigurasi migrasi db
buka di database/migrations

- Membuat Resource
php artisan make:filament-resource User
php artisan make:filament-resource Plan
php artisan make:filament-resource Payment
php artisan make:filament-resource Order

jika ingin langung otomatis generate, tambahkan --generate
ex  php artisan make:filament-resource User --generate

- Menyiapkan Form di Resource
Panduan ini dipakai jika dibuthkan, jika sudah menggunakan  --generate dan tidak diperlukan tambahan di form resource maka tidak perlu.

-- Input text nama
use Filament\Forms;
use Filament\Forms\Form;
 
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('nama'),
        ]);
}

-Menyiapkan Table Resource
use Filament\Tables;
use Filament\Tables\Table;
 
public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
        ]);
}

