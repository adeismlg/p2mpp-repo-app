<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UploadLimitsTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('uploadEndpoints')]
    public function test_upload_endpoints_accept_files_at_25_megabytes(
        string $route,
        string $fileField,
        string $fileName,
        string $mimeType,
        string $disk,
        array $data,
        string $table,
        string $redirectRoute,
    ): void {
        Storage::fake($disk);
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $data[$fileField] = $this->fakeFile($fileName, $mimeType, 25_600);

        $response = $this->actingAs($staff)->post(route($route), $data);

        $response->assertRedirect(route($redirectRoute));
        $this->assertDatabaseCount($table, 1);
    }

    #[DataProvider('uploadEndpoints')]
    public function test_ajax_uploads_return_json_redirect_after_saving_the_file(
        string $route,
        string $fileField,
        string $fileName,
        string $mimeType,
        string $disk,
        array $data,
        string $table,
        string $redirectRoute,
    ): void {
        Storage::fake($disk);
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $data[$fileField] = $this->fakeFile($fileName, $mimeType, 1);

        $response = $this->actingAs($staff)
            ->withHeaders([
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->post(route($route), $data);

        $response->assertOk()
            ->assertJsonPath('redirect', route($redirectRoute))
            ->assertJsonStructure(['message']);
        $response->assertSessionHas('status');
        $this->assertDatabaseCount($table, 1);
        $storedFiles = Storage::disk($disk)->allFiles();
        $this->assertCount(1, $storedFiles);
        $this->assertStringNotContainsString($fileName, basename($storedFiles[0]));
    }

    #[DataProvider('publicImageUploadEndpoints')]
    public function test_public_image_uploads_remove_appended_script_payloads(
        string $route,
        string $fileField,
        array $data,
        ?string $table,
        ?string $pathColumn,
        ?string $redirectRoute,
    ): void {
        Storage::fake('public');
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $image = UploadedFile::fake()->image('gambar.png', 10, 10);
        file_put_contents($image->getPathname(), $image->get()."\n<?php echo 'UPLOAD_SCRIPT_PAYLOAD'; ?>");
        $data[$fileField] = $image;

        $response = $this->actingAs($staff)->post(route($route), $data);

        if ($table === null) {
            $response->assertOk()->assertJsonStructure(['location']);
            $path = substr((string) parse_url($response->json('location'), PHP_URL_PATH), strlen('/storage/'));
        } else {
            $response->assertRedirect(route($redirectRoute));
            $path = DB::table($table)->value($pathColumn);
        }

        $storedImage = Storage::disk('public')->get($path);
        $this->assertNotFalse(getimagesizefromstring($storedImage));
        $this->assertStringNotContainsString('UPLOAD_SCRIPT_PAYLOAD', $storedImage);
    }

    #[DataProvider('oversizedUploadEndpoints')]
    public function test_upload_endpoints_reject_files_over_25_megabytes(
        string $route,
        string $fileField,
        string $fileName,
        string $mimeType,
        string $disk,
        array $data,
        string $table,
    ): void {
        Storage::fake($disk);
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);
        $data[$fileField] = $this->fakeFile($fileName, $mimeType, 25_601);

        $response = $this->actingAs($staff)->from(route($this->createRoute($route)))->post(route($route), $data);

        $response->assertSessionHasErrors($fileField);
        $this->assertDatabaseCount($table, 0);
        $this->assertSame([], Storage::disk($disk)->allFiles());
    }

    public function test_upload_forms_show_progress_and_the_25_megabyte_limit(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);

        foreach ([
            'admin.documents.create',
            'admin.accreditation-evidences.create',
            'admin.courses.create',
            'admin.news.create',
            'admin.sliders.create',
        ] as $route) {
            $this->actingAs($staff)
                ->get(route($route))
                ->assertOk()
                ->assertSee('data-upload-progress-form', false)
                ->assertSee('data-upload-progress', false)
                ->assertSee('data-upload-progress-bar', false)
                ->assertSee('25 MB');
        }
    }

    public static function uploadEndpoints(): array
    {
        return [
            'documents' => [
                'admin.documents.store',
                'file',
                'dokumen.pdf',
                'application/pdf',
                'documents',
                ['title' => 'Dokumen uji'],
                'documents',
                'admin.documents.index',
            ],
            'accreditation evidence' => [
                'admin.accreditation-evidences.store',
                'evidence_file',
                'bukti.pdf',
                'application/pdf',
                'local',
                [
                    'scope' => 'institution',
                    'criterion_code' => 'C.1',
                    'criterion_name' => 'Tata pamong',
                    'evidence_title' => 'Dokumen kebijakan',
                    'status' => 'draft',
                ],
                'accreditation_evidence',
                'admin.accreditation-evidences.index',
            ],
            'course image' => [
                'admin.courses.store',
                'thumbnail',
                'pelatihan.png',
                'image/png',
                'public',
                ['name' => 'Pelatihan uji'],
                'courses',
                'admin.courses.index',
            ],
            'news image' => [
                'admin.news.store',
                'thumbnail',
                'berita.png',
                'image/png',
                'public',
                ['title' => 'Berita uji', 'content' => 'Konten berita'],
                'news',
                'admin.news.index',
            ],
            'slider image' => [
                'admin.sliders.store',
                'image',
                'slider.png',
                'image/png',
                'public',
                ['title' => 'Slider uji', 'order' => 1],
                'sliders',
                'admin.sliders.index',
            ],
        ];
    }

    public static function oversizedUploadEndpoints(): array
    {
        return array_map(
            static fn (array $endpoint): array => array_slice($endpoint, 0, 7),
            self::uploadEndpoints(),
        );
    }

    public static function publicImageUploadEndpoints(): array
    {
        return [
            'rich content images' => [
                'admin.editor-images.store',
                'file',
                [],
                null,
                null,
                null,
            ],
            'course thumbnails' => [
                'admin.courses.store',
                'thumbnail',
                ['name' => 'Pelatihan uji'],
                'courses',
                'thumbnail',
                'admin.courses.index',
            ],
            'news thumbnails' => [
                'admin.news.store',
                'thumbnail',
                ['title' => 'Berita uji', 'content' => 'Konten berita'],
                'news',
                'thumbnail',
                'admin.news.index',
            ],
            'slider images' => [
                'admin.sliders.store',
                'image',
                ['title' => 'Slider uji', 'order' => 1],
                'sliders',
                'image',
                'admin.sliders.index',
            ],
        ];
    }

    private function fakeFile(string $fileName, string $mimeType, int $sizeKilobytes): UploadedFile
    {
        $file = str_starts_with($mimeType, 'image/')
            ? UploadedFile::fake()->image($fileName, 1, 1)
            : UploadedFile::fake()->create($fileName, 1, $mimeType);

        return $file->size($sizeKilobytes);
    }

    private function createRoute(string $storeRoute): string
    {
        return match ($storeRoute) {
            'admin.documents.store' => 'admin.documents.create',
            'admin.accreditation-evidences.store' => 'admin.accreditation-evidences.create',
            'admin.courses.store' => 'admin.courses.create',
            'admin.news.store' => 'admin.news.create',
            'admin.sliders.store' => 'admin.sliders.create',
        };
    }
}
