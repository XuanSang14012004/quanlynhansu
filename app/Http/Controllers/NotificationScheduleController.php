<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\NotificationSchedule;

class NotificationScheduleController extends Controller
{
   
    public function store(Request $request)
    {
        NotificationSchedule::create([
            'schedule_type' => $request->schedule_type,
            'notify_time' => $request->notify_time,
            'email' => $request->email ? 1 : 0,
            'zalo' => $request->zalo ? 1 : 0,
            'phone' => $request->phone ? 1 : 0,
            'user_id' => Auth::id()
        ]);

        return redirect()->back();
    }
    public function update(Request $request, $id)
    {
        $notification = NotificationSchedule::findOrFail($id);

        $notification->schedule_type = $request->schedule_type;
        $notification->notify_time = $request->notify_time;
        $notification->email = $request->has('email');
        $notification->zalo = $request->has('zalo');
        $notification->phone = $request->has('phone');

        $notification->save();

        return redirect()->back()->with('success', 'Cập nhật thành công');
    }
    public function destroy($id)
    {
        NotificationSchedule::find($id)->delete();

        return redirect()->back();
    }
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);

        $notifications = NotificationSchedule::orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);

        return view('notification.index', compact('notifications', 'perPage'));
    }
}
