@component('email.reservation.layout', ['locale' => $locale])
  <div class='reminder'>
    <div class='greeting'>
      <p>Dear partner,</p>
      <p>As of {{ date('d/m/Y') }}, you have {{ $reservations->count() }} unconfirmed check-ins from our company:</p>
    </div>

    @component('mail::table')
      | Code | Guest | Arrival / Departure |
      | :------------ | :------- | ---------: |
      @foreach ($reservations as $reservation)
        | {{ $reservation->code }} | {{ $reservation->guest_name }} | {{$reservation->fromCarbon->format('d/m/Y')." - ".$reservation->tillCarbon->format('d/m/Y') }} |
      @endforeach
    @endcomponent

    <div>
      <p>Please mark check-ins/no-shows via the following <a href='https://extranet.roomp.co/bookings/{{ $hotelID }}?token={{$token}}'>link</a></p>

      <p style='text-align: center; margin: 0;'>Thank you!</p>
      <p style='text-align: center;'>Roomp Team</p>
    </div>
  </div>
@endcomponent