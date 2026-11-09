<?php

namespace Database\Seeders;

use App\Models\StudyProgramAccreditation;
use Illuminate\Database\Seeder;
use RuntimeException;

class StudyProgramAccreditationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/study-program-accreditations.csv');
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException("Data akreditasi tidak dapat dibuka: {$path}");
        }

        try {
            $headers = fgetcsv($handle, null, ',', '"', '');

            if ($headers === false) {
                throw new RuntimeException("Data akreditasi kosong: {$path}");
            }

            $headers = array_map('trim', $headers);

            while (($values = fgetcsv($handle, null, ',', '"', '')) !== false) {
                if (count($values) !== count($headers)) {
                    throw new RuntimeException('Baris data akreditasi memiliki jumlah kolom yang tidak sesuai.');
                }

                $row = array_combine($headers, $values);

                if (! str_starts_with($row['jenjang'], 'PROGRAM STUDI ')) {
                    continue;
                }

                $sourceId = trim($row['id']);
                $rating = ucwords(mb_strtolower(trim($row['skor'])));

                StudyProgramAccreditation::updateOrCreate(
                    ['source_id' => $sourceId],
                    [
                        'study_level' => substr(trim($row['jenjang']), strlen('PROGRAM STUDI ')),
                        'decree_number' => trim($row['sk']) ?: null,
                        'name' => trim($row['nama_prodi']),
                        'name_en' => trim($row['nama_prodi_eng']) ?: null,
                        'rating' => $rating,
                        'valid_until' => $this->parseDate(trim($row['masa_berlaku']), $sourceId),
                        'source_document_name' => trim($row['dokumen']) ?: null,
                    ],
                );
            }
        } finally {
            fclose($handle);
        }
    }

    private function parseDate(string $value, string $sourceId): string
    {
        $months = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $value, $matches)) {
            [, $day, $month, $year] = $matches;
        } elseif (preg_match('/^(\d{1,2})\s+([\p{L}]+)\s+(\d{4})$/u', $value, $matches)) {
            $day = $matches[1];
            $month = $months[mb_strtolower($matches[2])] ?? null;
            $year = $matches[3];
        } else {
            $month = null;
        }

        if (! isset($day, $month, $year) || ! checkdate((int) $month, (int) $day, (int) $year)) {
            throw new RuntimeException("Tanggal akreditasi '{$value}' pada data {$sourceId} tidak valid.");
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }
}
