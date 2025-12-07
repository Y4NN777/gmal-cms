<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function visitors()
    {
        return response()->json(['count' => 0]);
    }

    public function donors()
    {
        return response()->json(['count' => 0]);
    }

    public function dashboard()
    {
        return response()->json(['stats' => []]);
    }

    public function reports()
    {
        return response()->json(['reports' => []]);
    }
}
