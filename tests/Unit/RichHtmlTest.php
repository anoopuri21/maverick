<?php

namespace Tests\Unit;

use Tests\TestCase;

class RichHtmlTest extends TestCase
{
    public function test_empty_and_blank_html_render_nothing(): void
    {
        $this->assertSame('', rich_html(null));
        $this->assertSame('', rich_html(''));
        $this->assertSame('', rich_html('   '));
        $this->assertSame('', rich_html('<p></p>'));
        $this->assertSame('', rich_html('<p><br></p>'));
        $this->assertFalse(html_filled('<p>&nbsp;</p>'));
    }

    public function test_plain_text_is_escaped_and_keeps_line_breaks(): void
    {
        $this->assertSame('Hello &amp; welcome', rich_html('Hello & welcome'));
        $this->assertSame("Line 1<br>\nLine 2", rich_html("Line 1\nLine 2"));
    }

    public function test_html_markup_is_returned_as_stored(): void
    {
        $html = '<p>Hello <strong>world</strong></p>';

        $this->assertSame($html, rich_html($html));
    }

    public function test_executable_markup_is_removed_from_stored_rich_text(): void
    {
        $html = '<p>Safe copy</p>'
            .'<script>alert("xss")</script>'
            .'<img src="/photo.jpg" onerror="alert(1)">'
            .'<a href="jav&#x09;ascript:alert(1)">Link</a>';

        $rendered = rich_html($html);

        $this->assertStringContainsString('<p>Safe copy</p>', $rendered);
        $this->assertStringNotContainsString('<script', $rendered);
        $this->assertStringNotContainsString('onerror', $rendered);
        $this->assertStringNotContainsString('javascript:', strtolower($rendered));
    }

    public function test_editor_prose_helpers_use_the_same_sanitizer(): void
    {
        $html = '<p>Chapter</p><iframe src="https://example.com"></iframe>';

        $rendered = \App\Support\MlpProse::html($html);

        $this->assertStringContainsString('<p>Chapter</p>', $rendered);
        $this->assertStringNotContainsString('<iframe', $rendered);
    }

    public function test_rich_content_images_get_lazy_and_async_hints(): void
    {
        $html = '<p>Intro</p><img src="/one.jpg"><img src="/two.jpg" loading="eager" />';

        $optimized = lazy_load_rich_content_images($html);

        $this->assertStringContainsString('<img src="/one.jpg" loading="lazy" decoding="async">', $optimized);
        $this->assertStringContainsString('<img src="/two.jpg" loading="eager" decoding="async" />', $optimized);
    }
}
