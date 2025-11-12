<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'urgency'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected $appends = [
        'status_text',
        'urgency_text'
    ];

    public const STATUS_OPTIONS = [
        'pending' => 'Pendiente',
        'in_progress' => 'En Progreso',
        'completed' => 'Completada'
    ];

    public const URGENCY_OPTIONS = [
        'Baja' => 'Baja',
        'Media' => 'Media',
        'Alta' => 'Alta'
    ];

    public function getStatusTextAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? 'Desconocido';
    }

    public function getUrgencyTextAttribute(): string
    {
        return self::URGENCY_OPTIONS[$this->urgency] ?? 'Desconocida';
    }
}
