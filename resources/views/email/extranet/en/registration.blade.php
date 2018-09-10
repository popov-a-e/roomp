@component('email.reservation.layout', ['locale' => $locale])
  <div class='reminder'>
    <div class='greeting'>
      <p>Dear Partner,</p>
      @if ($isNewAccount)
        <p>Thank you for your interest shown to <b>Roomp!</b></p>
        <p>Register by <a href='{{ "https://$subdomain.roomp.co/register/{$id}?token=$token"}}'>the link</a> to get started
          with Roomp</p>
        <p>
          You will need to come up with login and password.
          Please note that login and password will be used to access the Roomp Extranet and for the Channel Manager
          connection.
        </p>
      @else
        <p>We are glad that you decided to expand your partnership with us!</p>
        <p>To connect the hotel <b>{{ $hotel->regular_name }}</b>
          you need to go through  <a href='{{ "https://$subdomain.roomp.co/new_hotel/{$hotel->id}?token=$token"}}'>the link</a>   and confirm adding a new property to your account.
        </p>
        <p>
          Please note that access to the extranet of the hotel will be available by login and password that you used when registering.
        </p>
      @endif
      <style>
        p, li {
          font-size: 14px !important;
        }

        div.footer p {
          margin: 0;
          padding: 0;
          text-align: left;
        }
      </style>
      <div class="footer">
        <p>Still have questions?</p>
        <p>We are always ready to help!</p>
        <p><b>+7 (812) 242-84-58</b></p>
        <p><b>partners@roomp.co</b></p>

        <p style="float: right;"><b>Roomp</b> Team</p>
      </div>
    </div>
  </div>
@endcomponent
