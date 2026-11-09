<?php

namespace App\Models;

use Database\Factories\StudyProgramAccreditationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgramAccreditation extends Model
{
    /** @use HasFactory<StudyProgramAccreditationFactory> */
    use HasFactory;

    protected $fillable = [
        'source_id',
        'study_level',
        'decree_number',
        'name',
        'name_en',
        'rating',
        'valid_until',
        'source_document_name',
        'certificate_path',
        'certificate_file_name',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
        ];
    }

    public function localized(string $attribute): mixed
    {
        return app()->getLocale() === 'en' && filled($this->getAttribute($attribute.'_en'))
            ? $this->getAttribute($attribute.'_en')
            : $this->getAttribute($attribute);
    }

    public function scopeCurrentlyValid(Builder $query): void
    {
        $query->whereDate('valid_until', '>=', today());
    }

    public function scopeLatestCurrentlyValid(Builder $query): void
    {
        $query->currentlyValid()
            ->whereNotExists(function ($subquery): void {
                $subquery->selectRaw('1')
                    ->from('study_program_accreditations as newer_accreditations')
                    ->whereColumn('newer_accreditations.name', 'study_program_accreditations.name')
                    ->whereColumn('newer_accreditations.study_level', 'study_program_accreditations.study_level')
                    ->whereColumn('newer_accreditations.valid_until', '>', 'study_program_accreditations.valid_until');
            });
    }
}
