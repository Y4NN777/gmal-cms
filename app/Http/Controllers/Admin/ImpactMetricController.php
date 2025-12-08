<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImpactMetric;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImpactMetricController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metrics = ImpactMetric::ordered()->get();

        return Inertia::render('Admin/ImpactMetrics/Index', [
            'metrics' => $metrics
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/ImpactMetrics/Form', [
            'metric' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:impact_metrics,key|max:255',
            'label_en' => 'required|string|max:255',
            'label_fr' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        ImpactMetric::create($validated);

        return redirect()->route('impact-metrics.index')
            ->with('success', 'Impact metric created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImpactMetric $impactMetric)
    {
        return Inertia::render('Admin/ImpactMetrics/Form', [
            'metric' => $impactMetric
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImpactMetric $impactMetric)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:impact_metrics,key,' . $impactMetric->id,
            'label_en' => 'required|string|max:255',
            'label_fr' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $impactMetric->update($validated);

        return redirect()->route('impact-metrics.index')
            ->with('success', 'Impact metric updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImpactMetric $impactMetric)
    {
        $impactMetric->delete();

        return redirect()->route('impact-metrics.index')
            ->with('success', 'Impact metric deleted successfully.');
    }

    /**
     * Update the order of metrics
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'metrics' => 'required|array',
            'metrics.*.id' => 'required|exists:impact_metrics,id',
            'metrics.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['metrics'] as $metricData) {
            ImpactMetric::where('id', $metricData['id'])
                ->update(['order' => $metricData['order']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
