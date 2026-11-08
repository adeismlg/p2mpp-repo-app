<?php

namespace App\Models;

use Database\Factories\AccreditationEvidenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccreditationEvidence extends Model
{
    /** @use HasFactory<AccreditationEvidenceFactory> */
    use HasFactory;

    public const STATUSES = [
        'draft' => 'Belum dikumpulkan',
        'in_progress' => 'Dalam proses',
        'review' => 'Menunggu validasi',
        'complete' => 'Lengkap',
    ];

    public const SCOPES = [
        'institution' => 'Institusi',
        'program' => 'Program studi',
        'unit' => 'Unit kerja',
    ];

    protected $fillable = [
        'instrument',
        'scope',
        'assessment_entity',
        'criterion_code',
        'criterion_name',
        'evidence_title',
        'evidence_path',
        'owner_id',
        'status',
        'due_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
