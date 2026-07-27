<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BlogController extends Controller
{
    /**
     * Get the list of 12-14 realistic dummy blog posts as objects.
     */
    protected function getDummyPosts(): Collection
    {
        return collect([
            (object) [
                'id' => 1,
                'title' => 'Unlocking Global Leadership: The Future of the Executive MBA',
                'slug' => 'unlocking-global-leadership-future-executive-mba',
                'excerpt' => 'Discover how modern Executive MBA programs are restructuring to prepare next-generation business leaders for complex global markets.',
                'content' => '<h2>The Shift in Global Executive Leadership</h2>
<p>The business landscape has changed irreversibly. Organizations today require leaders who are not just experts in corporate finance or strategic positioning, but who also possess high emotional intelligence, adaptability, and an understanding of multi-faceted global systems.</p>
<blockquote>"The modern executive must transition from a functional manager to a visionary architect of organizational success."</blockquote>
<p>At Maverick Business Academy, we believe this evolution can be accelerated. Let us explore the core areas where Executive MBA curriculums are modernizing:</p>
<h3>1. Technology Integration and Artificial Intelligence</h3>
<p>AI is no longer a concept for the technical department. Modern leaders need to understand how algorithms can drive decision-making, optimize supply chains, and redefine business models. Executive programs now place strategic technology management at their very core.</p>
<h3>2. Cross-Border Collaboration and Cultural Intelligence</h3>
<p>Leading across borders requires more than knowing foreign exchange rates. It demands cross-cultural empathy and deep communication frameworks. Successful MBAs emphasize global residency programs and global study pathways to foster true cultural intelligence.</p>
<ul>
    <li>Frictionless cross-border team collaboration techniques.</li>
    <li>Understanding regional regulatory and ESG landscapes.</li>
    <li>Adapting communications to non-Western contexts.</li>
</ul>
<p>To summarize, the EMBA of the future is deeply pragmatic, fluidly technological, and explicitly international. Leaders who invest in modernizing their skills now will capture the highest opportunities tomorrow.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&w=1200&q=80',
                'category' => 'MBA Insights',
                'tags' => ['Leadership', 'Global Business', 'Executive MBA'],
                'author_name' => 'Dr. Elizabeth Vance',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Dean of Executive Programs at Maverick Business Academy with over 20 years of research in global organization systems.',
                'published_at' => '2026-01-10',
                'reading_time_minutes' => 6,
                'is_featured' => true,
                'meta_title' => 'Unlocking Global Leadership: The Future of the Executive MBA',
                'meta_description' => 'Discover how modern Executive MBA programs are restructuring to prepare next-generation business leaders for complex global markets.'
            ],
            (object) [
                'id' => 2,
                'title' => 'Negotiation Masterclass: Strategies for High-Stakes Deals',
                'slug' => 'negotiation-masterclass-strategies-high-stakes-deals',
                'excerpt' => 'High-stakes negotiations require more than intuition. Learn 5 evidence-based tactics from leading business school negotiators.',
                'content' => '<h2>Mastering the Fine Art of High-Stakes Negotiation</h2>
<p>High-stakes business deals are rarely won on loud charisma or aggressive posturing. Instead, they are systematically orchestrated by meticulous preparation, strategic empathy, and an acute understanding of psychological incentives.</p>
<blockquote>"Negotiation is not about defeating the opposing side, but about discovering creative solutions that satisfy both parties’ critical interests."</blockquote>
<p>Whether you are raising a multi-million dollar series round or pursuing a major cross-border acquisition, these five strategies will elevate your bargaining outcomes:</p>
<h3>1. The Power of Precise Preparation</h3>
<p>Before stepping into the room, define your BATNA (Best Alternative to a Negotiated Agreement) and identify the underlying motivations of your counterpart. Information asymmetry is your greatest risk.</p>
<h3>2. Leverage Tactical Empathy</h3>
<p>Active listening allows you to uncover hidden constraints that the other side might not disclose directly. Use open-ended questions like "What are the primary obstacles you face in meeting this timeline?" to encourage collaboration.</p>
<h3>3. Frame Your Proposals Strategically</h3>
<p>The human brain evaluates options contextually. Presenting your package with clear tradeoffs and anchoring premium packages early establishes a psychological benchmark in your favor.</p>
<ul>
    <li>Avoid making early or single-dimension concessions.</li>
    <li>Use silent pauses to prompt the other party to explain their stance.</li>
    <li>Separate personal dynamics from objective deal metrics.</li>
</ul>
<p>Ultimately, professional negotiators are problem solvers. Adopting these tactical frameworks guarantees that you leave the negotiating table with maximum value, strong business relationships, and highly stable agreements.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Leadership',
                'tags' => ['Negotiation', 'Business Strategy', 'Deal Making'],
                'author_name' => 'Prof. Marcus Brody',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Senior Lecturer of Corporate Strategy and Negotiation Tactics. Former M&A consultant for FTSE 100 organizations.',
                'published_at' => '2026-01-14',
                'reading_time_minutes' => 8,
                'is_featured' => false,
                'meta_title' => 'Negotiation Masterclass: Strategies for High-Stakes Deals',
                'meta_description' => 'High-stakes negotiations require more than intuition. Learn 5 evidence-based tactics from leading business school negotiators.'
            ],
            (object) [
                'id' => 3,
                'title' => 'The Power of Cultural Intelligence in Multinational Teams',
                'slug' => 'power-cultural-intelligence-multinational-teams',
                'excerpt' => 'As businesses expand globally, cultural intelligence (CQ) becomes the key differentiator for high-performing teams.',
                'content' => '<h2>Understanding Cultural Intelligence (CQ) in Business</h2>
<p>Modern workplaces are increasingly global and diverse. In multinational teams, the primary obstacle to performance is rarely technical capacity—it is cultural friction. Cultivating CQ represents a vital leadership skill.</p>
<blockquote>"Cultural Intelligence is the capability to relate and work effectively across diverse cultural, regional, and national contexts."</blockquote>
<h3>The Four Pillars of Cultural Intelligence</h3>
<p>CQ consists of four key capabilities that business professionals must actively develop:</p>
<h3>1. CQ Drive (Motivation)</h3>
<p>The leader’s interest and confidence in adapting to culturally diverse settings. Without genuine curiosity, learning new customs feels like a chore.</p>
<h3>2. CQ Knowledge (Cognition)</h3>
<p>Developing structural knowledge about how cultures differ regarding communication styles, religious practices, and organizational dynamics.</p>
<h3>3. CQ Strategy (Metacognition)</h3>
<p>The ability to plan for, monitor, and adjust mental models when interacting with individuals of distinct cultural backgrounds.</p>
<h3>4. CQ Action (Behavior)</h3>
<p>The practical capacity to adapt verbal and non-verbal actions appropriately, ensuring respectful and effective teamwork.</p>
<p>By investing in CQ training, multinational firms unlock unprecedented innovation, improve global expansion success rates, and build highly inclusive, resilient cultures.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1531538606174-0f90ff5dce83?auto=format&fit=crop&w=1200&q=80',
                'category' => 'MBA Insights',
                'tags' => ['Cultural Intelligence', 'Teamwork', 'International Management'],
                'author_name' => 'Sophia Al-Jamil',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Intercultural Consultant and MBA guest speaker specializing in European-Middle Eastern joint ventures.',
                'published_at' => '2026-01-18',
                'reading_time_minutes' => 5,
                'is_featured' => false,
                'meta_title' => 'The Power of Cultural Intelligence in Multinational Teams',
                'meta_description' => 'As businesses expand globally, cultural intelligence (CQ) becomes the key differentiator for high-performing teams.'
            ],
            (object) [
                'id' => 4,
                'title' => '5 Essential Networking Skills for MBA Students',
                'slug' => '5-essential-networking-skills-mba-students',
                'excerpt' => 'An MBA is only as valuable as the network you build. Learn how to connect meaningfully with industry leaders.',
                'content' => '<h2>Maximizing Your MBA ROI Through Strategic Networking</h2>
<p>Many students enter business school believing that academic grades are the ultimate arbiter of post-graduation success. While core corporate competencies matter, your network is often the true driver of your professional path.</p>
<blockquote>"Your network is your net worth. In the business world, relationships are the currency of progress."</blockquote>
<p>Here are 5 essential networking practices that every student should implement immediately:</p>
<h3>1. Shift from Transactional to Generational Relationships</h3>
<p>Do not contact alumni only when you need a job referral. Seek long-term mentorship by offering help, sharing industry research, and showing genuine interest in their strategic achievements.</p>
<h3>2. Perfect Your 30-Second Positioning Statement</h3>
<p>Can you articulate who you are, the distinct value you bring, and where you want to go in exactly 30 seconds? Your pitch must be highly polished, memorable, and adaptable.</p>
<h3>3. Master the Informational Interview</h3>
<p>Reach out to mid-to-senior executives for 15-minute consultations to discuss their career path and current business challenges. Ask insightful questions that exhibit your preparation.</p>
<ul>
    <li>Research the speaker thoroughly prior to the call.</li>
    <li>Send a handwritten or personalized digital thank-you note within 12 hours.</li>
    <li>Keep contacts updated on how you applied their advice over time.</li>
</ul>
<p>Strategic networking is an active discipline. Commit to these professional practices, and watch your career landscape expand exponentially.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Career Advice',
                'tags' => ['Networking', 'Career Growth', 'MBA Success'],
                'author_name' => 'Liam Henderson',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Head of Career Services at Maverick Business Academy. Ex-Executive Search Partner at Korn Ferry.',
                'published_at' => '2026-01-22',
                'reading_time_minutes' => 7,
                'is_featured' => false,
                'meta_title' => '5 Essential Networking Skills for MBA Students',
                'meta_description' => 'An MBA is only as valuable as the network you build. Learn how to connect meaningfully with industry leaders.'
            ],
            (object) [
                'id' => 5,
                'title' => 'Demystifying Venture Capital: How Startups Raise Capital',
                'slug' => 'demystifying-venture-capital-how-startups-raise-capital',
                'excerpt' => 'An in-depth guide to understanding the seed-to-growth venture capital cycle and what investors look for.',
                'content' => '<h2>Decoding the Venture Capital Matrix</h2>
<p>For entrepreneurs, securing institutional financing can feel like a labyrinth of buzzwords, investor terms sheets, and complex cap tables. This guide demystifies the structure of modern Venture Capital.</p>
<blockquote>"Venture Capital is a partnership, not a grant. Understanding an investor’s risk profile is step zero to fundraising."</blockquote>
<h3>The Capital Progression Continuum</h3>
<p>Founders must raise capital in structured stages to align dilution with enterprise value milestones:</p>
<h3>1. Seed Phase: Validating the Thesis</h3>
<p>At the seed stage, capital is allocated to refine product-market fit, construct the core engineering team, and prove initial customer acquisition loops.</p>
<h3>2. Series A: Building the Economic Machine</h3>
<p>Here, investors search for systematic metrics: repeatable sales funnels, stable unit economics, and a scalable customer lifetime value (LTV) to CAC ratio.</p>
<h3>3. Series B & Beyond: Acceleration and Scale</h3>
<p>Once the machine is built, growth capital acts as rocket fuel to expand market share, expand product portfolios, and expand globally.</p>
<p>Founders who approach investors with clear metric-driven narratives and deep commercial execution frameworks will consistently out-fundraise their competition.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Industry Trends',
                'tags' => ['Venture Capital', 'Startups', 'Finance'],
                'author_name' => 'Prof. Marcus Brody',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Senior Lecturer of Corporate Strategy and Negotiation Tactics. Former M&A consultant for FTSE 100 organizations.',
                'published_at' => '2026-01-26',
                'reading_time_minutes' => 9,
                'is_featured' => false,
                'meta_title' => 'Demystifying Venture Capital: How Startups Raise Capital',
                'meta_description' => 'An in-depth guide to understanding the seed-to-growth venture capital cycle and what investors look for.'
            ],
            (object) [
                'id' => 6,
                'title' => 'How MBA Alumnus Sara Lin Scaled Her EdTech Startup to $10M',
                'slug' => 'how-mba-alumnus-sara-lin-scaled-her-edtech-startup-to-10m',
                'excerpt' => 'Read the inspiring story of Sara Lin, who turned her Maverick MBA thesis project into a thriving global EdTech startup.',
                'content' => '<h2>From MBA Thesis to Global Scale-up</h2>
<p>Many business ideas remain trapped within academic journals or pitch decks. For Sara Lin, a 2024 graduate of Maverick Business Academy, her global thesis was the foundation of an EdTech firm now operating across 12 countries.</p>
<blockquote>"Maverick provided me with more than business metrics. It gave me the global platform and investor access to bring my project to life."</blockquote>
<p>In this exclusive interview, Sara shares the key growth pillars behind her success:</p>
<h3>1. Identifying the Structural Gap</h3>
<p>While studying digital transformations in emerging economies, Sara identified a critical bottleneck: local institutions lacked access to high-fidelity asynchronous curriculum tools. She engineered a solution that scaled seamlessly on low-bandwidth networks.</p>
<h3>2. Leveraging the MBA Incubation Loop</h3>
<p>Rather than waiting for graduation, Sara used her strategy and finance courses to continuously test and refine her software architecture and financial model, guided by seasoned faculty advisors.</p>
<h3>3. Building the Seed Cohort</h3>
<p>Sara’s first three institutional clients were introduced through Maverick’s corporate partner network. This initial reference validation unlocked her Series A funding round within six months of graduation.</p>
<p>Sara’s journey exemplifies the true mission of Maverick Business Academy: developing pragmatic leaders who don’t just observe the future, but actively build it.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1580894732444-8fecef2271ff?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Student Success',
                'tags' => ['Alumni Stories', 'EdTech', 'Entrepreneurship'],
                'author_name' => 'Dr. Elizabeth Vance',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Dean of Executive Programs at Maverick Business Academy with over 20 years of research in global organization systems.',
                'published_at' => '2026-01-29',
                'reading_time_minutes' => 6,
                'is_featured' => false,
                'meta_title' => 'How MBA Alumnus Sara Lin Scaled Her EdTech Startup to $10M',
                'meta_description' => 'Read the inspiring story of Sara Lin, who turned her Maverick MBA thesis project into a thriving global EdTech startup.'
            ],
            (object) [
                'id' => 7,
                'title' => 'Navigating the Shift to Sustainable Corporate Governance',
                'slug' => 'navigating-shift-sustainable-corporate-governance',
                'excerpt' => 'Regulatory pressure and consumer preferences are forcing corporate boards to integrate ESG targets. Learn how to lead the green transition.',
                'content' => '<h2>The Strategic Evolution of Modern ESG</h2>
<p>Environmental, Social, and Governance (ESG) criteria are no longer minor issues relegated to corporate social responsibility reports. Today, sustainable governance represents a fiduciary duty and a source of competitive advantage.</p>
<blockquote>"The most profitable organizations of the next decade will be those that integrate sustainability directly into their core capital allocation models."</blockquote>
<p>Let us explore the core areas where modern corporate boards are actively restructuring:</p>
<h3>1. Decarbonizing the Enterprise Supply Chain</h3>
<p>Leaders must transition from Scope 1 direct emissions metrics to deep Scope 3 audit trails. This involves restructuring purchasing protocols, partnering with green logistics firms, and holding global suppliers strictly accountable.</p>
<h3>2. Implementing Robust Diversity and Inclusion Structures</h3>
<p>Diverse perspectives in management and board levels are empirically linked to better risk management and higher innovation rates. Progressive firms design structural paths to senior leadership for underrepresented talents.</p>
<h3>3. Dynamic and Transparent Shareholder Auditing</h3>
<p>Institutional investors demand precise, audited ESG metrics over vague mission statements. Implementing standardized frameworks ensures access to lower-cost green financing options.</p>
<p>Sustainable governance is not a cost center; it is an long-term value preservation model. Executives equipped with these strategic frameworks are the architects of the future corporate economy.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Industry Trends',
                'tags' => ['ESG', 'Sustainability', 'Corporate Governance'],
                'author_name' => 'Sophia Al-Jamil',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Intercultural Consultant and MBA guest speaker specializing in European-Middle Eastern joint ventures.',
                'published_at' => '2026-02-02',
                'reading_time_minutes' => 8,
                'is_featured' => false,
                'meta_title' => 'Navigating the Shift to Sustainable Corporate Governance',
                'meta_description' => 'Regulatory pressure and consumer preferences are forcing corporate boards to integrate ESG targets. Learn how to lead the green transition.'
            ],
            (object) [
                'id' => 8,
                'title' => 'Rebranding Your Career: Transitioning into Management Roles',
                'slug' => 'rebranding-career-transitioning-management-roles',
                'excerpt' => 'An expert blueprint on how specialized individual contributors can successfully pivot into executive leadership.',
                'content' => '<h2>Unlocking the Path from Technical Specialist to Strategic Leader</h2>
<p>Many professionals reach a ceiling where their technical skills are no longer the primary driver of their career advancement. Transitioning into management requires a massive shift in mindset, behavior, and professional positioning.</p>
<blockquote>"What got you here won\'t get you there. Individual contributors drive execution; managers drive alignment."</blockquote>
<p>This master blueprint outlines the essential shifts you must execute to reposition yourself for leadership roles:</p>
<h3>1. Master the Language of Strategic Alignment</h3>
<p>Stop talking about daily technical tasks and start framing your work in terms of top-line revenue, operational efficiency, and risk mitigation. Connect your initiatives to your company’s global strategic goals.</p>
<h3>2. Develop Influence Without Authority</h3>
<p>To lead teams effectively, you must learn to inspire, build consensus, and resolve conflicts across distinct departments, even when you hold no direct disciplinary authority.</p>
<h3>3. Actively Delegate and Empower</h3>
<p>The greatest mistake of first-time managers is micromanagement. Your role is to build optimal conditions for your team to perform, not to complete the technical tasks yourself.</p>
<p>Executive management is a highly distinct craft. Repositioning yourself through these leadership behaviors guarantees that you will be viewed as a high-potential asset by corporate decision-makers.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1542744094-3a31f103e35f?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Career Advice',
                'tags' => ['Career Pivot', 'Leadership Dev', 'Executive Management'],
                'author_name' => 'Liam Henderson',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Head of Career Services at Maverick Business Academy. Ex-Executive Search Partner at Korn Ferry.',
                'published_at' => '2026-02-05',
                'reading_time_minutes' => 6,
                'is_featured' => false,
                'meta_title' => 'Rebranding Your Career: Transitioning into Management Roles',
                'meta_description' => 'An expert blueprint on how specialized individual contributors can successfully pivot into executive leadership.'
            ],
            (object) [
                'id' => 9,
                'title' => 'The Rise of Hybrid Business Models: Opportunities in 2026',
                'slug' => 'rise-of-hybrid-business-models-opportunities-2026',
                'excerpt' => 'Analyze how forward-thinking global businesses are blending digital-first scalability with localized physical presence.',
                'content' => '<h2>The Strategic Paradox of the Hybrid Business Model</h2>
<p>For years, commercial analysts predicted a digital-only economy. However, the realities of 2026 have proven that the ultimate model is hybrid—blending software-driven scale with highly focused, tangible customer experiences.</p>
<blockquote>"The future of commerce belongs to the fast and physical: companies that combine digital speed with authentic human touchpoints."</blockquote>
<p>This deep-dive analysis highlights the structural opportunities of hybrid business architectures:</p>
<h3>1. The Omni-Channel Logistics Advantage</h3>
<p>Businesses that maintain local, compact physical hubs to complement digital logistics pathways see dramatically lower customer churn rates and accelerated local distribution times.</p>
<h3>2. Authentic Human-Centric Customer Experiences</h3>
<p>While standard support can be successfully managed by AI, high-value corporate deals and premium customer relationships still require highly structured personal consultations and trust building.</p>
<h3>3. Blended Workplace Environments and Talent Acquisition</h3>
<p>The most resilient organizations do not demand fully remote or fully in-office operations. Instead, they design a hybrid flow that maximizes focused individual deep work and high-impact physical design sprints.</p>
<p>Implementing a hybrid structure is not a compromise; it is an advanced strategy. Businesses that master this operational architecture will dominate the commercial landscape of tomorrow.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Industry Trends',
                'tags' => ['Hybrid Business', 'Strategy', 'Future of Work'],
                'author_name' => 'Prof. Marcus Brody',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Senior Lecturer of Corporate Strategy and Negotiation Tactics. Former M&A consultant for FTSE 100 organizations.',
                'published_at' => '2026-02-08',
                'reading_time_minutes' => 7,
                'is_featured' => false,
                'meta_title' => 'The Rise of Hybrid Business Models: Opportunities in 2026',
                'meta_description' => 'Analyze how forward-thinking global businesses are blending digital-first scalability with localized physical presence.'
            ],
            (object) [
                'id' => 10,
                'title' => 'Designing High-Performance Organizations for Complex Markets',
                'slug' => 'designing-high-performance-organizations-complex-markets',
                'excerpt' => 'An administrative roadmap on how to construct agile, responsive structural architectures inside legacy corporate systems.',
                'content' => '<h2>Breaking Free from Rigid Bureaucracy</h2>
<p>When market volatility becomes a permanent fixture, rigid corporate hierarchies act as anchors dragging down innovation and growth. Agile systems are the only path to survival.</p>
<blockquote>"An organization’s capacity to learn and translate that learning into rapid action is the ultimate competitive advantage."</blockquote>
<p>This strategic roadmap outlines the structural transformations required to foster a high-performance organizational culture:</p>
<h3>1. Establish Autonomous Cross-Functional Squads</h3>
<p>Break down corporate silos by assembling small, self-directed teams comprising designers, engineers, and financial analysts focused on a single customer metric.</p>
<h3>2. Push Decision-Making to the Edges</h3>
<p>Dramatically reduce approval times by empowering frontline workers with clear decision-making frameworks and transparent operational goals.</p>
<h3>3. Institutionalize Rapid Failure and Iteration</h3>
<p>Create corporate psychological safety where calculated risks are encouraged, and failed experiments are analyzed as valuable strategic data points rather than performance faults.</p>
<p>Redesigning organizational architecture requires systemic courage. Executives who dismantle legacy hierarchies and build decentralized systems will lead the high-growth markets of tomorrow.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Leadership',
                'tags' => ['Org Design', 'Agility', 'Corporate Innovation'],
                'author_name' => 'Dr. Elizabeth Vance',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Dean of Executive Programs at Maverick Business Academy with over 20 years of research in global organization systems.',
                'published_at' => '2026-02-12',
                'reading_time_minutes' => 8,
                'is_featured' => false,
                'meta_title' => 'Designing High-Performance Organizations for Complex Markets',
                'meta_description' => 'An administrative roadmap on how to construct agile, responsive structural architectures inside legacy corporate systems.'
            ],
            (object) [
                'id' => 11,
                'title' => 'Inside Maverick: Student-Led International Consultancy Projects',
                'slug' => 'inside-maverick-student-led-international-consultancy-projects',
                'excerpt' => 'See how our MBA cohorts work directly with FTSE 100 executives to solve real global operational issues.',
                'content' => '<h2>Real Consultancies, Real Enterprise Impact</h2>
<p>At Maverick Business Academy, we believe in radical, experiential learning. Our students don\'t just analyze old Harvard Business School case studies—they act as active advisors for multinational corporations.</p>
<blockquote>"The international consulting project forced our cohort to analyze complex local regulatory frameworks in real time under direct executive oversight."</blockquote>
<p>Let us explore how these global consultancy modules are structurally designed to deliver extreme ROI for both students and corporate sponsors:</p>
<h3>1. Addressing Unstructured Enterprise Problems</h3>
<p>Our corporate partners present our cohorts with active, unsolved strategic dilemmas—ranging from digital supply chain shifts in APAC to regional post-merger cultural alignment challenges in the EMEA region.</p>
<h3>2. Deep Multi-Country Field Research</h3>
<p>Students travel directly to partner offices, conducting frontline interviews, assessing localized market dynamics, and building high-fidelity quantitative analysis templates.</p>
<h3>3. Presenting Direct Boardroom Recommendations</h3>
<p>The program culminates in a live board presentation where student cohorts deliver their strategic suggestions to active corporate board members and corporate managing directors.</p>
<p>Maverick’s consulting modules bridge the gap between academic theory and high-stakes executive execution, ensuring our graduates enter the job market with elite operational credentials.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Student Success',
                'tags' => ['Consultancy', 'Experiential Learning', 'Global Impact'],
                'author_name' => 'Sophia Al-Jamil',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Intercultural Consultant and MBA guest speaker specializing in European-Middle Eastern joint ventures.',
                'published_at' => '2026-02-15',
                'reading_time_minutes' => 6,
                'is_featured' => false,
                'meta_title' => 'Inside Maverick: Student-Led International Consultancy Projects',
                'meta_description' => 'See how our MBA cohorts work directly with FTSE 100 executives to solve real global operational issues.'
            ],
            (object) [
                'id' => 12,
                'title' => 'How to Balance a High-Stakes Career with Postgraduate Study',
                'slug' => 'how-to-balance-high-stakes-career-with-postgraduate-study',
                'excerpt' => 'Practical time-management architectures and cognitive hacks for busy corporate professionals pursuing advanced degrees.',
                'content' => '<h2>Succeeding as a Dual-Career Professional</h2>
<p>The choice to pursue an Executive MBA or PhD while leading a team and managing a family is a massive cognitive and logistical challenge. Without highly structured systems, burnout is inevitable.</p>
<blockquote>"Time management is not about working harder; it is about establishing highly robust, non-negotiable boundaries."</blockquote>
<p>These time-management frameworks are engineered specifically for the modern high-velocity executive:</p>
<h3>1. Implement Ruthless Time Blocking</h3>
<p>Treat your academic research hours exactly like critical corporate board meetings. Schedule non-negotiable blocks in your shared calendar and turn off all digital notifications.</p>
<h3>2. Master the Art of Cognitive Offloading</h3>
<p>Do not attempt to remember task deadlines. Leverage robust task systems, digital capture channels, and clean databases to maintain mental clarity and prevent decision fatigue.</p>
<h3>3. Optimize Interlocking Study Cohorts</h3>
<p>Lean heavily on your MBA study squad. Divide extensive academic readings, collaborate on shared study notes, and rotate presentation duties to distribute the cognitive load.</p>
<p>Balancing high-stakes careers with postgraduate studies requires strategic operational discipline. Applying these tactical habits guarantees elite academic achievements and sustained professional growth.</p>',
                'featured_image_url' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Career Advice',
                'tags' => ['Time Management', 'MBA Life', 'Executive Well-being'],
                'author_name' => 'Liam Henderson',
                'author_avatar_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&h=150&q=80',
                'author_bio' => 'Head of Career Services at Maverick Business Academy. Ex-Executive Search Partner at Korn Ferry.',
                'published_at' => '2026-02-18',
                'reading_time_minutes' => 5,
                'is_featured' => false,
                'meta_title' => 'How to Balance a High-Stakes Career with Postgraduate Study',
                'meta_description' => 'Practical time-management architectures and cognitive hacks for busy corporate professionals pursuing advanced degrees.'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allPosts = $this->getDummyPosts();

        // 1. Categories list
        $categories = ['All', 'MBA Insights', 'Leadership', 'Career Advice', 'Industry Trends', 'Student Success'];

        // 2. Filter by category
        $activeCategory = $request->query('category', 'All');
        if ($activeCategory !== 'All') {
            $allPosts = $allPosts->filter(function ($post) use ($activeCategory) {
                return strcasecmp($post->category, $activeCategory) === 0;
            });
        }

        // 3. Search filter
        $searchQuery = $request->query('search', '');
        if (!empty($searchQuery)) {
            $allPosts = $allPosts->filter(function ($post) use ($searchQuery) {
                return str_contains(strtolower($post->title), strtolower($searchQuery))
                    || str_contains(strtolower($post->excerpt), strtolower($searchQuery))
                    || str_contains(strtolower($post->category), strtolower($searchQuery));
            });
        }

        // Sort posts so published_at is desc, or featured always comes first
        $allPosts = $allPosts->sortByDesc('published_at')->values();

        // 4. Identify Featured Post (First featured post available)
        // If searching or filtering, we don't treat the featured post differently in layout (or we do)
        // Usually, featured post is only highlighted on Page 1 when no specific search is active.
        $featuredPost = null;
        if ($activeCategory === 'All' && empty($searchQuery)) {
            $featuredPost = $allPosts->firstWhere('is_featured', true);
            // Fallback to first post if none marked is_featured
            if (!$featuredPost) {
                $featuredPost = $allPosts->first();
            }
        }

        // If we have a highlighted featured post, exclude it from the grid listing on page 1
        $gridPosts = $allPosts;
        $page = (int) $request->query('page', 1);
        $perPage = 6;

        if ($featuredPost && $page === 1) {
            $gridPosts = $gridPosts->filter(function ($post) use ($featuredPost) {
                return $post->id !== $featuredPost->id;
            });
        }

        // 5. Server-side Pagination
        $offset = ($page - 1) * $perPage;
        $currentPageItems = $gridPosts->slice($offset, $perPage)->values();

        $paginatedPosts = new LengthAwarePaginator(
            $currentPageItems,
            $gridPosts->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Required site variable for layout footer and header dynamic checks
        $site = (object) [
            'logo_url' => asset('assets/images/logo.png'),
            'logo_white_url' => asset('assets/images/logo-white.png'),
            'apply_now_url' => url('/apply/'),
            'whatsapp_number' => '447000000000',
            'address' => 'Maverick Business Academy, London, UK',
            'email' => 'admissions@maverick.edu',
            'phone' => '442070000000',
            'facebook_url' => '#',
            'instagram_url' => '#',
            'linkedin_url' => '#',
            'youtube_url' => '#',
        ];

        return view('blogs.index', compact('paginatedPosts', 'featuredPost', 'categories', 'activeCategory', 'searchQuery', 'site'));
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $posts = $this->getDummyPosts();
        $post = $posts->firstWhere('slug', $slug);

        if (!$post) {
            abort(404, 'Article not found');
        }

        // 1. Dynamic Table of Contents Generator
        // We scan the content HTML for <h2> and <h3> tags and map them.
        preg_match_all('/<h([2-3])>(.*?)<\/h[2-3]>/', $post->content, $matches, PREG_SET_ORDER);
        $headings = [];
        foreach ($matches as $match) {
            $level = (int) $match[1];
            $text = strip_tags($match[2]);
            // Create a safe anchor slug
            $anchor = strtolower(preg_replace('/[^a-z0-9\-]+/i', '-', $text));
            $headings[] = (object) [
                'level' => $level,
                'text' => $text,
                'anchor' => $anchor
            ];
        }

        // To support anchoring, let's inject IDs into the H2/H3 tags of the post content
        $postContentInjected = $post->content;
        foreach ($headings as $heading) {
            $tag = 'h' . $heading->level;
            $pattern = '/<' . $tag . '>(.*?' . preg_quote($heading->text, '/') . '.*?)<\/' . $tag . '>/';
            $replacement = '<' . $tag . ' id="' . $heading->anchor . '">$1</' . $tag . '>';
            $postContentInjected = preg_replace($pattern, $replacement, $postContentInjected, 1);
        }
        $post->content = $postContentInjected;

        // 2. Select Related Articles (3 items of same category or random ones, excluding current)
        $relatedPosts = $posts->filter(function ($item) use ($post) {
            return $item->id !== $post->id;
        });

        $sameCategory = $relatedPosts->filter(function ($item) use ($post) {
            return strcasecmp($item->category, $post->category) === 0;
        });

        if ($sameCategory->count() >= 3) {
            $relatedPosts = $sameCategory->take(3);
        } else {
            // merge and pad with other categories
            $relatedPosts = $sameCategory->merge($relatedPosts)->unique('id')->take(3);
        }

        $site = (object) [
            'logo_url' => asset('assets/images/logo.png'),
            'logo_white_url' => asset('assets/images/logo-white.png'),
            'apply_now_url' => url('/apply/'),
            'whatsapp_number' => '447000000000',
            'address' => 'Maverick Business Academy, London, UK',
            'email' => 'admissions@maverick.edu',
            'phone' => '442070000000',
            'facebook_url' => '#',
            'instagram_url' => '#',
            'linkedin_url' => '#',
            'youtube_url' => '#',
        ];

        return view('blogs.show', compact('post', 'headings', 'relatedPosts', 'site'));
    }
}
