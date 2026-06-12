@echo off
echo Creating eCart Electronics Project Structure...
echo.

REM Create directories
echo Creating directories...
mkdir app\Http\Controllers\Admin 2>nul
mkdir app\Http\Controllers\User 2>nul
mkdir app\Http\Controllers\Auth 2>nul
mkdir app\Http\Middleware 2>nul
mkdir app\Models 2>nul
mkdir database\migrations 2>nul
mkdir database\seeders 2>nul
mkdir resources\views\admin\layouts 2>nul
mkdir resources\views\admin\categories 2>nul
mkdir resources\views\admin\brands 2>nul
mkdir resources\views\admin\products 2>nul
mkdir resources\views\admin\orders 2>nul
mkdir resources\views\admin\reports 2>nul
mkdir resources\views\user\layouts 2>nul
mkdir resources\views\user\products 2>nul
mkdir resources\views\auth 2>nul
mkdir routes 2>nul

echo Creating files...

REM Create Controllers
echo Creating Admin Controllers...
type nul > "app\Http\Controllers\Admin\DashboardController.php"
type nul > "app\Http\Controllers\Admin\CategoryController.php"
type nul > "app\Http\Controllers\Admin\BrandController.php"
type nul > "app\Http\Controllers\Admin\ProductController.php"
type nul > "app\Http\Controllers\Admin\OrderController.php"
type nul > "app\Http\Controllers\Admin\PaymentController.php"
type nul > "app\Http\Controllers\Admin\ReportController.php"

echo Creating User Controllers...
type nul > "app\Http\Controllers\User\HomeController.php"
type nul > "app\Http\Controllers\User\ProductController.php"
type nul > "app\Http\Controllers\User\CartController.php"
type nul > "app\Http\Controllers\User\WishlistController.php"
type nul > "app\Http\Controllers\User\CheckoutController.php"
type nul > "app\Http\Controllers\User\OrderController.php"
type nul > "app\Http\Controllers\User\ProfileController.php"
type nul > "app\Http\Controllers\User\SettingController.php"

echo Creating Auth Controllers...
type nul > "app\Http\Controllers\Auth\LoginController.php"
type nul > "app\Http\Controllers\Auth\RegisterController.php"
type nul > "app\Http\Controllers\Auth\LogoutController.php"

REM Create Middleware
echo Creating Middleware...
type nul > "app\Http\Middleware\AdminMiddleware.php"
type nul > "app\Http\Middleware\UserMiddleware.php"

REM Create Models
echo Creating Models...
type nul > "app\Models\User.php"
type nul > "app\Models\Category.php"
type nul > "app\Models\Brand.php"
type nul > "app\Models\Product.php"
type nul > "app\Models\Cart.php"
type nul > "app\Models\Wishlist.php"
type nul > "app\Models\Order.php"
type nul > "app\Models\OrderItem.php"
type nul > "app\Models\Payment.php"

REM Create Database Files
echo Creating Migrations...
type nul > "database\migrations\create_users_table.php"
type nul > "database\migrations\create_categories_table.php"
type nul > "database\migrations\create_brands_table.php"
type nul > "database\migrations\create_products_table.php"
type nul > "database\migrations\create_carts_table.php"
type nul > "database\migrations\create_wishlists_table.php"
type nul > "database\migrations\create_orders_table.php"
type nul > "database\migrations\create_order_items_table.php"
type nul > "database\migrations\create_payments_table.php"

echo Creating Seeders...
type nul > "database\seeders\DatabaseSeeder.php"
type nul > "database\seeders\CategorySeeder.php"
type nul > "database\seeders\BrandSeeder.php"
type nul > "database\seeders\ProductSeeder.php"

REM Create Views
echo Creating Admin Views...
type nul > "resources\views\admin\layouts\app.blade.php"
type nul > "resources\views\admin\layouts\sidebar.blade.php"
type nul > "resources\views\admin\layouts\navbar.blade.php"
type nul > "resources\views\admin\dashboard.blade.php"

type nul > "resources\views\admin\categories\index.blade.php"
type nul > "resources\views\admin\categories\create.blade.php"
type nul > "resources\views\admin\categories\edit.blade.php"

type nul > "resources\views\admin\brands\index.blade.php"
type nul > "resources\views\admin\brands\create.blade.php"
type nul > "resources\views\admin\brands\edit.blade.php"

type nul > "resources\views\admin\products\index.blade.php"
type nul > "resources\views\admin\products\create.blade.php"
type nul > "resources\views\admin\products\edit.blade.php"
type nul > "resources\views\admin\products\show.blade.php"

type nul > "resources\views\admin\orders\index.blade.php"
type nul > "resources\views\admin\reports\revenue.blade.php"

echo Creating User Views...
type nul > "resources\views\user\layouts\app.blade.php"
type nul > "resources\views\user\layouts\header.blade.php"
type nul > "resources\views\user\home.blade.php"

type nul > "resources\views\user\products\index.blade.php"
type nul > "resources\views\user\products\show.blade.php"

type nul > "resources\views\user\cart.blade.php"
type nul > "resources\views\user\wishlist.blade.php"
type nul > "resources\views\user\checkout.blade.php"
type nul > "resources\views\user\orders.blade.php"
type nul > "resources\views\user\profile.blade.php"
type nul > "resources\views\user\settings.blade.php"

echo Creating Auth Views...
type nul > "resources\views\auth\login.blade.php"
type nul > "resources\views\auth\register.blade.php"

REM Create Routes
echo Creating Route Files...
type nul > "routes\web.php"
type nul > "routes\admin.php"
type nul > "routes\user.php"

echo.
echo Project structure created successfully!
echo.
echo Next steps:
echo 1. Run: composer dump-autoload
echo 2. Update your .env file with database configuration
echo 3. Run migrations: php artisan migrate
echo 4. Run seeders: php artisan db:seed
echo 5. Start server: php artisan serve
echo.
pause