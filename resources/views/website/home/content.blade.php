<main>
  <roomp-header
    :promo-overlay='true'
    :user='{!! $user !!}'
    :locale='"{{ $locale }}"'
    :currency="'{{ $currency }}'"
    :locales="{{ collect(config('app.locales')) }}"
    :currencies="{{ collect(config('app.currencies')) }}"
  >
  </roomp-header>

  <div class='content' v-on:wheel='wheel'>
    <overlay-promo></overlay-promo>

    <div class='background'>
    </div>

    <div class='header'>
      <h1>{{ __('home.title') }}</h1>
      <h2>{{ __('home.subtitle') }}</h2>
    </div>

    <roomp-form :params='{!! $params !!}'>
    </roomp-form>

    <div class="conveniences">
      <h3>{{__('home.icon_title')}}</h3>
      <div class="conveniences-container">

        <div>
          <i class="icon-bed"></i>
          <p>{{__('home.icon_bed')}}</p>
        </div>
        <div>
          <i class="icon-TV"></i>
          <p>{{__('home.icon_tv')}}</p>
        </div>
        <div>
          <i class="icon-wifi"></i>
          <p>{{__('home.icon_wifi')}}</p>
        </div>
        <div>
          <i class="icon-towel"></i>
          <p>{{__('home.icon_towels')}}</p>
        </div>
        <div>
          <i class="icon-bathroom"></i>
          <p>{{__('home.icon_bath')}}</p>
        </div>
      </div>
    </div>

    <roomp-footer style='top: calc(100% + 20px);' :home='true'></roomp-footer>
  </div>
</main>