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
}
