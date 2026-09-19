<?php

namespace Tests\Feature;

use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SliderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_sliders_are_rendered_on_the_home_page(): void
    {
        Slider::factory()->create([
            'title' => 'Slider Aktif',
            'image' => 'sliders/active.jpg',
            'is_active' => true,
        ]);
        Slider::factory()->create([
            'title' => 'Slider Nonaktif',
            'image' => 'sliders/inactive.jpg',
            'is_active' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertSee('Slider Aktif');
        $response->assertDontSee('Slider Nonaktif');
    }

    public function test_staff_can_create_a_slider_with_an_image(): void
    {
        Storage::fake('public');
        $staff = User::factory()->create(['role' => User::ROLE_EDITOR]);

        $response = $this->actingAs($staff)->post(route('admin.sliders.store'), [
            'title' => 'Slider Baru',
            'subtitle' => 'Deskripsi slider',
            'order' => 1,
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('slider.jpg'),
        ]);

        $response->assertRedirect(route('admin.sliders.index'));
        $slider = Slider::query()->firstOrFail();
        $this->assertSame('Slider Baru', $slider->title);
        Storage::disk('public')->assertExists($slider->image);
    }

    public function test_guest_users_cannot_manage_sliders(): void
    {
        $this->get(route('admin.sliders.index'))->assertRedirect(route('login'));
    }
}
