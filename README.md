## Quiz Game

### Installation


Run the following commands to set up the project:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Testing
Intall the sqlite php dependency for testing

```bash
sudo apt install php-sqlite3
```

Run this command to migrate the testing db

```bash
php artisan migrate:fresh --env=testing
```

### Running

```bash
php artisan serve
```

```bash
npm run build
```
