# Lue

Internal Time Off Management System. In [COMQUAS](https://www.comquas.com) , employee need to apply leave from Lue.

When apply time off , it will send email , slack to supervisor.

## Screenshots

![](https://cldup.com/caFvxqy3Yg.png)

![](https://cldup.com/32OAIpYTvt.png)

## Setup

Change the admin user and email in `database/seeds/UsersTableSeeder.php`

```
# php artisan migrate
# php artisan db:seed
```

Copy `.env.example` to `.evn`.

Edit `.env` for slack and mailgun.

## Using

- PHP with Laravel 5.4
- MySQL
- [Carbon](http://carbon.nesbot.com/docs/)
- [NowUI Kit](http://demos.creative-tim.com/now-ui-kit/index.html)
- [Bootstrap 4](https://v4-alpha.getbootstrap.com)
- [Moment.js](https://momentjs.com)
- [Select2](http://select2.github.io)


## Todo

- [x] send email when time-off is apply, approve, reject
- [x] send to slack of supervisor when time-off is apply, approve, reject
- [ ] REST API
- [ ] Android App
- [ ] iOS App
- [ ] Send Payslip (PDF File)
- [ ] Filter Time-Off by date range