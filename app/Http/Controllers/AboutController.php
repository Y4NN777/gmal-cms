<?php

namespace App\Http\Controllers;

use App\Models\ImpactMetric;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index()
    {
        $metrics = ImpactMetric::active()->ordered()->get();

        return Inertia::render('About/Index', [
            'metrics' => $metrics
        ]);
    }
}
