<main>
  <roomp-header
    :user='{!! $user !!}'
    :locale='"{{ $locale }}"'
    :currency="'{{ $currency }}'"
    :settings="{{ $settings }}"
  ></roomp-header>

  <div class='content' :style='{height: contentHeight + "px"}'>

    <div class='header'>
      <h1><span>404</span><br>{{ __('errors.not_found') }}</h1>
      <h2>{!! __('errors.try_again') !!}</h2>
    </div>
    <roomp-form :params='{!! json_encode($params) !!}'></roomp-form>
  </div>
</main>