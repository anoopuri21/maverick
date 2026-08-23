<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('privacy_page.tag', 'LEGAL');
        $this->migrator->add('privacy_page.heading_line1', 'Privacy');
        $this->migrator->add('privacy_page.heading_italic', 'Policy');
        $this->migrator->add('privacy_page.description', 'How Maverick Business Academy London collects, uses, and protects your personal information.');
        $this->migrator->add('privacy_page.background_image', null);
        $this->migrator->add('privacy_page.background_image_asset_id', null);
        $this->migrator->add('privacy_page.center_image', null);
        $this->migrator->add('privacy_page.center_image_asset_id', null);
        $this->migrator->add('privacy_page.center_image_alt', 'Privacy Policy');
        $this->migrator->add('privacy_page.body', <<<'HTML'
<p>Maverick Business Academy London respects your privacy and is committed to protecting the personal information you share with us through our website, enquiry forms, social media campaigns, email communications, WhatsApp, telephone calls, and other communication channels.</p>

<h2>Information We Collect</h2>
<p>We may collect personal information such as your name, email address, phone number, nationality, academic background, professional experience, course preferences, enquiry details, and any documents you voluntarily share with us for admission, counselling, or academic purposes.</p>
<p>We may also collect limited technical information such as website usage data, browser type, device information, IP address, and cookies to improve website performance and user experience.</p>

<h2>How We Use Your Information</h2>
<p>We use your personal information to:</p>
<ul>
<li>Respond to your enquiries</li>
<li>Provide course information and admission guidance</li>
<li>Assess eligibility for academic programmes</li>
<li>Share brochures, fee details, scholarships, and event updates</li>
<li>Process applications and student documentation</li>
<li>Improve our website, services, and communication</li>
<li>Contact you through phone, email, WhatsApp, SMS, or other approved communication channels</li>
</ul>

<h2>Sharing of Information</h2>
<p>We do not sell your personal information. However, we may share relevant information with our academic partners, university partners, admissions teams, student support teams, payment providers, IT service providers, or regulatory bodies where required for admission processing, student support, verification, or legal compliance.</p>

<h2>Data Protection and Security</h2>
<p>We take reasonable steps to protect your personal information from unauthorized access, misuse, loss, alteration, or disclosure. Access to personal data is limited to authorized team members who require the information for official academic, admission, or administrative purposes.</p>

<h2>Cookies</h2>
<p>Our website may use cookies and similar technologies to improve user experience, analyze website traffic, and support marketing activities. You may disable cookies through your browser settings; however, some website features may not function properly.</p>

<h2>Marketing Communications</h2>
<p>By submitting an enquiry or sharing your contact details, you agree that Maverick Business Academy London may contact you regarding programmes, admissions, scholarships, events, and related educational services. You may request to stop receiving marketing communications at any time.</p>

<h2>Data Retention</h2>
<p>We retain personal information only for as long as necessary for admission, student support, academic administration, legal, regulatory, and record-keeping purposes.</p>

<h2>Your Rights</h2>
<p>You may request access to your personal information, correction of inaccurate details, deletion where applicable, or withdrawal from marketing communications by contacting us.</p>

<h2>External Links</h2>
<p>Our website may contain links to external websites. Maverick Business Academy London is not responsible for the privacy practices, content, or security of third-party websites.</p>

<h2>Contact Us</h2>
<p>For any privacy-related questions or requests, please contact us at:</p>
<p>Maverick Business Academy London<br>
Email: <a href="mailto:askus@mbalondon.org.uk">askus@mbalondon.org.uk</a><br>
Website: <a href="https://www.mbalondon.org.uk" target="_blank" rel="noopener noreferrer">www.mbalondon.org.uk</a></p>

<h2>Updates to This Policy</h2>
<p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with the updated effective date.</p>
<p><strong>Effective Date:</strong> 2012</p>
HTML);

        $this->migrator->add('privacy_seo.meta_title', 'Privacy Policy | Maverick Business Academy London');
        $this->migrator->add('privacy_seo.meta_description', 'Learn how Maverick Business Academy London collects, uses, and protects your personal information across our website and communication channels.');
        $this->migrator->add('privacy_seo.meta_keywords', 'privacy policy, data protection, Maverick Business Academy London');
        $this->migrator->add('privacy_seo.canonical_url', null);
        $this->migrator->add('privacy_seo.robots', 'index, follow');
        $this->migrator->add('privacy_seo.og_title', null);
        $this->migrator->add('privacy_seo.og_description', null);
        $this->migrator->add('privacy_seo.og_image_url', null);
        $this->migrator->add('privacy_seo.og_image_url_asset_id', null);
        $this->migrator->add('privacy_seo.og_type', 'website');
        $this->migrator->add('privacy_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('privacy_seo.twitter_title', null);
        $this->migrator->add('privacy_seo.twitter_description', null);
        $this->migrator->add('privacy_seo.twitter_image_url', null);
        $this->migrator->add('privacy_seo.twitter_image_url_asset_id', null);
        $this->migrator->add('privacy_seo.schema_json', null);
        $this->migrator->add('privacy_seo.custom_head_scripts', null);
        $this->migrator->add('privacy_seo.custom_body_scripts', null);
    }
};
