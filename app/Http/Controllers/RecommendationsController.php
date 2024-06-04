<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RecommendationsController extends Controller
{
    public function recommendations(Request $request)
    {
        $user = auth()->user();
        $query = User::where('gender', '!=', $user->gender);

        // Age range filter
        if ($request->has('min_age') && $request->has('max_age')) {
            $minAge = $request->input('min_age');
            $maxAge = $request->input('max_age');
            $query->whereBetween('age', [$minAge, $maxAge]);
        }

        // Distance filter
        if ($request->has('distance')) {
            $distance = $request->input('distance');
            $currentLatitude = $user->latitude;
            $currentLongitude = $user->longitude;

            
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

            $query->select('*')
                ->selectRaw("$haversine AS distance", [$currentLatitude, $currentLongitude, $currentLatitude])
                ->having('distance', '<=', $distance);
        }

        //  paginated results
        $recommendations = $query->paginate(10);

        return response()->json($recommendations);
    }
    
}
