<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@areva.com.eg'],
            [
                'name' => 'Areva Admin',
                'password' => Hash::make('password'),
            ],
        );

        $categories = [
            [
                'name' => ['en' => 'Real Estate', 'ar' => 'عقارات'],
                'slug' => ['en' => 'real-estate', 'ar' => 'aqarat'],
                'description' => [
                    'en' => 'News, trends and analysis about the real estate market across Egypt.',
                    'ar' => 'أخبار وتحليلات واتجاهات سوق العقارات في مصر.',
                ],
                'sort' => 1,
                'parent_slug' => null,
            ],
            [
                'name' => ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'],
                'slug' => ['en' => 'new-cairo', 'ar' => 'al-qahira-al-jadida'],
                'description' => [
                    'en' => 'Living, buying and investing guides for New Cairo.',
                    'ar' => 'أدلة السكن والشراء والاستثمار في القاهرة الجديدة.',
                ],
                'sort' => 2,
                'parent_slug' => 'real-estate',
            ],
            [
                'name' => ['en' => 'New Capital', 'ar' => 'العاصمة الإدارية'],
                'slug' => ['en' => 'new-capital', 'ar' => 'al-asima-al-idariya'],
                'description' => [
                    'en' => 'Projects and opportunities in Egypt’s New Administrative Capital.',
                    'ar' => 'مشروعات وفرص في العاصمة الإدارية الجديدة.',
                ],
                'sort' => 3,
                'parent_slug' => 'real-estate',
            ],
            [
                'name' => ['en' => 'Investment', 'ar' => 'استثمار'],
                'slug' => ['en' => 'investment', 'ar' => 'istithmar'],
                'description' => [
                    'en' => 'Property investment strategies and returns in Egypt.',
                    'ar' => 'استراتيجيات وعوائد الاستثمار العقاري في مصر.',
                ],
                'sort' => 4,
                'parent_slug' => null,
            ],
            [
                'name' => ['en' => 'Guides', 'ar' => 'أدلة'],
                'slug' => ['en' => 'guides', 'ar' => 'adilla'],
                'description' => [
                    'en' => 'Practical step-by-step guides for buying and selling property.',
                    'ar' => 'أدلة عملية خطوة بخطوة لشراء وبيع العقارات.',
                ],
                'sort' => 5,
                'parent_slug' => null,
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $data) {
            $category = Category::query()->updateOrCreate(
                ['sort' => $data['sort']],
                [
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'description' => $data['description'],
                    'meta_title' => $data['name'],
                    'meta_description' => $data['description'],
                    'robots_index' => true,
                    'robots_follow' => true,
                    'is_active' => true,
                    'sort' => $data['sort'],
                    'parent_id' => null,
                ],
            );
            $categoryModels[$data['slug']['en']] = $category;
        }

        foreach ($categories as $data) {
            if (! $data['parent_slug']) {
                continue;
            }

            $categoryModels[$data['slug']['en']]->update([
                'parent_id' => $categoryModels[$data['parent_slug']]->id,
            ]);
        }

        $newCairo = $categoryModels['new-cairo'];

        $article = Article::query()->updateOrCreate(
            ['category_id' => $newCairo->id],
            [
                'title' => [
                    'en' => 'The Future of Modern Living in New Cairo',
                    'ar' => 'مستقبل المعيشة الحديثة في القاهرة الجديدة',
                ],
                'slug' => [
                    'en' => 'future-of-modern-living-in-new-cairo',
                    'ar' => 'mustaqbal-al-maisha-al-haditha-fi-al-qahira-al-jadida',
                ],
                'excerpt' => [
                    'en' => 'Discover how New Cairo is shaping the future of modern living in Egypt.',
                    'ar' => 'اكتشف كيف تشكّل القاهرة الجديدة مستقبل المعيشة الحديثة في مصر.',
                ],
                'body' => [
                    'en' => '<p>New Cairo has rapidly evolved from a quiet suburban extension into one of Egypt\'s most sought-after residential and commercial hubs.</p><h2>Why New Cairo Leads the Way</h2><p>Strategic location, luxury compounds, and strong rental yields continue to attract buyers and investors.</p>',
                    'ar' => '<p>تحوّلت القاهرة الجديدة بسرعة من امتداد سكني هادئ إلى واحدة من أبرز الوجهات السكنية والتجارية في مصر.</p><h2>لماذا تتقدّم القاهرة الجديدة</h2><p>الموقع الاستراتيجي والكمبوندات الفاخرة والعوائد الإيجارية القوية تجذب المشترين والمستثمرين.</p>',
                ],
                'meta_title' => [
                    'en' => 'The Future of Modern Living in New Cairo — Areva Development',
                    'ar' => 'مستقبل المعيشة الحديثة في القاهرة الجديدة — أريفا',
                ],
                'meta_description' => [
                    'en' => 'Discover how New Cairo is shaping the future of modern living in Egypt with luxury compounds and investment opportunities.',
                    'ar' => 'اكتشف كيف تشكّل القاهرة الجديدة مستقبل المعيشة الحديثة مع الكمبوندات الفاخرة وفرص الاستثمار.',
                ],
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now()->subDays(10),
                'is_featured' => true,
                'is_trending' => true,
                'read_time_minutes' => 8,
                'robots_index' => true,
                'robots_follow' => true,
            ],
        );

        HeroSlide::query()->updateOrCreate(
            ['sort' => 1],
            [
                'title' => [
                    'en' => 'The Future of Modern Living in New Cairo',
                    'ar' => 'مستقبل المعيشة الحديثة في القاهرة الجديدة',
                ],
                'subtitle' => [
                    'en' => 'Expert insights for buyers and investors across Egypt.',
                    'ar' => 'رؤى متخصصة للمشترين والمستثمرين في أنحاء مصر.',
                ],
                'cta_label' => [
                    'en' => 'Read the Article',
                    'ar' => 'اقرأ المقال',
                ],
                'cta_url' => null,
                'article_id' => $article->id,
                'is_active' => true,
                'sort' => 1,
            ],
        );

        Setting::setValue('site', [
            'name' => ['en' => 'Areva Development', 'ar' => 'أريفا للتطوير'],
            'email' => 'info@areva.com.eg',
            'phone' => '+20 100 323 4567',
            'address' => ['en' => 'New Cairo, Cairo, Egypt', 'ar' => 'القاهرة الجديدة، القاهرة، مصر'],
            'footer_blurb' => [
                'en' => 'Creating exceptional spaces that empower businesses and individuals to thrive across Egypt’s leading real estate markets.',
                'ar' => 'نصنع مساحات استثنائية تمكّن الأفراد والأعمال من الازدهار في أبرز أسواق العقارات في مصر.',
            ],
        ]);

        Setting::setValue('seo_defaults', [
            'meta_title' => [
                'en' => 'Areva Development Blog — Real Estate Insights in Egypt',
                'ar' => 'مدونة أريفا — رؤى عقارية في مصر',
            ],
            'meta_description' => [
                'en' => 'Expert analysis, market trends and real estate guides for property decisions in Egypt.',
                'ar' => 'تحليلات خبراء واتجاهات السوق وأدلة عقارية لقرارات التملك في مصر.',
            ],
        ]);

        Setting::setValue('organization', [
            'name' => 'Areva Development',
            'url' => 'https://www.areva-development.com/',
            'logo' => '/assets/images/logo.png',
        ]);

        Setting::setValue('social', [
            'instagram' => '#',
            'facebook' => '#',
            'twitter' => '#',
            'linkedin' => '#',
        ]);
    }
}
