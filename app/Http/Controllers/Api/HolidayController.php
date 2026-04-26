<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->year ?? now()->year;

        return Holiday::with('user')
            ->whereYear('start_date', $year)
            ->get()
            ->map(function ($item) {
                $item->total_days =
                    Carbon::parse($item->start_date)
                    ->diffInDays($item->end_date) + 1;

                return $item;
            });
    }

    public function store(Request $request)
    {
        return Holiday::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => auth()->id() ?? 1
        ]);
    }

    public function show($id)
    {
        return Holiday::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->update($request->all());
        return $holiday;
    }

    public function destroy($id)
    {
        Holiday::destroy($id);
        return response()->json(['success' => true]);
    }
    public function indexView()
{
    $holidays = \App\Models\Holiday::with('user')->latest()->paginate(10);

    return view('holiday.holiday_config', compact('holidays'));
}
}
