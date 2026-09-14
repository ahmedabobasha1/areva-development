<?php

namespace Tests\Unit;

use App\Support\RichHtml;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RichHtmlTest extends TestCase
{
    #[Test]
    public function it_converts_logical_text_align_to_physical(): void
    {
        $html = '<p style="text-align: end;">يمين</p><h2 style="text-align: start;">يسار</h2>';

        $this->assertSame(
            '<p style="text-align: right;">يمين</p><h2 style="text-align: left;">يسار</h2>',
            RichHtml::normalizeTextAlign($html),
        );
    }
}
