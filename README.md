## Blog Admin Panel
<p><i>Note: App is still under heavy development</i></p>

<img src="https://user-images.githubusercontent.com/23532087/155858449-2a4af2ae-dd7e-40a5-ad83-7969232d26de.png" alt="Blog Admin Panel Image Light">
<img src="https://user-images.githubusercontent.com/23532087/155858457-05071bb4-461e-4a51-ac93-07818c181fcb.png" alt="Blog Admin Panel Image Dark">

Simple blog admin panel created with <a href="https://filamentphp.com/" target="_blank">Filament</a>. 

## Installation
Clone the repository, navigate to project directory and install dependencies
```bash
composer install
```
  
Create a file for environment variables by coping `.env.example`
```bash
cp .env.example .env
```

Create application key
```bash
php artisan key:generate
```

Setup database credentials in `.env` file and run migrations
```bash
php artisan migrate
```

## Usage

Create new user with command

```bash
php artisan make:filament-user
```

To be able to upload images you need to setup S3 disk, or you can use some of the alternatives for local development like <a href="https://min.io/" target="_blank">Minio</a>.

## Testing
```bash
./vendor/bin/pest
```
