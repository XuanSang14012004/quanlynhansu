<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        Holiday::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => auth()->id() ?? 1
        ]);
        return redirect()->back()->with('success', 'Thêm thành công!');
    }

    public function show($id)
    {
        return Holiday::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $holiday->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        Holiday::destroy($id);
        return redirect()->back()->with('success', 'Xoá thành công!');
    }
    public function indexView(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        $year = $request->year ?? now()->year;

        $holidays = \App\Models\Holiday::with('user')
            ->whereYear('start_date', $year)
            ->latest()
            ->paginate($perPage)
            ->appends([
                'year' => $year,
                'per_page' => $perPage
            ]);

        return view('holiday.holiday_config', compact('holidays', 'perPage', 'year'));
    }
}
