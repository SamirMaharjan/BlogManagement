# Laravel + Vue Blog Application

## Prerequisites
Ensure you have the following installed on your system:

- **PHP 8.2**
- **Node.js 18**
- **Composer**
- **NPM**
- **MySQL** (or any database you prefer)

## Installation Steps
Follow these steps to install and set up the application:

### 1. Clone the Repository
```sh
git clone <repository-url>
cd <project-directory>
```

### 2. Install Backend Dependencies
```sh
composer install
```

### 3. Install Frontend Dependencies
```sh
npm install
```

### 4. Configure Environment
- Copy the `.env.example` file to `.env`:
  ```sh
  cp .env.example .env
  ```
- Update database credentials in the `.env` file:
  ```env
  DB_DATABASE=your_database
  DB_USERNAME=your_username
  DB_PASSWORD=your_password
  ```

### 5. Set Up the Database
```sh
php artisan migrate --seed
```
This will create the necessary tables and seed some initial data.

### 6. Start the Backend Server
```sh
php artisan serve
```

### 7. Start the Frontend Development Server
```sh
npm run dev
```

## API Documentation
You can check the API documentation using Postman:
[Postman API Docs](https://documenter.getpostman.com/view/25062418/2sAYdoESv8)

## Authentication for API
- Login API returns a Bearer token which should be included in subsequent requests.
- Logout API is available to invalidate the token.

## Troubleshooting
- If you encounter permission issues, run:
  ```sh
  chmod -R 777 storage bootstrap/cache
  ```
- If migrations fail, ensure MySQL is running and the credentials in `.env` are correct.
- If frontend changes are not reflecting, restart the Vue server using `npm run dev`.

## Additional Commands
- To clear cache:
  ```sh
  php artisan cache:clear
  php artisan config:clear
  ```
- To refresh the database:
  ```sh
  php artisan migrate:fresh --seed
  ```

Happy coding! 🚀

