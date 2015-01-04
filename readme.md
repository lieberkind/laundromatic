## Laundromatic
PHP command line tool for interacting with Saniva laundry systems. Built on
Laravel and using Artisan for creating commands.

### Commands
The commands currently supported are:

- `php artisan wash:show [date:YYYYMMDD]`
- `php artisan wash:book [date:YYYYMMDD] [slot:1-4]`
- `php artisan wash:cancel [date:YYYYMMDD] [slot:1-4]`

### Todo
- Refactor procedual proof-of-concept fuglyness into more reusable components
- Free the system of Laravel and Artisan and make it usable from everywhere, just like tools like Composer
- Possibility to view and book times for more than one laundromat
- Scheduling feature for setting up a cron jobs to automatically book new times when existing have been used
- Build a small web app that encompasses all of the above features as well (and at the same time is prettier to look at)
