<main>
  <roomp-header :user='{{ $user }}' :locale='"{{$locale}}"'
                :currency="'{{ $currency }}'"
                :settings="{{ $settings }}"></roomp-header>

  <datepicker v-if='datepickerVisible'></datepicker>

  <guest-overlay v-if='guest_overlay_visible'></guest-overlay>

  <div class='content' v-if='!menuVisible'>
    <filters
      :request='{{$request}}'
      :params='{{$params}}'
      :max-price="{{ ['RUB' => 10000, 'USD' => 200, 'GEL' => 300][$currency] }}"
    >
    </filters>
    <hotels v-if='!filtersVisible'>
    </hotels>
  </div>

  <!--<g-map v-if='mapVisible' :hotels='hotels'
         :center='cityData'>
  </g-map>-->
</main>