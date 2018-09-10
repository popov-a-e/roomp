<div class='content reservation'>
  <p class='greeting'>
    {{ __("reservation.mail_greeting", ['user' => $reservation->guest_name]) }}
    <br/>
    <br/>
    {!!  __("reservation.mail_created", ['hotel' => $hotel->name]) !!}
  </p>

  <h1>{{ __("reservation.reservation") }}: {{$reservation->code}}</h1>

  <img style="width: 100%; margin: 20px 0" src='{{ image_url($photo, 2000) }}'/>

  <div class='info'>
    <p>
      <strong>{{__('reservation.hotel')}}:</strong>
      <span>
        <a target="_blank"
           href="{{ url('/') }}/hotel/{{$hotel->pretty_url}}"
        >{{$hotel->name}}</a> ({{$hotel->regular_name}})
      </span>
    </p>
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
      <span>{{$reservation->fromCarbon->toDateString()}} ({{ __("common.after") }} {{date('H:i', strtotime($hotel->checkin))}})</span>
    </p>
    <p>
      <strong>{{__('reservation.checkout')}}:</strong>
      <span>{{$reservation->tillCarbon->toDateString()}} ({{ __("common.before") }} {{date('H:i', strtotime($hotel->checkout))}})</span>
    </p>
    <p>
      <strong>{{__('reservation.guest_name')}}:</strong>
      <span>{{$reservation->guest_name}}</span>
    </p>
  </div>

  <h2>{{__('reservation.info')}}</h2>

  @component('mail::table')
    |  |  |
    | :------------ | ---------:|
    @foreach ($occupancies as $occupancy)
      | {{ $occupancy->allotment->{$userLocale} }}, {{ $occupancy->room->class->{$userLocale} }}, {{ pluralize('common.adults', $occupancy->adults) }}, @if($occupancy->children > 0){{ pluralize('common.children', $occupancy->children) }}, @endif @if($occupancy->infants > 0) {{ pluralize('common.infants', $occupancy->infants) }}, @endif {{ pluralize('common.nights', $nights) }}, {{ __('reservation.occupancy.'. ($occupancy->breakfast_included ? 'breakfast_included' : 'no_breakfast')) }} | {{$nights}} x {{ toCurrency(number_format($occupancy->customerPrice, 2,',',' '), $reservation->currency) }} |
    @endforeach
    @if ($reservation->discount > 0)  | {{ __('reservation.promo_code_discount', ['promo_code' => $reservation->promoCode->code]) }} | {{ toCurrency(number_format($reservation->customerDiscount, 2,',',' '), $reservation->currency) }} |
    @endif| {{__("reservation.total_vat")}} | {{ toCurrency(number_format($reservation->customerTotal, 2, ',', ' '), $reservation->currency) }} |
  @endcomponent

  @if($status->status->active === true && $reservation->channel_id === 1 && !$reservation->is_fulfilled)
    @component('mail::button', ['url' => url("/r/cancel?code={$reservation->code}&cancel_token={$reservation->cancel_token}"), 'color' => 'ghost'])
      {{ __("reservation.cancel") }}
    @endcomponent
  @endif

  <div class='additional'>
    <h2>{{__('reservation.additional')}}</h2>
    <!--<p>
      <strong>{{ __('reservation.breakfast') }}:</strong>
      <span>{{ __('reservation.has_breakfast', ['not' => $hotel->breakfast ? '' : __('reservation.not')]) }}</span>
    </p>-->
    <p>
      <strong>{{ __('reservation.important')}}</strong>
      <span>{{ __('reservation.id_needed') }}</span>
    </p>
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