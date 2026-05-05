<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public static function record($userId, $action, $targetType, $targetId, $description)
{
    self::create([
        'user_id' => $userId,
        'action' => $action,
        'target_type' => $targetType,
        'target_id' => $targetId,
        'description' => $description,
        'ip_address' => request()->ip(),
    ]);
}
}
