@php
  $formId = 'mlp-enquiry-'.\Illuminate\Support\Str::lower((string) \Illuminate\Support\Str::uuid());
@endphp

@if(session('success'))
<p class="mlp-form__success" role="status">{{ session('success') }}</p>
@endif

@if(session('error'))
<p class="mlp-form__errors" role="alert">{{ session('error') }}</p>
@endif

@if($errors->any())
<ul class="mlp-form__errors" role="alert">
  @foreach($errors->all() as $error)
  <li>{{ $error }}</li>
  @endforeach
</ul>
@endif

<form class="mlp-form__fields" id="{{ $formId }}" action="{{ route('mba-masters-landing.enquire') }}" method="POST" novalidate>
  @csrf
  <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="mlp-form__honeypot" aria-hidden="true">

  <label class="mlp-field mlp-field--full" for="{{ $formId }}-name">
    <span>Full name</span>
    <input id="{{ $formId }}-name" type="text" name="name" value="{{ old('name') }}" required maxlength="100" autocomplete="name">
  </label>

  <div class="mlp-form__row">
    <label class="mlp-field" for="{{ $formId }}-email">
      <span>Email</span>
      <input id="{{ $formId }}-email" type="email" name="email" value="{{ old('email') }}" required maxlength="150" autocomplete="email">
    </label>

    <label class="mlp-field" for="{{ $formId }}-phone">
      <span>Phone / WhatsApp</span>
      <input id="{{ $formId }}-phone" type="tel" name="phone" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel">
    </label>
  </div>

  <div class="mlp-form__row">
    <label class="mlp-field" for="{{ $formId }}-country">
      <span>Country</span>
      <input id="{{ $formId }}-country" type="text" name="country" value="{{ old('country') }}" maxlength="100" autocomplete="country-name" placeholder="Where are you based?">
    </label>

    <label class="mlp-field" for="{{ $formId }}-program">
      <span>Program</span>
      <select id="{{ $formId }}-program" name="program">
        <option value="">Select</option>
        <option value="MBA" @selected(old('program') === 'MBA')>MBA</option>
        <option value="Executive MBA" @selected(old('program') === 'Executive MBA')>Executive MBA</option>
        <option value="Master's / MSc" @selected(old('program') === "Master's / MSc")>Master's / MSc</option>
        <option value="LLM" @selected(old('program') === 'LLM')>LLM</option>
        <option value="Not sure" @selected(old('program') === 'Not sure')>Not sure yet</option>
      </select>
    </label>
  </div>

  <label class="mlp-field mlp-field--full" for="{{ $formId }}-timeline">
    <span>How soon you want to start?</span>
    <select id="{{ $formId }}-timeline" name="start_timeline">
      <option value="not-decided" @selected(old('start_timeline', 'not-decided') === 'not-decided')>Not decided</option>
      <option value="1-3-months" @selected(old('start_timeline') === '1-3-months')>1–3 months</option>
      <option value="3-6-months" @selected(old('start_timeline') === '3-6-months')>3–6 months</option>
      <option value="more-than-6-months" @selected(old('start_timeline') === 'more-than-6-months')>More than 6 months</option>
    </select>
  </label>

  <p class="mlp-form__privacy-note">Share your details and admissions will guide you on eligibility, fees and next steps.</p>
  <button type="submit" class="mlp-btn mlp-btn--primary mlp-btn--block">Submit enquiry <span aria-hidden="true">↗</span></button>
</form>
