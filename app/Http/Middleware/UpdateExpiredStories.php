<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Story;
use Carbon\Carbon;

class UpdateExpiredStories
{
    public function handle($request, Closure $next)
    {
        $expiryDate = Carbon::now()->subHours(24);
        Story::where('created_at', '<', $expiryDate)->where('status', 'active')->update(['status' => 'inactive']);

        return $next($request);
    }
}
