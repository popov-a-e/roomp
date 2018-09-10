<main>
  <roomp-header
    :user='{{ $user }}'
    :locale='"{{ $locale }}"'
    :currency="'{{ $currency }}'"
    :settings="{{ $settings }}"
  >
  </roomp-header>

  <gallery-overlay :photos='{{ $photos }}' v-if='overlayVisible'></gallery-overlay>
  <datepicker v-if='datepickerVisible'></datepicker>

  <div class='guests-overlay' v-if='id || id === 0' v-on:click='unpick' >
    <guests :id='id'></guests>
  </div>

  <div class='content-container'>
    <payment-overlay v-if='paymentOverlayVisible'></payment-overlay>

    <div class='heroimage'>
      <div
        class='photos'
        style='background-image: url("{{ image_url($heroimage, 2000) }}");'
        v-on:click='heroimageGalleryOpen'
      ></div>
      <h1>{{$hotel->name}}</h1>
      <p><i class="icon-pin"></i>{{$hotel->address}}</p>
    </div>

    <div class='info'>
      <div class='amenities'>
        <h2>{{ __('hotel.facilities') }}</h2>

        <div>
          <button onclick='
              this.parentNode.classList.toggle("full");
              this.querySelector("i").classList.toggle("icon-chevron-down");
              this.querySelector("i").classList.toggle("icon-chevron-top"); //TODO
            '><i class='icon-chevron-down'></i></button>
          @foreach($amenities as $key => $amenity)
            <p class='{{ $amenity->class_name }}'>{{ $amenity->name }}</p>

          @endforeach
        </div>
      </div>

    {{--<!--<room-nav :photos='{{ "'$photos'" }}'></room-nav>--> --}}

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

    <button class='book' v-on:click='toggleBooking' v-if='!bookingVisible'>{{ __("hotel.book") }}</button>

    <booking
      :params='{{ $params }}'
      :request='{{ $request }}'
      :hotel_id='{{ $hotel->id }}'
      :payment-online='{{ "false"/*($hotel->payment_online ? "true" : "false")*/ }}'
      :payment-offline='{{ $hotel->payment_by_card || $hotel->payment_by_cash ? "true" : "false" }}'
      :has-breakfast='{{ $hotel->breakfast ? "true" : "false" }}'
      :user='{{ $user }}'
      :policy='{{ $policy }}'

      v-if='bookingVisible'
    >
    </booking>
  </div>
</main>