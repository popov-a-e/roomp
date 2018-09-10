<main>
  <roomp-header
    :user='{!! $user !!}'
    :locale='"{{ $locale }}"'
    :currency="'{{ $currency }}'"
    :settings="{{ $settings }}"
  ></roomp-header>

  <div class='content' :style='{height: contentHeight + "px"}'>
    <div class='header'>
      <h1>{{ __('home.title') }}</h1>
      <h2>{{ __('home.subtitle') }}</h2>
    </div>
    <roomp-form :params='{!! $params !!}'></roomp-form>
  </div>
</main>