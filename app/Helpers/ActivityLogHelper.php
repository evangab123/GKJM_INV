<?php

namespace App\Helpers;

use App\Models\ActivityLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogHelper
{
    public static function log($activity, $newData = null, $previousData = null, $entitas, $idObjek)
    {
        $log = new ActivityLogs();
        $log->username = Auth::user()->username ?? null;
        $log->activity = $activity;
        $log->id_objek = $idObjek;
        $log->entitas = $entitas;
        $changes = [];

        if ($previousData === null || $newData === null) {
            $changes = $newData;
        } else {
            foreach ($previousData as $key => $value) {
                if (array_key_exists($key, $newData)) {
                    if (!in_array($key, ['created_at', 'updated_at'])) {
                        if (is_array($value) || is_array($newData[$key])) {
                            if (json_encode($value) !== json_encode($newData[$key])) {
                                $changes[$key] = [
                                    'old' => $value,
                                    'new' => $newData[$key],
                                ];
                            }
                        } else {
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
