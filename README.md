# axiomaticgreenfields
Technical Assessment for Axiomatic Consultants


To boot application please run the following commands in your terminal in the root of the project:

docker compose up --build -d
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app npm run build

Upon application boot dummy data will be seeded into a MySQL database

Run PEST test via:

php artisan test

Default Users:

Admin:
admin@axiomatic.co.za
password

Viewer:
viewer@axiomatic.co.za
password
