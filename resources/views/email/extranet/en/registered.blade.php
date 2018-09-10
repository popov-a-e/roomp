  @component('email.reservation.layout', ['locale' => $locale])
  <div class='reminder'>
    <div class='greeting'>
      <p>Dear partner,</p>
      @if ($isNewAccount)
      <p>Congratulation with the registration in <b>Roomp</b>!</p>
      <p>You became a partner of the Roomp chain of hotels and got access to the Roomp Extranet. We advise you to keep
        this email, so your login and password will be always within reach.</p>
      <p><b>Your access information:</b></p>
      <p><b>Link to <a href='{{ "https://$subdomain.roomp.co/login"}}'>extranet</a><br> </b>
        <b>Login: {{ $email }}</b><br>
        <b>Password: {{ $password }}</b><br>
        <b>Your property: {{ $hotel->regular_name }} (ID {{ $hotel->id }})</b></p>
      @else
        <p>Your hotel "{{ $hotel->regular_name }}" has been successfully added to <b>Roomp</b>!</p>
        <p><b>Your property ID: {{ $hotel->id }}</b></p>
      @endif
      <p>Having registered with Roomp you have accepted and agreed with the terms of <a href='{{ "https://$subdomain.roomp.co/docs/offer"}}'>the offer</a>.
        You can see the offer in the Extranet, the
        Offer part.</p>
      <p><b>How to start working with us?</b></p>
      <ol>
        <li>
          <b>Print a Roomp guest service reminder</b>
          <p>For the administrator of your hotel, we have prepared a <a
              href='{{ "https://$subdomain.roomp.co/docs/reminder"}}'>guest service reminder</a> - it has Roomp service rules and contacts of our support team. We
            recommend printing and placing it in a place convenient for the administrator.</p>
        </li>
        <li>
          <b>Connect to the Roomp sales channel in your Channel Manager</b>
          @if (!$channel_manager)
          <p>If you have Channel Manager, please contact us at <a href="tel:+78122428458">+7 (812) 242-84-58</a>. </p>
          @else
          <p>To connect to the Roomp sales channel in your Channel Manager (WuBook / OtelMS / Travelline), follow the <a
              href='{{ "https://$subdomain.roomp.co/docs/instruction"}}'> instructions</a>.
            Please note that when you configure the connection, you will need the login and password of the Roomp
            extranet.
          </p>
          @endif
        </li>
      </ol>

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

        <p>Welcome to Roomp!</p>
        <p>Still have questions?</p>
        <p>We are always ready to help!</p>
        <p><b>+7 (812) 242-84-58</b></p>
        <p><b>partners@roomp.co</b></p>
        <p style="float: right; clear: both;"><b>Roomp</b> Team</p>

      </div>
    </div>
  </div>
@endcomponent
