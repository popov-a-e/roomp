@component('email.reservation.layout')
  <div class='reminder'>
    <div class='greeting'>
      <p>Уважаемый партнер,</p>
      <p>На {{ date('d/m/Y') }} у вас {{ $reservations->count() }} неподтвержденных заселений от нашей компании:</p>
    </div>

    @component('mail::table')
      | Код | Гость | Заезд / Выезд |
      | :------------ | :------- | ---------: |
      @foreach ($reservations as $reservation)
        | {{ $reservation->code }} | {{ $reservation->guest_name }} | {{$reservation->fromCarbon->format('d/m/Y')." - ".$reservation->tillCarbon->format('d/m/Y') }} |
      @endforeach
    @endcomponent

    <div>
      <p>Пожалуйста,  отметьте заезды / незаезды по <a href='https://extranet.roomp.co/bookings/{{ $hotelID }}?token={{$token}}'>этой ссылке</a></p>

      <p style='text-align: center; margin: 0;'>Спасибо, что вы с нами!</p>
      <p style='text-align: center;'>Команда Roomp</p>
    </div>
  </div>
@endcomponent