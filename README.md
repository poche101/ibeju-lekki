# CE attendance — multi-church Laravel (attendance2.christembassylz5.org)
Public: /ibeju-lekki-mega · /alasia. Admin: /{slug}/admin/login (separate admin per church).
Admin menu: Overview · Members (database: search, add, edit, delete, CSV) · Attendance (present/absent by date, CSV) · Reports · Semantic Search (OpenAI).

## Install (Laravel 11/12)
1. `laravel new attendance && cd attendance`; copy these folders over it (replace `routes/web.php`).
2. `app/Models/User.php`: add `'church_id'` to `$fillable`.
3. `bootstrap/app.php` -> `->withMiddleware(fn($m)=>$m->alias(['church.admin'=>\App\Http\Middleware\ChurchAdmin::class]))`
4. `config/services.php`: `'openai'=>['key'=>env('OPENAI_API_KEY'),'model'=>env('OPENAI_MODEL','gpt-4o-mini')]`; set both in `.env` (use any chat model your OpenAI account has).
5. `.env`: DB_* settings. Run `php artisan migrate && php artisan db:seed --class=ChurchSeeder` (prints each church's admin login).
6. Nginx: `server_name attendance2.christembassylz5.org;` root `/var/www/attendance/public`, then certbot for SSL.
