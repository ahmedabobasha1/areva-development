<?php

namespace Tests\Feature;

use App\Models\GlareLanding;
use Tests\TestCase;
use Throwable;

class GlareControllerTest extends TestCase
{
    public function test_glare_landing_renders_for_english(): void
    {
        $this->ensureGlareLanding();

        $this->get('/en/glare')
            ->assertOk()
            ->assertSee('Designing Spaces.', false)
            ->assertSee('GLARE', false)
            ->assertSee('id="contact"', false)
            ->assertSee('class="lp-hero-form"', false)
            ->assertSee('Send Inquiry', false)
            ->assertSee('glare-landing', false)
            ->assertSeeText("Let's Create Something Exceptional")
            ->assertDontSee('نصمم المساحات.', false);
    }

    public function test_glare_landing_renders_for_arabic(): void
    {
        $this->ensureGlareLanding();

        $this->get('/ar/glare')
            ->assertOk()
            ->assertSee('نصمم المساحات.', false)
            ->assertSee('GLARE', false)
            ->assertDontSee('Designing Spaces.', false);
    }

    public function test_home_nav_includes_glare_tab(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('href="'.url('/en/glare').'"', false)
            ->assertSee('>GLARE</a>', false);
    }

    public function test_unpublished_glare_landing_returns_not_found(): void
    {
        $glare = $this->ensureGlareLanding();
        $glare->update(['is_published' => false]);

        try {
            $this->get('/en/glare')->assertNotFound();
        } finally {
            $glare->update(['is_published' => true]);
        }
    }

    public function test_admin_managed_copy_appears_on_landing(): void
    {
        $glare = $this->ensureGlareLanding();
        $originalTitle = $glare->getTranslation('hero_title', 'en');
        $originalSubmit = $glare->getTranslation('submit_label', 'en');

        $glare->setTranslation('hero_title', 'en', "Custom Spaces.\nCustom Experiences.");
        $glare->setTranslation('submit_label', 'en', 'Book a Consult');
        $glare->save();

        try {
            $this->get('/en/glare')
                ->assertOk()
                ->assertSee('Custom Spaces.', false)
                ->assertSee('Book a Consult', false);
        } finally {
            $glare->setTranslation('hero_title', 'en', $originalTitle);
            $glare->setTranslation('submit_label', 'en', $originalSubmit);
            $glare->save();
        }
    }

    private function ensureGlareLanding(): GlareLanding
    {
        try {
            return GlareLanding::current();
        } catch (Throwable $exception) {
            if ($this->isUnavailableDatabase($exception)) {
                $this->markTestSkipped('Database driver is unavailable in this test environment.');
            }

            throw $exception;
        }
    }

    private function isUnavailableDatabase(Throwable $exception): bool
    {
        $message = $exception->getMessage();

        return str_contains($message, 'could not find driver')
            || str_contains($message, 'no such table')
            || str_contains($message, 'Base table or view not found');
    }
}
