<main
  v-on:click='Bus.$emit("click")'
  v-on:mouseup='Bus.$emit("mouseup")'
  v-on:mousemove='(e) => Bus.$emit("mousemove", e.pageX, e.pageY, e)'
>
  <div class='content'>
    <a href='/' class='logo'>

    </a>

    <div class='status'>
      <span class='reservation'>{{__('reservation.reservation')}}</span> {{ $reservation->code }}: <span
        class='status'>{{ $status_name }}</span>
    </div>


    <div class='photo'>
      <div style='background-image:url({{ image_url($photo) }})'></div>
    </div>

    <div class='info'>
      <p>
        <strong>{{__('reservation.hotel')}}:</strong>
        <span>
        <a target="_blank"
           href="/hotel/{{$hotel->pretty_url}}">{{$hotel->name}}</a> ({{$hotel->regular_name}})</span></p>
      <p>
        <strong>{{ __('reservation.address') }}:</strong>
        <span>{{$hotel->address}}</span>
      </p>
      <p>
        <strong>{{__('reservation.reception_phone')}}:</strong>
        <span>{{$hotel->reception_phone}}</span>
      </p>
      <p>
        <strong>{{__('reservation.checkin')}}:</strong>
        <span>{{$reservation->fromCarbon->toDateString()}}
          ({{ __('common.after') }} {{date('H:i', strtotime($hotel->checkin))}})</span>
      </p>
      <p>
        <strong>{{__('reservation.checkout')}}:</strong>
        <span>{{$reservation->tillCarbon->toDateString()}}
          ({{ __('common.before') }}  {{date('H:i', strtotime($hotel->checkout))}})</span>
      </p>
      <p>
        <strong>{{__('reservation.guest_name')}}:</strong>
        <span>{{$reservation->guest_name}}</span>
      </p>
    </div>

    <h2>{{__('reservation.info')}}</h2>
    <div class='occupancies'>
      @foreach ($occupancies as $occupancy)
        <div class='occupancy'>
          <div>
            {{$occupancy->allotment->{$locale} }},
            {{$occupancy->room->class->{$locale} }},
            {{ pluralize('common.adults', $occupancy->adults) }},
            @if ($occupancy->children > 0) {{ pluralize('common.children', $occupancy->children) }}, @endif
            @if ($occupancy->infants > 0) {{ pluralize('common.infants', $occupancy->infants) }}, @endif
            {{ pluralize('common.nights', $nights) }},
            {{ __('reservation.occupancy.'. ($occupancy->breakfast_included ? 'breakfast_included' : 'no_breakfast')) }}
          </div>
          <div>{{$nights}}
            x {{ toCurrency(number_format($occupancy->customerPrice, 2,',',' '), $reservation->currency) }}</div>
        </div>
      @endforeach
      @if ($reservation->discount > 0)
        <div class='discount'>
          <div>
            {{ __('reservation.promo_code_discount', ['promo_code' => $reservation->promoCode->code]) }}
          </div>
          <div>{{ toCurrency(number_format($reservation->customerDiscount, 2,',',' '), $reservation->currency) }}</div>
        </div>
      @endif
      <div class='total' style="flex-wrap: wrap;">
        <div>{{__('reservation.total')}}</div>
        <div>
          {{ toCurrency(number_format($reservation->customerTotal, 2, ',', ' '), $reservation->currency) }}
        </div>
        @if ($hotel->currency !== $reservation->currency)
        <div class="original-total" style="
            width: 100%;
            text-align: right;
            font-size: 15px;
            font-weight: 400;
        ">
          {{ toCurrency(number_format($reservation->total, 2, ',', ' '), $hotel->currency) }}
        </div>
        @endif
      </div>
      {{--@if ($status->status->active && (($reservation->payment() && !$status->status->online) || ($status->status->online && !$status->status->confirmed)))--}}
        {{--<payment-status>--}}

        {{--</payment-status>--}}
      {{--@endif--}}
    </div>

    <div class='controls'>
      @if($status->status->active === true && $reservation->channel_id === 1 && !$reservation->is_fulfilled)
        <button class='cancel' v-on:click='cancel'>
          {{ __('reservation.cancel') }}
        </button>
        @if ($status->status->online === false && $hotel->payment_online && $hotel->currency === 'RUB')
          <button class='pay' v-if='paymentStatus !== true' v-on:click='pay'>
            {{__('reservation.pay')}}
          </button>
        @endif
      @endif
    </div>

    <div class='additional'>
      <h2>{{__('reservation.additional')}}</h2>
      <p>
        <strong>{{ __('reservation.important')}}</strong>
        <span>{{ __('reservation.id_needed') }}</span>
      </p>
      <!--
      <p>
        <strong>{{ __('reservation.breakfast') }}:</strong>
        <span>{{ __('reservation.has_breakfast', ['not' => $hotel->breakfast ? '' : __('reservation.not')]) }}</span>
      </p>-->
      @if ($hotel->reach)
        <p>
          <strong>{{ __("reservation.reach") }}:</strong>
          <span>{{$hotel->reach}}</span>
        </p>
      @endif
      @if ($hotel->landmark)
        <p>
          <strong>{{ __("reservation.landmark") }}:</strong>
          <span>{{$hotel->landmark}}</span>
        </p>
      @endif
      <img style="width: 100%;" src='{{ map_image_url($hotel->map) }}'/>
    </div>
  </div>
</main>