<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // @icon('wallet', 18) -> inline SVG that inherits the current text color.
        Blade::directive('icon', function (string $expression): string {
            return "<?php echo icon({$expression}); ?>";
        });
    }
}
