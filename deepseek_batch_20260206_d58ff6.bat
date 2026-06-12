@echo off
echo Creating missing tables...

REM Create notifications table
php artisan notifications:table

REM Create other missing tables manually
echo Creating addresses table...
(
echo ^<?php
echo.
echo use Illuminate\Database\Migrations\Migration;
echo use Illuminate\Database\Schema\Blueprint;
echo use Illuminate\Support\Facades\Schema;
echo.
echo return new class extends Migration
echo {
echo     public function up^()
echo     {
echo         Schema::create^('addresses', function ^(Blueprint ^$table^) {
echo             ^$table->id^(^);
echo             ^$table->unsignedBigInteger^('user_id'^);
echo             ^$table->enum^('type', ['shipping', 'billing', 'both']^);
echo             ^$table->string^('address_line_1'^);
echo             ^$table->string^('address_line_2'^)->nullable^(^);
echo             ^$table->string^('city'^);
echo             ^$table->string^('state'^);
echo             ^$table->string^('zip_code'^);
echo             ^$table->string^('country'^);
echo             ^$table->boolean^('is_default'^)->default^('false'^);
echo             ^$table->timestamps^(^);
echo             
echo             ^$table->foreign^('user_id'^)->references^('id'^)->on^('users'^)->onDelete^('cascade'^);
echo         }^);
echo     }
echo.
echo     public function down^(^)
echo     {
echo         Schema::dropIfExists^('addresses'^);
echo     }
echo };
) > "database\migrations\2024_01_01_000001_create_addresses_table.php"

echo Creating reviews table...
(
echo ^<?php
echo.
echo use Illuminate\Database\Migrations\Migration;
echo use Illuminate\Database\Schema\Blueprint;
echo use Illuminate\Support\Facades\Schema;
echo.
echo return new class extends Migration
echo {
echo     public function up^(^)
echo     {
echo         Schema::create^('reviews', function ^(Blueprint ^$table^) {
echo             ^$table->id^(^);
echo             ^$table->unsignedBigInteger^('user_id'^);
echo             ^$table->unsignedBigInteger^('product_id'^);
echo             ^$table->string^('title'^);
echo             ^$table->text^('comment'^);
echo             ^$table->integer^('rating'^)->unsigned^(^);
echo             ^$table->enum^('status', ['pending', 'approved', 'rejected']^)->default^('pending'^);
echo             ^$table->timestamps^(^);
echo             
echo             ^$table->foreign^('user_id'^)->references^('id'^)->on^('users'^)->onDelete^('cascade'^);
echo             ^$table->foreign^('product_id'^)->references^('id'^)->on^('products'^)->onDelete^('cascade'^);
echo             ^$table->unique^(['user_id', 'product_id']^);
echo         }^);
echo     }
echo.
echo     public function down^(^)
echo     {
echo         Schema::dropIfExists^('reviews'^);
echo     }
echo };
) > "database\migrations\2024_01_01_000002_create_reviews_table.php"

echo Creating login history table...
(
echo ^<?php
echo.
echo use Illuminate\Database\Migrations\Migration;
echo use Illuminate\Database\Schema\Blueprint;
echo use Illuminate\Support\Facades\Schema;
echo.
echo return new class extends Migration
echo {
echo     public function up^(^)
echo     {
echo         Schema::create^('login_histories', function ^(Blueprint ^$table^) {
echo             ^$table->id^(^);
echo             ^$table->unsignedBigInteger^('user_id'^);
echo             ^$table->string^('ip_address'^);
echo             ^$table->text^('user_agent'^);
echo             ^$table->timestamp^('login_at'^);
echo             ^$table->timestamp^('logout_at'^)->nullable^(^);
echo             
echo             ^$table->foreign^('user_id'^)->references^('id'^)->on^('users'^)->onDelete^('cascade'^);
echo         }^);
echo     }
echo.
echo     public function down^(^)
echo     {
echo         Schema::dropIfExists^('login_histories'^);
echo     }
echo };
) > "database\migrations\2024_01_01_000003_create_login_histories_table.php"

echo Running migrations...
php artisan migrate

echo.
echo Tables created successfully!
echo.
pause