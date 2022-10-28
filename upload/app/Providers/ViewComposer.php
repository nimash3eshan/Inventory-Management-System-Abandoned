<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use View;

class ViewComposer extends Provider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // invoice
        View::composer(
            ['sale.sale_content', 'receiving.receiving_content', 'expense.form', 'customer.partials.sale_table', 'supplier.partials.receiving_table', 'customer.profile', 'supplier.profile'],
            'App\Http\ViewComposers\Invoice'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
