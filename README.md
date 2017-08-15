# Lue

Internal Time Off Management System. In [COMQUAS](https://www.comquas.com) , employee need to apply leave from Lue.

When apply time off , it will send email , slack to supervisor.

## Setup

Change the admin user and email in `database/seeds/UsersTableSeeder.php`

```
# php artisan db:migrate
# php artisan db:seed
```

Copy `.env.example` to `.evn`.

Edit `.env` for slack and mailgun.