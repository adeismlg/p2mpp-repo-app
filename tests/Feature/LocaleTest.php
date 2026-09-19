<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    public function test_english_locale_translates_public_interface_labels(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/language/en');

        $response->assertRedirect();
        $this->assertSame('en', app()->getLocale());
        $this->assertSame('Latest Documents', __('public.latest_documents'));
        $this->assertSame('Document Repository', __('public.document_repository'));
        $this->assertSame('Latest News', __('public.latest_news'));
    }

    public function test_locale_switch_persists_the_selected_language(): void
    {
        $response = $this->withSession(['locale' => 'id'])
            ->get('/language/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
    }

    public function test_unsupported_locale_is_rejected(): void
    {
        $this->get('/language/fr')->assertNotFound();
    }
}
