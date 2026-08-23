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

<form class="mlp-form__fields" action="{{ route('mba-masters-landing.enquire') }}" method="POST" novalidate>
  @csrf
  <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="mlp-form__honeypot" aria-hidden="true">

  <label class="mlp-field">
    <span>Full name</span>
    <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" autocomplete="name">
  </label>

  <div class="mlp-form__row">
    <label class="mlp-field">
      <span>Email</span>
      <input type="email" name="email" value="{{ old('email') }}" required maxlength="150" autocomplete="email">
    </label>

    <label class="mlp-field">
      <span>Phone / WhatsApp</span>
      <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel">
    </label>
  </div>

  <div class="mlp-form__row">
    <label class="mlp-field">
      <span>Program</span>
      <select name="program">
        <option value="">Select</option>
        <option value="MBA" @selected(old('program') === 'MBA')>MBA</option>
        <option value="Executive MBA" @selected(old('program') === 'Executive MBA')>Executive MBA</option>
        <option value="Master's / MSc" @selected(old('program') === "Master's / MSc")>Master's / MSc</option>
        <option value="LLM" @selected(old('program') === 'LLM')>LLM</option>
        <option value="Not sure" @selected(old('program') === 'Not sure')>Not sure yet</option>
      </select>
    </label>

    <label class="mlp-field">
      <span>How soon you want to start?</span>
      <select name="start_timeline">
        <option value="not-decided" @selected(old('start_timeline', 'not-decided') === 'not-decided')>Not decided</option>
        <option value="1-3-months" @selected(old('start_timeline') === '1-3-months')>1–3 months</option>
        <option value="3-6-months" @selected(old('start_timeline') === '3-6-months')>3–6 months</option>
        <option value="more-than-6-months" @selected(old('start_timeline') === 'more-than-6-months')>More than 6 months</option>
      </select>
    </label>
  </div>

  <button type="submit" class="mlp-btn mlp-btn--primary mlp-btn--block">Submit enquiry</button>
</form>
