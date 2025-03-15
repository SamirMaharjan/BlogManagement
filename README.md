# Laravel + Vue Blog Application with Docker

## Prerequisites
You only need Docker and Docker Compose installed on your system. All other dependencies (PHP 8.2, Node.js 18, Composer, NPM, MySQL) will be included in the Docker containers.

## Installation Steps with Docker
Follow these steps to install and set up the application using Docker:

### 1. Clone the Repository
```sh
git clone <repository-url>
cd <project-directory>
```

### 2. Configure Environment
- Copy the `.env.example` file to `.env`:
  ```sh
  cp .env.example .env
  ```
- Update database credentials in the `.env` file to match Docker configuration:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=db
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=laravel
  DB_PASSWORD=root
  ```

### 3. Start Docker Containers
```sh
docker-compose up -d
```

### 4. Install Dependencies and Set Up Application
```sh
# Install backend dependencies
docker-compose exec app composer install

# Install frontend dependencies
docker-compose exec app npm install

# Generate application key
docker-compose exec app php artisan key:generate

# Set up storage link
docker-compose exec app php artisan storage:link

# Run database migrations and seed data
docker-compose exec app php artisan migrate --seed
```

### 5. Start the Frontend Development Server
```sh
docker-compose exec app npm run dev
```

### 6. Access the Application
- Laravel API: http://localhost:8000
- Vue frontend with Vite: http://localhost:5173

## API Documentation
You can check the API documentation using Postman:
[Postman API Docs](https://documenter.getpostman.com/view/25062418/2sAYdoESv8)

## Authentication for API
- Login using the provided credentials:
  - Email: test@gmail.com
  - Password: password
- The API returns a Bearer token which should be included in subsequent requests.
- Logout API is available to invalidate the token.

## Troubleshooting Docker Setup
- If containers aren't starting properly, check logs:
  ```sh
  docker-compose logs
  ```
- If you need to restart the containers:
  ```sh
  docker-compose restart
  ```
- If you need to rebuild containers after changing Dockerfile:
  ```sh
  docker-compose down
  docker-compose build
  docker-compose up -d
  ```
- If you encounter permission issues, run:
  ```sh
  docker-compose exec app chmod -R 777 storage bootstrap/cache
  ```

## Additional Docker Commands
- To clear Laravel cache:
  ```sh
  docker-compose exec app php artisan cache:clear
  docker-compose exec app php artisan config:clear
  ```
- To refresh the database:
  ```sh
  docker-compose exec app php artisan migrate:fresh --seed
  ```
- To stop all containers:
  ```sh
  docker-compose down
  ```

## Running in Production
For production deployment:
```sh
# Build frontend assets
docker-compose exec app npm run build

# Optimize Laravel
docker-compose exec app php artisan optimize
```
 docker-compose exec app bash
 