# Turn on maintenance mode
php artisan down || true

# Kill Socket.io Server & Queue process
pm2 delete server.yaml

# Install/update composer dependecies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

#Generate JWT Secret
php artisan jwt:secret --force

#Generate Key
php artisan key:generate 

# Run database migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear

#Clear Queue
php artisan queue:flush

#Restart Queue
php artisan queue:restart

# Clear expired password reset tokens
php artisan auth:clear-resets


# Clear and cache config
php artisan config:clear

# Clear and cache config
php artisan config:cache

# Clear and cache views
php artisan view:cache
      

# Install node modules
npm ci

# Build assets using Laravel Mix
npm run dev

# Turn off maintenance mode
php artisan up

# Start Socket.io Server & Queue
pm2 start server.yaml

# Start Laravel
php artisan serve