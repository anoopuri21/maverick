<div class="edu-programmes__china">
  @foreach($chinaItems as $item)
  <div class="edu-programmes__china-item fade-up">
    @if(filled($item['icon_key'] ?? null))
    <div class="edu-programmes__china-icon">
      <x-edu.icon :name="$item['icon_key']" :size="22" />
    </div>
    @endif
    <div class="edu-programmes__china-body">
      <h4 class="edu-programmes__china-title">{{ $item['title'] }}</h4>
      @if(filled($item['description'] ?? null))
      <p class="edu-programmes__china-desc">{{ $item['description'] }}</p>
      @endif
    </div>
  </div>
  @endforeach
</div>

@if(filled($programmes->china_cta_label) && filled($programmes->china_cta_url))
<div class="edu-programmes__china-cta fade-up">
  <a href="{{ edu_href($programmes->china_cta_url) }}" class="btn btn--primary">{{ $programmes->china_cta_label }}</a>
</div>
@endif
