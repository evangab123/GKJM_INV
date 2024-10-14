<?php

namespace App\Helpers;

use App\Models\ActivityLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogHelper
{
    public static function log($activity, $newData = null, $previousData = null)
    {
        $log = new ActivityLogs();
        $log->username = Auth::user()->username ?? null;
        $log->activity = $activity;

        $changes = [];

        if ($previousData === null || $newData === null) {
            // If no previous data, assume this is a new record
            $changes = $newData;
        } else {
            foreach ($previousData as $key => $value) {
                if (array_key_exists($key, $newData)) {
                    // Ignore 'created_at' and 'updated_at' fields
                    if (!in_array($key, ['created_at', 'updated_at'])) {
                        // Handle array or object comparison by converting to JSON strings
                        if (is_array($value) || is_array($newData[$key])) {
                            if (json_encode($value) !== json_encode($newData[$key])) {
                                $changes[$key] = [
                                    'old' => $value,
                                    'new' => $newData[$key],
                                ];
                            }
                        } else {
                            // Compare scalar values (convert to string for comparison)
                            if ((string)$value !== (string)$newData[$key]) {
                                $changes[$key] = [
                                    'old' => $value,
                                    'new' => $newData[$key],
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Save changes as JSON
        $log->changess = json_encode($changes);
        $log->ip_address = Request::ip();
        $log->user_agent = Request::header('User-Agent');

        $log->save();
    }
}
