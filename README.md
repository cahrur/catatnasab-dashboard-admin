# Panduan Instalasi

Saya buat gambaran flow databasenya disini [flow-catatnasab](https://github.com/cahrur/flow-catatnasab)

## Setup Database

1. Konfigurasi `.env`
2. DB Migrate

    ```bash
    php artisan migrate
    ```

## Instalasi Filament

    ```bash
    composer require filament/filament:"^3.2" -W
    php artisan filament:install --panels
    ```

## Buat Pengguna Admin

    ```bash
    php artisan make:filament-user
    ```

## Batasi Pengguna yang Bisa Login ke Panel Admin

Edit file `App\Models\User.php`:

    ```php
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
    ?>
    ```

## Mulai Membuat Model

    ```bash
    php artisan make:model User -m
    php artisan make:model Plan -m
    php artisan make:model Payment -m
    php artisan make:model Order -m
    ```

## Konfigurasi Migrasi DB

Buka file migrasi di folder `database/migrations`.

## Membuat Resource

    ```bash
    php artisan make:filament-resource User
    php artisan make:filament-resource Plan
    php artisan make:filament-resource Payment
    php artisan make:filament-resource Order
    ```

Jika ingin langsung otomatis generate, tambahkan `--generate`.

Contoh:

    ```bash
    php artisan make:filament-resource User --generate
    ```

## Menyiapkan Form di Resource

Panduan ini dipakai jika dibutuhkan. Jika sudah menggunakan `--generate` dan tidak diperlukan tambahan di form resource, maka tidak perlu.

### Input Text Nama

    ```php
    use Filament\Forms;
    use Filament\Forms\Form;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama'),
            ]);
    }
    ```

## Menyiapkan Table Resource

    ```php
    use Filament\Tables;
    use Filament\Tables\Table;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ]);
    }
    ```

## Trigger

    ```sql
    DELIMITER $$

    CREATE TRIGGER trigger_update_user_plan
    AFTER UPDATE ON orders
    FOR EACH ROW
    BEGIN
        IF NEW.status = 'paid' THEN
            UPDATE users
            SET 
                plan = NEW.plan_id,
                expired_plan = DATE_ADD(NEW.created_at, INTERVAL (SELECT durasi FROM plans WHERE id = NEW.plan_id) DAY),
                start_plan = NEW.created_at,
                updated_at = NEW.updated_at
            WHERE id = NEW.user_id;
        END IF;
    END$$

    DELIMITER ;
    ```
