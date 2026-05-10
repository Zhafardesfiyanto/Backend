<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
        'ip_address',
    ];

    public static function record($userId, $action, $targetType, $targetId, $description)
    {
        self::create([
            'user_id'     => $userId,
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}

