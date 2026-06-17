<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EmailNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API endpoints for email delivery logs and audit trails (admin only).
 */
class NotificationController extends Controller
{
    /**
     * List email notification delivery history.
     */
    public function emailHistory(Request $request): JsonResponse
    {
        $query = EmailNotification::query()->with(['user:id,name,email', 'booking:id,reference_code'])
            ->latest('id');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($template = $request->query('template')) {
            $query->where('template_key', $template);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(20),
        ]);
    }

    /**
     * List audit log entries for OTP and security events.
     */
    public function auditLogs(Request $request): JsonResponse
    {
        $query = AuditLog::query()->with('user:id,name,email')->latest('id');

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        if ($event = $request->query('event')) {
            $query->where('event', $event);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(20),
        ]);
    }
}
