<main>
  <div class='content'>
    <a href='/' class='logo'>

    </a>

    <div class='status'>
      <span class='reservation'>{{__('reservation.reservation')}}</span> {{ $reservation->code }}: <span class='status'>{{ $status_name }}</span>
    </div>

    <div class='info' style='margin-top: 10px;'>
      <p>
        <strong>{{__('reservation.hotel')}}:</strong>
        <span>
        <a target="_blank"
           href="https://roomp.co/hotel/{{$hotel->pretty_url}}">{{$hotel->name}}</a> ({{$hotel->regular_name}})</span></p>
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
        <span>{{$reservation->fromCarbon->toDateString()}} ({{ __('common.after') }} {{date('H:i', strtotime($hotel->checkin))}})</span>
      </p>
      <p>
        <strong>{{__('reservation.checkout')}}:</strong>
        <span>{{$reservation->tillCarbon->toDateString()}} ({{ __('common.before') }}  {{date('H:i', strtotime($hotel->checkout))}})</span>
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
            {{$occupancy->allotment->name }},
            {{$occupancy->room->class->name }},
            {{ pluralize('common.adults', $occupancy->adults) }},
            {{ pluralize('common.children', $occupancy->children) }},
            {{ pluralize('common.infants', $occupancy->infants) }},
            {{ pluralize('common.nights', $nights) }},
            {{ __('reservation.occupancy.'. ($occupancy->breakfast_included ? 'breakfast_included' : 'no_breakfast')) }}
          </div>
          <div>{{$nights}} x {{ toCurrency(number_format($occupancy->price, 2,',',' '), $hotel->currency) }}</div>
        </div>
      @endforeach
      <div class='total'>
        <div>{{__('reservation.total')}}</div>
        <div>
          {{ toCurrency(number_format($total, 2, ',', ' '), $hotel->currency) }}
        </div>
      </div>
    </div>

    <div class='controls'>
      @if($can_cancel)
        <a class='cancel' href='{{ $reservation->code }}/miss'>
          Незаезд
        </a>
      @endif
    </div>

    <div class='additional'>
      <h2>{{__('reservation.additional')}}</h2>
      <p>
        <strong>{{ __('reservation.breakfast') }}:</strong>
        <span>{{ __('reservation.has_breakfast', ['not' => $hotel->breakfast ? '' : __('reservation.not')]) }}</span>
      </p>
    </div>
  </div>
</main>