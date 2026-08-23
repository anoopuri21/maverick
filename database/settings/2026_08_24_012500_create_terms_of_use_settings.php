<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('terms_page.tag', 'LEGAL');
        $this->migrator->add('terms_page.heading_line1', 'Terms of');
        $this->migrator->add('terms_page.heading_italic', 'Use');
        $this->migrator->add('terms_page.description', 'The terms that govern your use of the Maverick Business Academy London website and related services.');
        $this->migrator->add('terms_page.background_image', null);
        $this->migrator->add('terms_page.background_image_asset_id', null);
        $this->migrator->add('terms_page.center_image', null);
        $this->migrator->add('terms_page.center_image_asset_id', null);
        $this->migrator->add('terms_page.center_image_alt', 'Terms of Use');
        $this->migrator->add('terms_page.body', <<<'HTML'
<p>Welcome to the website of Maverick Business Academy London. By accessing or using this website, submitting an enquiry, applying for a programme, or using any of our services, you agree to comply with the following Terms of Use.</p>

<h2>1. About Us</h2>
<p>This website represents Maverick Business Academy London and its educational services. Current admissions support, student coordination, commercial services, and related operations are managed by Maverick Business Academy FZE, United Arab Emirates.</p>

<h2>2. Use of Website</h2>
<p>You may use this website for lawful purposes only. You agree not to misuse the website, interfere with its operation, attempt unauthorized access, upload harmful content, or use the website in a way that may damage the reputation, services, or systems of Maverick Business Academy.</p>

<h2>3. Programme Information</h2>
<p>The information provided on this website regarding programmes, courses, fees, scholarships, duration, delivery mode, university partners, and admission requirements is for general guidance only. While we aim to keep all information accurate and updated, details may change from time to time based on academic partner requirements, university policies, regulatory updates, or operational decisions.</p>
<p>Students and applicants are advised to confirm the latest programme details with our admissions team before making any admission or payment decision.</p>

<h2>4. Admissions and Eligibility</h2>
<p>Submission of an enquiry or application does not guarantee admission. Admission is subject to eligibility checks, document verification, academic partner requirements, payment completion, and approval by the relevant institution or university partner.</p>
<p>Maverick Business Academy reserves the right to request additional documents or information to process an application.</p>

<h2>5. Fees, Payments, and Scholarships</h2>
<p>Programme fees, payment plans, and scholarship offers may vary depending on the programme, intake, eligibility, and promotional period. Scholarship offers are subject to availability, approval, and applicable terms.</p>
<p>All payment-related information will be communicated officially through authorized Maverick Business Academy representatives or approved payment channels.</p>

<h2>6. Student Responsibilities</h2>
<p>Students and applicants are responsible for providing accurate personal, academic, and professional information. Any false, incomplete, or misleading information may result in delays, rejection of application, cancellation of admission, or other necessary action.</p>
<p>Students are also responsible for meeting academic deadlines, attending required sessions where applicable, submitting assignments, completing payments, and following the rules and policies of Maverick Business Academy and the relevant academic partner.</p>

<h2>7. Third-Party Universities and Academic Partners</h2>
<p>Some programmes may be delivered, awarded, accredited, or supported through third-party universities, awarding bodies, or academic partners. Their own academic regulations, admission criteria, assessment rules, certification timelines, and policies may apply.</p>
<p>Maverick Business Academy acts as an educational service provider, representative, or support center where applicable and does not control all decisions made by external universities, awarding bodies, or regulatory authorities.</p>

<h2>8. Recognition, Equivalency, and Attestation</h2>
<p>Recognition, equivalency, attestation, licensing, employment acceptance, immigration acceptance, and professional body approval may vary by country, authority, employer, and individual circumstances.</p>
<p>Maverick Business Academy may provide guidance and supporting information; however, final decisions are made by the relevant government authority, employer, ministry, professional body, or third-party agency. Students are encouraged to verify recognition or equivalency requirements before enrollment if this is important for their personal, professional, or government-related purpose.</p>

<h2>9. Website Content</h2>
<p>All content on this website, including text, images, graphics, logos, brochures, videos, designs, and other materials, is owned by or licensed to Maverick Business Academy unless otherwise stated. You may not copy, reproduce, modify, distribute, or use our content for commercial purposes without prior written permission.</p>

<h2>10. External Links</h2>
<p>This website may contain links to third-party websites. These links are provided for convenience only. Maverick Business Academy is not responsible for the content, accuracy, privacy practices, or security of external websites.</p>

<h2>11. Limitation of Liability</h2>
<p>While we make reasonable efforts to ensure that the information on this website is accurate and useful, Maverick Business Academy does not guarantee that the website will always be error-free, uninterrupted, or fully updated.</p>
<p>Maverick Business Academy shall not be liable for any direct, indirect, incidental, or consequential loss arising from the use of this website, reliance on website information, third-party links, or decisions made based on the content available on this website.</p>

<h2>12. Privacy and Data Protection</h2>
<p>Your use of this website is also governed by our <a href="/privacy-policy">Privacy Policy</a>, which explains how we collect, use, protect, and manage personal information. Please review our Privacy Policy for more details.</p>

<h2>13. Changes to These Terms</h2>
<p>Maverick Business Academy may update or revise these Terms of Use at any time. Any changes will be published on this page. Continued use of the website after changes are posted means you accept the updated terms.</p>

<h2>14. Contact Us</h2>
<p>For any questions regarding these Terms of Use, please contact us:</p>
<p>Maverick Business Academy FZE<br>
Office 201, 2nd Floor, Robot Park Tower, Sharjah, United Arab Emirates<br>
Email: <a href="mailto:admissions@mbalondon.org.uk">admissions@mbalondon.org.uk</a><br>
Website: <a href="https://www.mbalondon.org.uk" target="_blank" rel="noopener noreferrer">www.mbalondon.org.uk</a></p>
<p><strong>Effective Date:</strong> 2012–2026</p>
HTML);

        $this->migrator->add('terms_seo.meta_title', 'Terms of Use | Maverick Business Academy London');
        $this->migrator->add('terms_seo.meta_description', 'Terms of Use for the Maverick Business Academy London website, programmes, admissions, and related educational services.');
        $this->migrator->add('terms_seo.meta_keywords', 'terms of use, terms and conditions, Maverick Business Academy London');
        $this->migrator->add('terms_seo.canonical_url', null);
        $this->migrator->add('terms_seo.robots', 'index, follow');
        $this->migrator->add('terms_seo.og_title', null);
        $this->migrator->add('terms_seo.og_description', null);
        $this->migrator->add('terms_seo.og_image_url', null);
        $this->migrator->add('terms_seo.og_image_url_asset_id', null);
        $this->migrator->add('terms_seo.og_type', 'website');
        $this->migrator->add('terms_seo.twitter_card', 'summary_large_image');
        $this->migrator->add('terms_seo.twitter_title', null);
        $this->migrator->add('terms_seo.twitter_description', null);
        $this->migrator->add('terms_seo.twitter_image_url', null);
        $this->migrator->add('terms_seo.twitter_image_url_asset_id', null);
        $this->migrator->add('terms_seo.schema_json', null);
        $this->migrator->add('terms_seo.custom_head_scripts', null);
        $this->migrator->add('terms_seo.custom_body_scripts', null);
    }
};
