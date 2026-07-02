<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        $latestNotification = Notification::where('user_id', Auth::id())
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'count' => $count,
            'latest_id' => $latestNotification?->id,
            'latest_title' => $latestNotification?->title,
            'latest_message' => $latestNotification?->message,
            'latest_url' => $latestNotification?->url,
            'latest_is_read' => $latestNotification?->is_read,
        ]);
    }

    public function latest()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    public function read(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke notifikasi ini.');
        }

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        if ($notification->url) {
            return redirect($notification->url);
        }

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notifikasi telah dibaca.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus notifikasi ini.');
        }

        $notification->delete();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}