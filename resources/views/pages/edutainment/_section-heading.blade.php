@php
    $hasTitle = filled($title ?? null) || filled($titleLine2 ?? null) || filled($titleItalic ?? null);
@endphp
@if(filled($label ?? null) || $hasTitle)
  @if(filled($label ?? null))
  <div class="section-label"><span>{{ $label }}</span></div>
  @endif
  @if($hasTitle)
  <h2 class="section-title">
    @if(filled($title ?? null)){{ $title }}@endif
    @if(!empty($titleBreak) && (filled($titleLine2 ?? null) || filled($titleItalic ?? null)))
      <br>
    @elseif(filled($title ?? null) && (filled($titleLine2 ?? null) || filled($titleItalic ?? null)))
      {{ ' ' }}
    @endif
    @if(filled($titleLine2 ?? null)){{ $titleLine2 }}@endif
    @if(filled($titleLine2 ?? null) && filled($titleItalic ?? null)){{ ' ' }}@endif
    @if(filled($titleItalic ?? null))<em>{{ $titleItalic }}</em>@endif
  </h2>
  @endif
@endif
