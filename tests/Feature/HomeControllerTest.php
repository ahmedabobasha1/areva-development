<?php

namespace Tests\Feature;

use App\Models\Setting;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_home_footer_renders_contact_and_social_links(): void
    {
        $site = Setting::getValue('site', []);
        Setting::setValue('site', array_merge(is_array($site) ? $site : [], [
            'email' => 'marketing@arevadevelopment.com',
            'phone' => '19030',
        ]));

        Setting::setValue('social', [
            'instagram' => 'https://www.instagram.com/areva.development',
            'facebook' => 'https://www.facebook.com/arevadevelopment',
            'youtube' => 'https://www.youtube.com/@ArevaDevelopment',
        ]);

        $this->get('/en')
            ->assertSee('marketing@arevadevelopment.com', false)
            ->assertSee('mailto:marketing@arevadevelopment.com', false)
            ->assertSee('19030', false)
            ->assertSee('tel:19030', false)
            ->assertSee('https://www.facebook.com/arevadevelopment', false)
            ->assertSee('https://www.instagram.com/areva.development', false)
            ->assertSee('https://www.youtube.com/@ArevaDevelopment', false)
            ->assertSee('class="footer-social-icon"', false)
            ->assertDontSee('>IG</a>', false)
            ->assertDontSee('>FB</a>', false)
            ->assertDontSee('>YT</a>', false)
            ->assertDontSee('aria-label="Twitter"', false)
            ->assertDontSee('aria-label="LinkedIn"', false);
    }

    public function test_home_footer_renders_english_head_office_and_sales_offices(): void
    {
        $this->storeOfficeSettings();

        $this->get('/en')
            ->assertSee('Head Office', false)
            ->assertSee('Sheraton Heliopolis, Building 10, Al Moltaqa Al Araby st.', false)
            ->assertSee('Sales Offices', false)
            ->assertSee('Plot no 158, 90th North, New Cairo, Egypt', false);
    }

    public function test_home_footer_renders_arabic_head_office_and_sales_offices(): void
    {
        $this->storeOfficeSettings();

        $this->get('/ar')
            ->assertSee('الفرع الرئيسي', false)
            ->assertSee('مكاتب المبيعات', false)
            ->assertSee('شيراتون', false)
            ->assertSee('التجمع الخامس', false)
            ->assertSee('القطعة رقم 158، شارع التسعين الشمالي، القاهرة الجديدة، مصر', false);
    }

    private function storeOfficeSettings(): void
    {
        $site = Setting::getValue('site', []);
        Setting::setValue('site', array_merge(is_array($site) ? $site : [], [
            'address' => [
                'en' => 'Sheraton Heliopolis, Building 10, Al Moltaqa Al Araby st.',
                'ar' => '10 شارع الملتقي العربي - شيراتون - مصر الجديدة - القاهرة',
            ],
            'offices' => [
                'head_office' => [
                    'label' => ['en' => 'Head Office', 'ar' => 'الفرع الرئيسي'],
                    'address' => [
                        'en' => 'Sheraton Heliopolis, Building 10, Al Moltaqa Al Araby st.',
                        'ar' => '10 شارع الملتقي العربي - شيراتون - مصر الجديدة - القاهرة',
                    ],
                ],
                'sales' => [
                    'label' => ['en' => 'Sales Offices', 'ar' => 'مكاتب المبيعات'],
                    'locations' => [
                        [
                            'label' => ['en' => 'Sheraton', 'ar' => 'شيراتون'],
                            'address' => [
                                'en' => 'Sheraton Heliopolis, Building 10, Al Moltaqa Al Araby st.',
                                'ar' => '10 شارع الملتقي العربي - شيراتون - مصر الجديدة - القاهرة',
                            ],
                        ],
                        [
                            'label' => ['en' => 'New Cairo', 'ar' => 'التجمع الخامس'],
                            'address' => [
                                'en' => 'Plot no 158, 90th North, New Cairo, Egypt',
                                'ar' => 'القطعة رقم 158، شارع التسعين الشمالي، القاهرة الجديدة، مصر',
                            ],
                        ],
                    ],
                ],
            ],
        ]));
    }
}
