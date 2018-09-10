<div class='content reservation'>
  <h1>{{ __("reservation.reservation") }}: {{$reservation->code}}</h1>

  <div class='info' style='color: black;'>
    <p>
      <strong>{{__('reservation.hotel')}}:</strong>
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
      <strong>{{__('reservation.checkin')}}:</strong>
      <span>{{$reservation->fromCarbon->toDateString()}} ({{ __("common.after") }} {{date('H:i', strtotime($hotel->checkin))}})</span>
    </p>
    <p>
      <strong>{{__('reservation.checkout')}}:</strong>
      <span>{{$reservation->tillCarbon->toDateString()}} ({{ __("common.before") }} {{date('H:i', strtotime($hotel->checkout))}})</span>
    </p>
    <p>
      <strong>{{__('reservation.nights')}}:</strong>
      <span>{{ $nights }}</span>
    </p>
    <p>
      <strong>{{__('reservation.guests')}}:</strong>
      <span>{{ $guests }}</span>
    </p>
    <p>
      <strong>{{__('reservation.guest_name')}}:</strong>
      <span>{{$reservation->guest_name}}</span>
    </p>
    @if($phone)
      <p>
        <strong>{{__('reservation.phone')}}:</strong>
        <span>{{ $phone }}</span>
      </p>
    @endif
    @if ($reservation->comment)
      <p>
        <strong>{{__('reservation.comment')}}: </strong>
        <span>{{$reservation->comment}}</span>
      </p>
    @endif
  </div>

  <h2>{{__('reservation.info')}}</h2>

  @component('mail::table')
    |  |  |
    | :------------ | ---------:|
    @foreach ($occupancies as $occupancy)
      | {{ $occupancy->allotment->{$hotelierLocale} }}, {{ $occupancy->room->class->{$hotelierLocale} }}, {{ pluralize('common.adults', $occupancy->adults) }}, @if($occupancy->children > 0){{ pluralize('common.children', $occupancy->children) }}, @endif @if($occupancy->infants > 0) {{ pluralize('common.infants', $occupancy->infants) }}, @endif {{ pluralize('common.nights', $nights) }}, {{ __('reservation.occupancy.'. ($occupancy->breakfast_included ? 'breakfast_included' : 'no_breakfast')) }} | {{$nights}} x {!!  toCurrency(number_format($occupancy->price, 2,',',' '), $hotel->currency) !!} |
    @endforeach
    @if ($reservation->discount > 0)  | {{ __('reservation.discount') }} | {{ toCurrency(number_format($reservation->discount, 2,',',' '), $hotel->currency) }} |
    @endif| {{__("reservation.total_vat")}} | {{ toCurrency(number_format($reservation->total, 2, ',', ' '), $hotel->currency) }} |
  @endcomponent

  <div class='additional'>
    <h2>{{__('reservation.additional')}}</h2>
    <p>
      <strong>{{ __("reservation.payment") }}: </strong>
      <span>
      @if ($reservation->status->status->online)
        {{ __("reservation.paid_online") }}
      @else
        {{ __("reservation.pay_at_hotel") }}
      @endif
      </span>
    </p>
  </div>
</div>