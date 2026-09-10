<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class GlareLanding extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    protected $fillable = [
        'hero_title',
        'hero_lead',
        'cta_label',
        'scroll_label',
        'back_label',
        'contact_eyebrow',
        'contact_title',
        'contact_lead',
        'contact_banner',
        'submit_label',
        'trust_line',
        'project_types',
        'meta_title',
        'meta_description',
        'is_published',
    ];

    public array $translatable = [
        'hero_title',
        'hero_lead',
        'cta_label',
        'scroll_label',
        'back_label',
        'contact_eyebrow',
        'contact_title',
        'contact_lead',
        'contact_banner',
        'submit_label',
        'trust_line',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'project_types' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_image')->singleFile();
        $this->addMediaCollection('contact_image')->singleFile();
        $this->addMediaCollection('logo')->singleFile();
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], static::defaults());
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'hero_title' => [
                'en' => "Designing Spaces.\nCreating Experiences.",
                'ar' => "نصمم المساحات.\nنصنع التجارب.",
            ],
            'hero_lead' => [
                'en' => 'Bespoke interior design solutions where creativity, functionality, and refined details come together.',
                'ar' => 'حلول تصميم داخلي مخصصة حيث تلتقي الإبداع والوظيفة والتفاصيل الراقية.',
            ],
            'cta_label' => [
                'en' => 'Start Your Project',
                'ar' => 'ابدأ مشروعك',
            ],
            'scroll_label' => [
                'en' => 'Scroll to contact',
                'ar' => 'انتقل للتواصل',
            ],
            'back_label' => [
                'en' => 'Back to Areva',
                'ar' => 'عودة إلى أريفا',
            ],
            'contact_eyebrow' => [
                'en' => 'Get in touch',
                'ar' => 'تواصل معنا',
            ],
            'contact_title' => [
                'en' => "Let's Create Something Exceptional",
                'ar' => 'لنصنع شيئاً استثنائياً',
            ],
            'contact_lead' => [
                'en' => 'Tell us about your space and vision. Our team will get in touch to discuss how we can bring your project to life.',
                'ar' => 'أخبرنا عن مساحتك ورؤيتك. سيتواصل فريقنا لمناقشة كيفية إحياء مشروعك.',
            ],
            'contact_banner' => [
                'en' => 'Interiors that reflect a higher standard',
                'ar' => 'تصاميم تعكس معياراً أعلى',
            ],
            'submit_label' => [
                'en' => 'Send Inquiry',
                'ar' => 'إرسال الاستفسار',
            ],
            'trust_line' => [
                'en' => 'Your information is kept confidential.',
                'ar' => 'معلوماتك محفوظة بسرية تامة.',
            ],
            'meta_title' => [
                'en' => 'GLARE — Designing Spaces. Creating Experiences.',
                'ar' => 'GLARE — نصمم المساحات ونصنع التجارب',
            ],
            'meta_description' => [
                'en' => 'Bespoke interior design solutions from GLARE where creativity, functionality, and refined details come together.',
                'ar' => 'حلول تصميم داخلي مخصصة من GLARE حيث تلتقي الإبداع والوظيفة والتفاصيل الراقية.',
            ],
            'project_types' => [
                [
                    'value' => 'Residential Interior',
                    'label' => ['en' => 'Residential Interior', 'ar' => 'تصميم داخلي سكني'],
                ],
                [
                    'value' => 'Commercial Space',
                    'label' => ['en' => 'Commercial Space', 'ar' => 'مساحة تجارية'],
                ],
                [
                    'value' => 'Full Home Renovation',
                    'label' => ['en' => 'Full Home Renovation', 'ar' => 'تجديد منزل كامل'],
                ],
                [
                    'value' => 'Consultation',
                    'label' => ['en' => 'Consultation', 'ar' => 'استشارة'],
                ],
                [
                    'value' => 'Other',
                    'label' => ['en' => 'Other', 'ar' => 'أخرى'],
                ],
            ],
            'is_published' => true,
        ];
    }

    public function localized(string $field, ?string $locale = null, ?string $fallback = ''): string
    {
        $locale ??= app()->getLocale();
        $value = $this->getTranslation($field, $locale, false);

        if (filled($value)) {
            return (string) $value;
        }

        $english = $this->getTranslation($field, 'en', false);

        return filled($english) ? (string) $english : (string) $fallback;
    }

    /**
     * @return array<string, string>
     */
    public function projectTypeOptions(?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $types = is_array($this->project_types) ? $this->project_types : [];
        $options = [];

        foreach ($types as $type) {
            if (! is_array($type) || blank($type['value'] ?? null)) {
                continue;
            }

            $label = $type['label'][$locale]
                ?? $type['label']['en']
                ?? $type['value'];

            $options[(string) $type['value']] = (string) $label;
        }

        return $options;
    }

    public function heroImageUrl(): string
    {
        return $this->getFirstMediaUrl('hero_image') ?: asset('assets/images/hero.jpg');
    }

    public function contactImageUrl(): string
    {
        return $this->getFirstMediaUrl('contact_image') ?: asset('assets/images/villa-modern.jpg');
    }

    public function logoUrl(): string
    {
        return $this->getFirstMediaUrl('logo') ?: asset('assets/images/glare-logo-white.png');
    }
}
