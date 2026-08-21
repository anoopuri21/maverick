{{-- ===== S9: APPLICATION PROCESS ===== --}}
@php
    $steps = collect($process->steps ?? [])->filter(fn ($step) => filled($step['title'] ?? null));
    $showProcess = filled($process->label ?? null) || filled($process->title ?? null) || $steps->isNotEmpty();
@endphp
@if($showProcess)
<section class="dmba-process section--light section--warm section-wrapper" aria-label="Application Process" data-testid="dmba-process-section">
  <div class="container">
    <div class="dmba-process__header">
      @if(filled($process->label))
      <div class="section-label"><span>{{ $process->label }}</span></div>
      @endif
      @if(filled($process->title))
      <h2 class="section-title">{{ $process->title }}</h2>
      @endif
    </div>

    @if($steps->isNotEmpty())
    <div class="dmba-process__steps" data-testid="dmba-process-steps">
      @foreach($steps as $step)
      <div class="dmba-process__step" data-testid="dmba-step-{{ $loop->iteration }}">
        <div class="dmba-process__step-circle">{{ $loop->iteration }}</div>
        <div class="dmba-process__step-content">
          <h3 class="dmba-process__step-title">{{ $step['title'] }}</h3>
          @if(filled($step['description'] ?? null))
          <p class="dmba-process__step-desc">{{ $step['description'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>
@endif
