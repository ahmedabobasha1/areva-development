<?php

namespace Tests\Feature;

use App\Models\Glare1Landing;
use App\Models\Setting;
use Tests\TestCase;
use Throwable;

class Glare1ControllerTest extends TestCase
{
    public function test_glare1_landing_renders_for_english(): void
    {
        $this->ensureGlare1Landing();

        $this->get('/en/glare1')
            ->assertOk()
            ->assertSee('Designing Spaces.', false)
            ->assertSee('id="contact"', false)
            ->assertSee('class="lp-hero-form"', false)
            ->assertSee('Send Inquiry', false)
            ->assertSee('glare1-landing', false)
            ->assertSeeText("Let's Create Something Exceptional")
            ->assertDontSee('نصمم المساحات.', false);
    }

    public function test_glare1_landing_renders_call_actions(): void
    {
        $this->ensureGlare1Landing();
        $this->seedContactSettings();

        $this->get('/en/glare1')
            ->assertOk()
            ->assertSee('class="quick-contact"', false)
            ->assertSee('class="quick-contact-call"', false)
            ->assertSee('tel:19030', false)
            ->assertSee('class="quick-contact-whatsapp"', false)
            ->assertSee('https://wa.me/201094942833', false)
            ->assertSee('rel="preload" as="style"', false)
            ->assertDontSee('assets/js/main.js', false);
    }

    public function test_glare1_arabic_skips_google_fonts(): void
    {
        $this->ensureGlare1Landing();

        $this->get('/ar/glare1')
            ->assertOk()
            ->assertDontSee('fonts.googleapis.com', false)
            ->assertSee('rel="preload" as="image"', false)
            ->assertDontSee('assets/js/main.js', false);
    }

    public function test_glare1_landing_renders_for_arabic(): void
    {
        $this->ensureGlare1Landing();

        $this->get('/ar/glare1')
            ->assertOk()
            ->assertSee('نصمم المساحات.', false)
            ->assertDontSee('Designing Spaces.', false);
    }

    public function test_home_nav_does_not_include_glare1_tab(): void
    {
        try {
            $this->get('/en')
                ->assertOk()
                ->assertDontSee('href="'.url('/en/glare1').'"', false)
                ->assertDontSee('>GLARE1</a>', false);
        } catch (Throwable $exception) {
            if ($this->isUnavailableDatabase($exception)) {
                $this->markTestSkipped('Database driver is unavailable in this test environment.');
            }

            throw $exception;
        }
    }

    public function test_unpublished_glare1_landing_returns_not_found(): void
    {
        $glare = $this->ensureGlare1Landing();
        $glare->update(['is_published' => false]);

        try {
            $this->get('/en/glare1')->assertNotFound();
        } finally {
            $glare->update(['is_published' => true]);
        }
    }

    public function test_admin_managed_copy_appears_on_landing(): void
    {
        $glare = $this->ensureGlare1Landing();
        $originalTitle = $glare->getTranslation('hero_title', 'en');
        $originalSubmit = $glare->getTranslation('submit_label', 'en');

        $glare->setTranslation('hero_title', 'en', "Custom Spaces.\nCustom Experiences.");
        $glare->setTranslation('submit_label', 'en', 'Book a Consult');
        $glare->save();

        try {
            $this->get('/en/glare1')
                ->assertOk()
                ->assertSee('Custom Spaces.', false)
                ->assertSee('Book a Consult', false);
        } finally {
            $glare->setTranslation('hero_title', 'en', $originalTitle);
            $glare->setTranslation('submit_label', 'en', $originalSubmit);
            $glare->save();
        }
    }

    private function ensureGlare1Landing(): Glare1Landing
    {
        try {
            return Glare1Landing::current();
        } catch (Throwable $exception) {
            if ($this->isUnavailableDatabase($exception)) {
                $this->markTestSkipped('Database driver is unavailable in this test environment.');
            }

            throw $exception;
        }
    }

    private function seedContactSettings(): void
    {
        try {
            $site = Setting::getValue('site', []);
            Setting::setValue('site', array_merge(is_array($site) ? $site : [], [
                'phone' => '19030',
                'whatsapp' => '01094942833',
            ]));
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
