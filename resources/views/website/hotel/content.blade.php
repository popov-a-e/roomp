<main>
  <roomp-header
    :user='{{ $user }}'
    :locale='"{{ $locale }}"'
    :currency="'{{ $currency }}'"
    :settings="{{ $settings }}"
  >
  </roomp-header>

  <div class='content-container' itemscope itemtype="http://schema.org/Hotel">
    <div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
      <meta itemprop="latitude" content="{{ $hotel->lat }}">
      <meta itemprop="longitude" content="{{ $hotel->lng }}">
    </div>
    <div class='content'>
      <gallery-overlay v-if='overlayVisible'></gallery-overlay>
      <payment-overlay v-if='paymentOverlayVisible'></payment-overlay>

      <div class='heroimage'>
        <div
          class='photos'
          style='background-image: url("{{ image_url($heroimage, 2000) }}");'
          v-on:click='heroimageGalleryOpen'
        ></div>
        <h1>{{$hotel->name}}</h1>
        <p itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><i class="icon-pin"></i>{{$hotel->address}}</p>
      </div>

      <div class='info'>
        <div class='description'>
          <h2>
            {{ __('hotel.description') }}
          </h2>
          <p>
            {{ $hotel->description }}
          </p>
        </div>

        <div class='amenities'>
          <h2>{{ __('hotel.facilities') }}</h2>

          <div>
            <button onclick='
              this.parentNode.classList.toggle("full");
              this.querySelector("i").classList.toggle("icon-chevron-down");
              this.querySelector("i").classList.toggle("icon-chevron-top"); //TODO
            '><i class='icon-chevron-down'></i></button>
            @if ($hotel->breakfast)
              <p class='marked'>{{ __('hotel.free_breakfast') }}</p>
            @endif
            @foreach($amenities as $key => $amenity)
              <p class='{{ $amenity->class_name }}'>{{ $amenity->name }}</p>

            @endforeach
          </div>
        </div>

        <room-nav :photos='{{ $photos }}'></room-nav>

        <div class='rules'>
          <div>
            <h2>{{ __('hotel.hotel_rules') }}</h2>
            <div>
              <p>{{ __('hotel.checkin') }} {{ date('H:i', strtotime($hotel->checkin)) }}</p>
              <p>{{ __('hotel.checkout') }} {{ date('H:i', strtotime($hotel->checkout)) }}</p>
            </div>
          </div>
          <div>
            <h2>{{ __('hotel.additional') }}</h2>
            <div>
              <p>{!! implode('<br>', preg_split('|[\r\n]|',$hotel->additional)) !!}</p>
            </div>
          </div>
        </div>
        <g-map :hotels='{{ $hotelData }}'
               :center='{{ $hotel->city }}'
               :simple='true'>
        </g-map>

        <div class='hotels'>
          <h2>{{ __('hotel.closest_hotels') }}</h2>
          <hotel :hotel='{{ $closest[0] }}'></hotel>
          <hotel :hotel='{{ $closest[1] }}'></hotel>
        </div>
      </div>
    </div>

    <div class='booking-container'>
      <booking
        :params='{{ $params }}'
        :request='{{ $request }}'
        :hotel_id='{{ $hotel->id }}'
        :payment-online='{{ ($hotel->payment_online && $hotel->currency === 'RUB' ? "true" : "false") }}'
        :payment-offline='{{ $hotel->payment_by_card || $hotel->payment_by_cash ? "true" : "false" }}'
        :has-breakfast='{{ $hotel->breakfast ? "true" : "false" }}'
        :policy='{{ $policy }}'
        :user='{{ $user }}'
      >
      </booking>
    </div>
  </div>

  <roomp-footer>

  </roomp-footer>
</main>