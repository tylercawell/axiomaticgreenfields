# axiomaticgreenfields
Technical Assessment for Axiomatic Consultants

Tech Stack
- Laravel 13
- PHP 8.4
- Inertia.js
- Vue 3
- Vite
- Tailwind CSS
- PEST (Testing)
- MySQL (Dockerised)
- Spatie Laravel Permissions

# Getting Started
From the root of the project run:
docker compose up --build-d

Run the following set up commands:
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app npm install
docker compose exec app npm run build

The application will be available at:
http://localhost:8080

# Default Users:

Admin:
admin@axiomatic.co.za
password

Permissions:
- View Commission Notes
- Manage Commission Notes

Viewer:
viewer@axiomatic.co.za
password

Permissions:
- View Commission Notes

# Seeded Data

** The database seeder includes **

- Companies
- Branches (Linked to Companies)
- Employees (Linked correctly to branches and companies)
- Commission Notes

** Commission notes and amounts are seeded using fixed values **

- 10,000.00
- 20,000.00

# Running Tests

** Run tests inside Docker **
docker compose exec app php artisan test

# Troubleshooting

** Vite manifest not found **
docker compose exec app npm run build

** Node container issues **
docker compose ps

** MySql test errors **
php artisan migrate --env=testing
