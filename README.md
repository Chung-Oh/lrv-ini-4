## Authentication structure

Laravel 6 to work with authentication, run the following commands:

1) `npm install`

2) `composer require laravel/ui --dev`

3) `php artisan ui vue --auth`

SQLite (The database used here was *sqlite*, for that we created the file *database.sqlite* in the directory *database/*.
Dont forget of enable on *env* file)

Translation Package `composer require caouecs/laravel-lang:~4.0`, after importing go to vendor package, copy language pack and
paste *resourses/lang/*. And finally go to the file *config/app.php* in the key *locale* and *fallback_locale* put in the
values folder name that was pasted into *resources/*.

To send emails in the language you want, you need to create a notification class to override Trait
`php artisan make:notification MyClassNotification`. Override the **toMail($notifiable)** method and in the User class implement
**sendPasswordResetNotification($token)** method.

**Laravel-ide-helper** installed to help with facades autocomplete `composer require --dev barryvdh/laravel-ide-helper`.
After installation go to *App\Providers\AppServiceProvider.php* and put in *register* method:

    if ($this->app->environment() !== 'production') {
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
    }

Then execute, to finally work: `php artisan ide-helper:generate`.

A helper was created in **App/helpers.php** to handle app and admin views.
