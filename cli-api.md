Wash
----
php artisan wash:show date          Show the schedule for specified date
php artisan wash:reservations       Show my current reservations
php artisan wash:avails [n=15]      Show the next n available times
php artisan wash:book 20141214 2    Book the specified turn at the spefied date

----------------------------------------------------
| 30 November, 2014                                |
----------------------------------------------------
| 07:00 - 10:00 | AVAILABLE | wash book 20141130 1 |
| 10:00 - 13:00 | TAKEN     | -                    |
| 13:00 - 16:00 | TAKEN     | -                    |
| 16:00 - 19:00 | AVAILABLE | wash book 20141130 4 |
----------------------------------------------------

------------------------------------------------------
| Next 15 available times                            |
------------------------------------------------------
| 15 November | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 4 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 4 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 4 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 4 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
| 30 December | 13:00 - 16:00 | wash book 20141130 1 |
------------------------------------------------------