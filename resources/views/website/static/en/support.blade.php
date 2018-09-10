<div class='content'>
  <h1>Frequently Asked Questions(FAQ)</h1>
  <input v-model='pattern' class="search" placeholder="Search" />
  <a href="#/" class="button-up" v-if='buttonVisible' v-on:click='toTop'>
    <i class='icon-chevron-top'></i><span class='desktop'>Up</span>
  </a>
  <div class="nav-support">
    <div v-for='question in questions' v-if='question.a.some(a => a.text.match(patternRegex))'>
      <h2>@{{question.h}}</h2>
      <a v-if='a.text.match(patternRegex)' v-for='a in question.a' v-on:click='goTo(a.link)' :href='a.link'>@{{a.text}}</a>
    </div>
  </div>

  <div class="faq-content">
    <h2>ROOMP</h2>
    <h3 name='what-is-roomp'>What is Roomp?</h3>

    <p>Roomp is Russia's first branded network of hotels, as well as an online platform designed to spare your time in search of a good, but budget stay. We aggregate and standardize the best hotel rooms at the cheapest price possible.</p>
    <h3 name='standards'>What are the standards of Roomp hotels?</h3>

    <p>Looking for hotels that would meet our quality standards requires time and financial costs. We try to simplify the process of hotel booking as much as possible, but at the same time we guarantee that every room you book is a perfectly clean room with free Wi-Fi, a flat TV, comfortable beds, clean linens and a private bath.</p>
    <h3 name='how-to-get-support'>How do I reach out to Customer Service if I have issues with the booking or my stay?</h3>

    <p>Our Customer Service will do anything to ensure that your experience with Roomp is great. In case of any concerns and/or questions, you can reach out to us by:  </p>
  <!--<li>calling: {{__("header.support_phone")}}
    </li>
    <li>	sending an email to help@roomp.co </li>
    <li>	Whatsapp: +7 (931) 210-25-20 </li>
    <li>	Telegram:  +7 (931) 210-25-20 </li>-->
    <h3 name='how-to-reach-the-hotel'>How do I get to the booked hotel?</h3>

    <p>All the information on the hotel location is detailed in the confirmation of your reservation that you receive via e-mail. On the day of arrival, you will also receive a message with the information about the location of the hotel. If you need any help, you can always contact our customer service by phone, email, Whatsapp or Telegram.</p>
    <h3 name='why-roomp'>Why should I choose Roomp over any other booking website?</h3>

    <p>When you book Roomp we promise to deliver service according to our standards unlike other online platforms that do not standardize their rooms. We periodically audit all of our rooms in order to keep the quality high. We also try to provide the best prices in the market.</p>
    <h2>BOOKING</h2>

    <h3 name='can-i-put-three-in-double'>Can three people book a double?</h3>

    <p>Some hotels accept more than two guests in one room. If you are having a difficulty booking a room for more than two guests, please contact our customer service by phone, email, Whatsapp or Telegram so we can help you find the best solution.</p>
    <h3 name='what-is-standard-time-checkin-checkout'>What is the standard time of check-in and check-out?</h3>

    <p>In most cases, the standard time of arrival is 14:00, and the standard check-out time is 12:00. However, the exact time of arrival and departure should be indicated on the hotel page and on the booking confirmation.</p>
    <h3 name='is-early-checkin-late-checkout-possible'>Can I check-in early and/or check-out late?</h3>

    <p>Early check-in and late check-out both are subject to room availability at the hotel and require an additional payment. The need for early check-in / late check-out is to be noted in the specially designated area when booking the room. However, that does not guarantee that the hotel will be able to provide you with the opportunity. See section "Guest Policy" for more. To clarify the possibility of early check-in or late check-out, please contact our customer service by phone, email, Whatsapp or Telegram.</p>
    <h3 name='how-to-register'>What are the valid Identify proofs that I should carry?</h3>

    <p>You need to carry an identity verification document with you. The identification proofs accepted are Russian Federation Passport, Foreign Passport, Military identity card, Temporary ID card, Passport or ID of a foreigner. IMPORTANT:  Driving license is NOT an identity document.</p>
    <h3 name='can-i-settle-with-pets'>Can I check-in with my pets?</h3>

    <p>The policy for pets varies from hotel to hotel. Accommodation with pets is possible if noted on the hotel's page under "Facilities'</p>
    <h3 name='why-status-is-unconfirmed'>Why did my booking convert to an unconfirmed booking?</h3>

    <p>This is possible in case your booking was non-prepaid. Roomp tries to reach out to you via SMS and over call to make sure your arrival is confirmed. Sometimes when you fail to respond or confirm your arrival, the booking might not be confirmed.
      <!--� ������, ���� � ���� ��������� ����� ��������, ��������������� ��������� � ����� �������� ��������� � �� ������� ��� ������ ��� ��������.-->
    </p>
    <p>If this has happened to you and you want to confirm your booking, do not hesitate to contact our customer service immediately.</p>
    <h3 name='how-to-book-more-than-3-rooms-at-a-time'>How do I book more than three rooms at a time?</h3>

    <p>For booking more than three rooms, you can contact us at our number or email us at help@roomp.co. You need to make an advance payment of 25% of the total booking amount to confirm the booking. You will have to pay using a special link that you will receive from our customer service.</p>
    <h3 name='can-i-book-at-a-third-party'>Can I book a room from my account for someone else?</h3>

    <p>Yes, you can book a room for someone else. The only thing we require is that the data provided at the time of the booking is the same as the data provided at the hotel upon the arrival. It should also be noted that the names of the guests can not be changed after the confirmation of your reservation.</p>
    <h3 name='can-i-book-at-a-few-hours'>Can I book a room for a few hours?</h3>

    <p>The room can be booked for at least a day. We can accommodate you with the room for a few hours; however, the cost will equal the price of a one-day accommodation.</p>
    <h3 name='can-i-book-for-long-time'>Can I book a room for a long term?</h3>

    <p>We can absolutely accommodate you for a long term: all you have to do is contact us at {{__("header.support_phone")}} or through e-mail at help@roomp.co.</p>

    <h2>BOOKING UPDATES AND CANCELLATION</h2>

    <h3 name='what-is-booking-policy'>What is the cancellation policy of Roomp?</h3>

    <p>You can read about the cancellation policy <a href="/guest">here</a>.</p>
    <h3 name='can-i-change-bookings'>Can I make any changes in a confirmed booking?</h3>

    <p>In order to make changes in the confirmed reservation, you need to contact our customer service at {{__("header.support_phone")}} or through e-mail at help@roomp.co, Whatsapp / Telegram +7 (931) 210-25- 20. Any booking changes are only possible prior the time of arrival. You will be charged in accordance <a href="/guest">cancellation policy</a> for cancelling some of your booked rooms.</p>
    <p>Changes in the booking are not possible if the reservation was made with a discount or using a promo code.</p>

    <h3 name='why-additional-prices-change'>Why does the cost of extension differ from the initial fare when I make changes in the booking?</h3>

    <p>That is possible due to our tariffs being dynamic in nature and changing subject to demand and supply. When extending your stay at Roomp hotels, you may find that the extra days can be both cheaper and more expensive than the initial fares.</p>
    <h3 name='can-i-cancel-a-booking'>Can I cancel my booking?</h3>

    <p>Absolutely! We refund the full amount of the booking if canceled more than 48 hours prior the arrival date. In case the cancellation is made less than 48 hours prior the arrival date, we will charge your for a day and refund the rest of the booking. Use any of the following ways to cancel the booking:  </p>
    <ul>
      <li>	use our website </li>
      <ul>
        <li>If you have a Roomp account, go to "My Reservations" in your account and click on the "Cancel" button next to your booking;;</li>
        <li>If you never created a Roomp account, you need to login using automatically generated login and passowrd that were sent to your phone number or through e-mail at the time of your booking </li>
      </ul>
      <li>	contact our customer service by calling {{__("header.support_phone")}}, e-mailing at help@roomp.co or using Whatsapp / Telegram +7 (931) 210-25-20  </li>
    </ul>
    <p>All cancellations will be subject to cancellation policy. Click <a href="/guest">here</a>.</p>
    <h3 name='what-is-maximum-payback-time'>How much time does it take the refund to be credited?</h3>

    <p>The refund will be automatically credited to you within 7-14 days after the cancellation, depending on the issuing bank.</p>
    <h3 name='what-if-i-dont-settle'>What would happen if I did not cancel my pre-paid booking and never checked-in?</h3>

    <p>In that case, in accordance with our  <a href="/guest">cancellation policy</a>, we will charge you for the cost of a two-day stay in the selected room (for 2 nights or more) or the cost of a one-day reservation (for bookings of less than 2 days). The refund will be automatically credited to you within 7-14 days after the cancellation, depending on the issuing bank.
    </p>
    <h2>REFUNDS AND WARRANTY</h2>

    <h3 name='how-to-cancel-a-booking'>How do I pay for my booking?</h3>

    <p>You can either pay for your stay using a credit card immediately at the time of booking or pay at the hotel during check-in.</p>
    <p>On the one hand, the choice to pay at the hotel gives you some flexibility, since you can pay for the booking both in cash and by credit card. On the other hand, you cannot pay during your check-in at some hotels and you cannot book more than three rooms at a time.</p>
    <p>The payment at the time of the booking gives you an opportunity to book as many rooms as you need.</p>
    <h3 name='why-some-hotels-lack-cash-pay'>Why can't I book some hotels without an advance payment?</h3>

    <p>The possibility of payment during check-in is absent for some hotels due to high demand for accommodation in these hotels. You can choose one of our several online payment methods to confirm your booking.</p>
    <h3 name='why-hotelier-changed-the-price'>I booked Roomp for one tariff, but the hotel manager is trying to charge me for a different tariff. Why?</h3>

    <p>If that is the case, immediately contact our customer service by calling {{__("header.support_phone")}}, e-mailing at help@roomp.co or using Whatsapp / Telegram +7 (931) 210-25-20.</p>
    <h3 name='is-it-safe-to-store-the-card-data'>Is it safe to give you my credit card information?</h3>

    <p>Roomp does not process or store any data on bank cards through our servers. The international processing company Payture processes the data of your card.</p>
    <h3 name='can-i-get-a-discount-if-i-book-alone-but-i-book-for-two'>Can I get a discount if I book a double while travelling alone?</h3>

    <p>Prices presented on our website are already the lowest in the market. That is achieved through individual negotiations with each hotel. In return for paying for a double room while travelling alone, you will thus get an extra comfort.</p>
    <h2>PRICES</h2>

    <h3 name='do-i-have-to-pay-commissions'>Do I pay an extra fee while booking Roomp?</h3>

    <p>The prices indicated on our website are final. You only pay the amount you see on the page of the room.</p>
    <h3 name='is-food-included'>Do you offer lunch / dinner with the booking?</h3>

    <p>Roomp does not guarantee the provision of breakfast at every hotel. You can find out about the availability of the included breakfast in the "Facilities" section of the hotel page. Lunches and dinners are provided on a paid basis at the standard prices of the hotel you choose.</p>

    <h2>DISCOUNT, COUPONS AND PROMO CODES</h2>
    <h3 name='how-to-use-promo-code'>How do I use a promo code?</h3>

    <p>Enter your promo code in a designed area while booking your stay.</p>
    <h3 name='why-i-cant-change-bookings-with-discount'>Why can't I modify discounted booking?</h3>

    <p>Roomp does not currently allow you to modify a discounted booking. You can always cancel, get your refund and re-book with changes. The refund will be automatically credited to you within 7-14 days after the cancellation, depending on the issuing bank.</p>
    <h2>SERVICES</h2>

    <h3 name='what-is-difference-between-standard-and-premium'>What is the difference between Standard and Premium rooms?</h3>

    <p>Premium rooms are usually larger and offer an improved quality service.</p>
    <h3 name='can-i-get-transfer'>Can I get a transfer to my hotel?</h3>

    <p>In case the booked hotel provides a transfer, you will find that information in the "Services and Facilities" section at the bottom of the hotel page.</p>
    <p>If you want to book a transfer or find out the cost of the transfer do not hesitate to contact the hotel. You can find the hotel's contact details on the booking confirmation that you receive through e-mail.</p>
    <p>You can also contact our customer service by calling {{__("header.support_phone")}}, e-mailing us at help@roomp.co or through Whatsapp / Telegram +7 (931) 210-25-20</p>
    <p>You can also book a transfer at the airport/train station.</p>
    <h2>PERSONAL ACCOUNT</h2>

    <h3 name='what-to-do-if-already-registered'>What should I do if an account with my e-mail is already registered?</h3>

    <p>In that case, you should do the following: click on the button "Forgot password", choose the method of your password recovery (by e-mail or by phone number), (a) enter the email address or (b) the phone number you specified when registering the account earlier. You will then recieve instructions through e-mail for password recovery or (b) a secret code will be sent to your phone number that you will have to enter in a special field.</p>
    <h3 name='how-to-reset-password'>Forgot my password. What do I do?</h3>

    <p>In that case, you should do the following: click on the button "Forgot password", choose the method of your password recovery (by e-mail or by phone number), (a) enter the email address or (b) the phone number you specified when registering the account earlier. You will then recieve instructions through e-mail for password recovery or (b) a secret code will be sent to your phone number that you will have to enter in a special field.</p>
    <h3 name='how-to-make-an-account-without-email'>How do I create an account without it being tied to my e-mail?</h3>

    <p>You have to indicate a phone number and an e-mail in order to create an account.</p>
    <h2>HOTEL OWNERS</h2>

    <h3 name='how-to-collaborate-hotel'>I am a hotel owner, how do I partner with Roomp?</h3>

    <p>To become a part of our network just contact us by calling {{__("header.support_phone")}} or e-mailing at partners@roomp.co. You can also visit  <a href ="/partner">Partner with Us</a> for more. </p>

    <h2>FOR CORPORATES</h2>

    <h3 name='how-to-collaborate-corporate'>How to do a corporate tie-up with Roomp?</h3>

    <p>If you are looking to corporate with Roomp, just contact us by calling {{__("header.support_phone")}} or e-mailing at partners@roomp.co. You can also visit <a href ="/partner">Partner with us</a> for more. </p>

    <h2>OTHER</h2>

    <h3 name='what-documents-to-have'>What is the identity verification that I should present?</h3>

    <p>You need to show an identity verification at the time of the check-in. The identification proofs accepted are Russian Federation Passport, Foreign Passport, Military identity card, Temporary ID card, Passport or ID of a foreigner.IMPORTANT: Driving license is NOT an identity document.</p>
    <p>We require that the data provided at the time of the booking is the same as the data provided at the hotel upon the arrival. Otherwise, the hotel will have to refuse accommodating you in accordance with Federal Law.</p>
    <h3 name='how-to-reach'>How do I get to my Roomp?</h3>

    <p>All the information on the hotel location and a map will be in the confirmation of your reservation that you receive via e-mail. On the day of arrival, you will also receive a message with the information about the location of the hotel. If you need any help, you can always contact our customer service by phone, email, Whatsapp or Telegram. </p>
    <p>Reach Roomp:</p>
    <li>	email: help@roomp.co </li>
    <li>	phone: {{__("header.support_phone")}}</li>
    <li>	Whatsapp +7 (931) 210-25-20 </li>
    <li>	Telegram +7 (931) 210-25-20</li>
    <h3 name='how-to-get-the-pay-document'>How do I receive a document confirming my booking?</h3>

    <p>A document confirming your booking payment will be automatically sent to your e-mail. In case you lose the confirmation, you can always request a new one by e-mailing help@roomp.co.</p>
    <h3 name='how-to-get-the-receipt'>How do I get a receipt for living in a hotel?</h3>

    <p>To receive a receipt contact the hotel reception upon arrival.</p>


  </div>
</div>