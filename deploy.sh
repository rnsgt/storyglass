#!/bin/bash

# ===========================================
# SCRIPT DEPLOYMENT LARAVEL KE DIRECTADMIN
# ===========================================
# Jalankan script ini via SSH di server DirectAdmin
# Usage: bash deploy.sh

echo "🚀 Starting Laravel Deployment..."

# Konfigurasi - GANTI INI SESUAI SERVER ANDA
PROJECT_PATH="/home/username/domains/yourdomain.com"
DOMAIN="yourdomain.com"

# Warna untuk output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function untuk print colored message
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if we're in the right directory
if [ ! -f "$PROJECT_PATH/artisan" ]; then
    print_error "File artisan tidak ditemukan di $PROJECT_PATH"
    print_error "Pastikan PROJECT_PATH sudah benar"
    exit 1
fi

cd $PROJECT_PATH

# 1. Check PHP version
print_info "Checking PHP version..."
PHP_VERSION=$(php -r "echo PHP_VERSION;")
print_success "PHP Version: $PHP_VERSION"

# 2. Check if .env exists
if [ ! -f ".env" ]; then
    print_error "File .env tidak ditemukan!"
    print_info "Copy dari .env.production atau buat manual"
    exit 1
fi
print_success "File .env found"

# 3. Generate APP_KEY if empty
APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ]; then
    print_info "Generating APP_KEY..."
    php artisan key:generate --force
    print_success "APP_KEY generated"
else
    print_success "APP_KEY already exists"
fi

# 4. Install/Update Composer Dependencies
print_info "Installing Composer dependencies..."
if [ ! -f "composer.phar" ]; then
    print_info "Downloading composer..."
    curl -sS https://getcomposer.org/installer | php
fi

php composer.phar install --optimize-autoloader --no-dev
print_success "Composer dependencies installed"

# 5. Set Permissions
print_info "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
print_success "Permissions set"

# 6. Create storage link
print_info "Creating storage link..."
if [ -L "public/storage" ]; then
    print_info "Storage link already exists, removing old link..."
    rm public/storage
fi
php artisan storage:link
print_success "Storage link created"

# 7. Test Database Connection
print_info "Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connected!'; exit;" 2>&1
if [ $? -eq 0 ]; then
    print_success "Database connection successful"
else
    print_error "Database connection failed! Check .env configuration"
    exit 1
fi

# 8. Run Migrations
print_info "Running migrations..."
php artisan migrate --force
if [ $? -eq 0 ]; then
    print_success "Migrations completed"
else
    print_error "Migration failed!"
    exit 1
fi

# 9. Seed Database (Optional - comment if not needed)
# print_info "Seeding database..."
# php artisan db:seed --force
# print_success "Database seeded"

# 10. Clear all cache
print_info "Clearing all cache..."
php artisan optimize:clear
print_success "Cache cleared"

# 11. Optimize for production
print_info "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
print_success "Optimization completed"

# 12. Final checks
print_info "Running final checks..."

# Check if storage directories are writable
if [ -w "storage/logs" ] && [ -w "storage/framework" ]; then
    print_success "Storage directories are writable"
else
    print_error "Storage directories are not writable!"
fi

# Check if .env is readable
if [ -r ".env" ]; then
    print_success ".env file is readable"
else
    print_error ".env file is not readable!"
fi

echo ""
echo "============================================"
print_success "🎉 DEPLOYMENT COMPLETED!"
echo "============================================"
echo ""
print_info "Next steps:"
echo "1. Test your website: https://$DOMAIN"
echo "2. Check logs if any error: tail -f storage/logs/laravel.log"
echo "3. Setup cron job for scheduler (if not done yet)"
echo ""
echo "Cron job command:"
echo "* * * * * cd $PROJECT_PATH && php artisan schedule:run >> /dev/null 2>&1"
echo ""
print_success "Happy coding! 🚀"
