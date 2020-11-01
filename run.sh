# Turn on maintenance mode
php artisan down || true

# Pull the latest changes from the git repository
# git reset --hard
# git clean -df
# git pull origin master

# Install/update composer dependecies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Run database migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear

# Clear expired password reset tokens
php artisan auth:clear-resets

# Clear and cache routes
php artisan route:cache

# Clear and cache config
php artisan config:cache

# Clear and cache views
php artisan view:cache

#Generate JWT Secret
php artisan jwt:secret 

#Generate Key
php artisan key:generate        

# Install node modules
npm ci

# Build assets using Laravel Mix
npm run dev

# Turn off maintenance mode
php artisan up

#Start Laravel
php artisan ser &

#Start Queue
php artisan queue:work &

#Start Socket.io server
node server.js &
