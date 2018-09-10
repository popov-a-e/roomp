@component('email.reservation.layout')
  <div class='reminder'>
    <div class='greeting'>
      <p>Dear partner,</p>
      <p>Congratulation with the registration in <b>Roomp</b>!</p>
      <p>You became a partner of the Roomp chain of hotels and got access to the Roomp Extranet. We advise you to keep
        this email, so your login and password will be always within reach.</p>
      <p><b>Your access information:</b></p>
      <p><b>Link: </b><a href='{{ "https://$subdomain.roomp.co/login"}}'>Экстранет</a><br>
        <b>Login: {{ $email }}</b><br>
        <b>Password: {{ $password }}</b></p>
      <p>Having registered with Roomp you have accepted and agreed with the terms of the offer: <a
          href='{{ "https://$subdomain.roomp.co/docs/offer"}}'>LINK</a>. You can see the offer in the Extranet, the
        Offer part.</p>
      <p><b>How to start working with us?</b></p>
      <ol>
        <li>
          <b>Print a Roomp guest service reminder</b>
          <p>For the administrator of your hotel, we have prepared a guest service <a
              href='{{ "https://$subdomain.roomp.co/docs/reminder"}}'>reminder
              по обслуживанию гостей Roomp</a> - it has Roomp service rules and contacts of our support team. We
            recommend printing and placing it in a place convenient for the administrator.</p>
        </li>
        <li>
          <b>Connect to the Roomp sales channel in your Channel Manager</b>
          <p>To connect to the Roomp sales channel in your Channel Manager (WuBook / OtelMS / Travelline), follow the <a
              href='{{ "https://$subdomain.roomp.co/docs/instruction"}}'>link instructions</a>.
            Please note that when you configure the connection, you will need the login and password of the Roomp
            extranet.
          </p>
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
        <p><b>8 (800) 777 17 56</b></p>
        <p><b>partners@roomp.co</b></p>
        <p style="float: right; clear: both;"><b>Roomp</b> Team</p>

      </div>
    </div>
  </div>
@endcomponent
