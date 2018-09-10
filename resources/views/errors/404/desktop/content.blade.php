<main
  v-on:click='Bus.$emit("click")'
  v-on:mouseup='Bus.$emit("mouseup")'
  v-on:mousemove='(e) => Bus.$emit("mousemove", e.pageX, e.pageY, e)'
>
  <roomp-header
    :user='{!! $user !!}'
    :locale='"{{ $locale }}"'
    :promo-overlay='true'
    :currency="'{{ $currency }}'"
    :settings="{{ $settings }}"
  >
  </roomp-header>

  <div class='content' v-on:wheel='wheel'>
    <overlay-promo></overlay-promo>

    <div class='background'>
    </div>

    <div class='header'>
      <h1><span>404</span><br>{{ __('errors.not_found') }}</h1>
      <h2>{!! __('errors.try_again') !!}</h2>
    </div>

    <roomp-form :params='{!! json_encode($params) !!}'>
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