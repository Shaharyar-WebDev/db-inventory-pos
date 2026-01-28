<?php

namespace App\Http\Middleware;

use App\Models\Outlet\Outlet;
use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OutletMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session();
        $outletId = $session->get('outlet_id');
        $outletsIndexUrl = Filament::getPanel('admin')
            ->getResourceUrl(Outlet::class);

        if (! $outletId) {
            $session->forget(['outlet_id', 'outlet_name']);
            Notification::make('no_outlet_selected')
                ->title('No Outlet Selected')
                ->body('Please select an outlet first')
                ->warning()
                ->send();

            return redirect($outletsIndexUrl);
        }

        // Cache lookup for performance
        $outlet = Outlet::find($outletId);

        if (! $outlet || ! $outlet->is_active) {
            $session->forget(['outlet_id', 'outlet_name']);
            Notification::make('no_outlet_selected')
                ->title('Invalid Outlet')
                ->body('Outlet does not exist or is inactive')
                ->warning()
                ->send();

            return redirect($outletsIndexUrl);
        }

        return $next($request);
    }
}
