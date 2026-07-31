@php
$testimonials = [
    [
        'photo' => 'https://i.pravatar.cc/128?img=12',
        'name' => 'Aisha Rahman',
        'rating' => 5,
        'testimonial' => 'Studying at Maverick opened doors I never thought possible. The international curriculum and mentorship helped me land a role at a multinational firm within months of graduating. I am grateful for the network and confidence this journey gave me.',
    ],
    [
        'photo' => 'https://i.pravatar.cc/128?img=33',
        'name' => 'James Okonkwo',
        'rating' => 5,
        'testimonial' => 'The dual-degree pathway transformed my career trajectory. Learning alongside peers from across the globe sharpened my perspective and prepared me for real-world leadership. Maverick truly invests in every student\'s success.',
    ],
    [
        'photo' => 'https://i.pravatar.cc/128?img=47',
        'name' => 'Priya Mehta',
        'rating' => 4,
        'testimonial' => 'From day one, the faculty challenged me to think beyond textbooks. The industry partnerships and internship opportunities made the transition into consulting seamless. I would recommend Maverick to anyone serious about an international career.',
    ],
    [
        'photo' => null,
        'name' => 'Daniel Weber',
        'rating' => 5,
        'testimonial' => 'Relocating for my MBA felt daunting, but Maverick\'s support system made it feel like home. The practical case studies and global faculty connections directly shaped how I approach strategy in my current role.',
    ],
    [
        'photo' => null,
        'name' => 'Fatima Al-Hassan',
        'rating' => 4,
        'testimonial' => 'What sets Maverick apart is the blend of academic rigor and real-world application. I gained skills that translated immediately into my promotion at a Fortune 500 company. The alumni community continues to open new opportunities.',
    ],
    [
        'photo' => null,
        'name' => 'Chen Wei',
        'rating' => 5,
        'testimonial' => 'Maverick gave me the credentials and confidence to compete on a global stage. The cross-cultural classroom experience was invaluable, and my career has accelerated beyond what I imagined when I first enrolled.',
    ],
];
@endphp

<section id="testimonials" class="os-testimonials" aria-label="Student Testimonials">
  <div class="container">
    <div class="os-testimonials__header">
      <span class="os-section-label fade-up">Testimonials</span>
      <h2 class="os-section-heading os-section-heading--center fade-up">
        What Our Students Say
      </h2>
    </div>

    <div class="os-testimonials__slider" data-testimonials-slider>
      <div class="os-testimonials__viewport">
        <div class="os-testimonials__track">
          @foreach ($testimonials as $item)
          <article class="os-testimonials__card">
            <div class="os-testimonials__stars" aria-label="{{ $item['rating'] }} out of 5 stars">
              @for ($i = 1; $i <= 5; $i++)
              <span
                data-lucide="star"
                class="os-testimonials__star {{ $i <= $item['rating'] ? 'os-testimonials__star--filled' : 'os-testimonials__star--empty' }}"
                aria-hidden="true"
              ></span>
              @endfor
            </div>

            <blockquote class="os-testimonials__quote">
              {{ $item['testimonial'] }}
            </blockquote>

            <div class="os-testimonials__author">
              @if ($item['photo'])
              <img
                src="{{ $item['photo'] }}"
                alt="{{ $item['name'] }}"
                class="os-testimonials__photo"
                width="48"
                height="48"
                loading="lazy"
              />
              @else
              <span class="os-testimonials__initials" aria-hidden="true">{{ strtoupper(mb_substr($item['name'], 0, 1)) }}</span>
              @endif
              <span class="os-testimonials__name">{{ $item['name'] }}</span>
            </div>
          </article>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
