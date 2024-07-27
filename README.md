php artisan make:migration create_categories_table --create=categories<br>
php artisan make:migration create_items_table --create=items<br>
php artisan migrate<br>
php artisan make:seeder CategoriesTableSeeder<br>
php artisan make:seeder UsersTableSeeder<br>
php artisan db:seed<br>
php artisan make:model Category -m<br>
php artisan make:model Item -m<br>
php artisan make:controller ItemController --resource<br>
php artisan make:migration add_images_to_items_table --table=items<br>
php artisan migrate<br>
php artisan storage:link<br>
