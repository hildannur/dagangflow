<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'category',
        'priority',
        'status',
        'message',
        'admin_reply',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'Baru',
            'in_progress' => 'Diproses',
            'resolved' => 'Selesai',
            default => 'Baru',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Rendah',
            'normal' => 'Normal',
            'high' => 'Tinggi',
            'urgent' => 'Urgent',
            default => 'Normal',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'open' => 'bg-amber-50 text-amber-700',
            'in_progress' => 'bg-blue-50 text-blue-700',
            'resolved' => 'bg-emerald-50 text-emerald-700',
            default => 'bg-amber-50 text-amber-700',
        };
    }

    public function getPriorityClassAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bg-slate-100 text-slate-600',
            'normal' => 'bg-blue-50 text-blue-700',
            'high' => 'bg-amber-50 text-amber-700',
            'urgent' => 'bg-red-50 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }
}
