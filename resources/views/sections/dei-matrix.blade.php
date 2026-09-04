@php $deiMatrix = $deiMatrix ?? null; @endphp
<section id="dei-matrix" class="deim section-wrapper" aria-labelledby="dei-matrix-heading">
  <div class="container">
    <div class="deim__intro">
      @if(filled($deiMatrix->label ?? null))
      <div class="section-label">
        <span>{{ $deiMatrix->label }}</span>
      </div>
      @endif

      <h2 id="dei-matrix-heading" class="deim__heading section-title">
        <span class="deim__heading-line">{{ $deiMatrix->heading ?? '' }}</span>
      </h2>

      @if(html_filled($deiMatrix->description ?? null))
      <div class="deim__description body-text">
        {!! rich_html($deiMatrix->description ?? null) !!}
      </div>
      @endif
    </div>

    <div class="deim__matrix-panel" role="group" aria-label="The three principles of the DEI Matrix">
      <div class="deim__matrix-grid" aria-hidden="true"></div>
      <div class="deim__axis" aria-hidden="true"></div>

      <ol class="deim__rows">
        <li class="deim__row" data-dei-principle="diversity">
          <article class="deim__row-inner" aria-labelledby="dei-diversity-heading">
            <div class="deim__row-marker" aria-hidden="true">
              <span class="deim__row-letter">{{ $deiMatrix->row_d_letter ?? '' }}</span>
            </div>
            <div class="deim__row-main">
              <h3 id="dei-diversity-heading" class="deim__row-heading">{{ $deiMatrix->row_d_heading ?? '' }}</h3>
              <div class="deim__row-definition">{!! rich_html($deiMatrix->row_d_definition ?? null) !!}</div>
            </div>
            <p class="deim__row-practice">
              {{ $deiMatrix->row_d_practice ?? '' }}
            </p>
          </article>
        </li>

        <li class="deim__row" data-dei-principle="equity">
          <article class="deim__row-inner" aria-labelledby="dei-equity-heading">
            <div class="deim__row-marker" aria-hidden="true">
              <span class="deim__row-letter">{{ $deiMatrix->row_e_letter ?? '' }}</span>
            </div>
            <div class="deim__row-main">
              <h3 id="dei-equity-heading" class="deim__row-heading">{{ $deiMatrix->row_e_heading ?? '' }}</h3>
              <div class="deim__row-definition">{!! rich_html($deiMatrix->row_e_definition ?? null) !!}</div>
            </div>
            <p class="deim__row-practice">
              {{ $deiMatrix->row_e_practice ?? '' }}
            </p>
          </article>
        </li>

        <li class="deim__row" data-dei-principle="inclusion">
          <article class="deim__row-inner" aria-labelledby="dei-inclusion-heading">
            <div class="deim__row-marker" aria-hidden="true">
              <span class="deim__row-letter">{{ $deiMatrix->row_i_letter ?? '' }}</span>
            </div>
            <div class="deim__row-main">
              <h3 id="dei-inclusion-heading" class="deim__row-heading">{{ $deiMatrix->row_i_heading ?? '' }}</h3>
              <div class="deim__row-definition">{!! rich_html($deiMatrix->row_i_definition ?? null) !!}</div>
            </div>
            <p class="deim__row-practice">
              {{ $deiMatrix->row_i_practice ?? '' }}
            </p>
          </article>
        </li>
      </ol>
    </div>
  </div>
</section>
