@extends('layouts.offer')
@section('content')
  <div class="offer">
    <style>
      .offer {
        position: relative;
        float: left;
        width: auto;
      }

      .offer table td {
        vertical-align: top;
      }

      .offer table {
        table-layout: auto;
        width: 100%;
        float: left;
        position: relative;
        clear: both;
      }

      .offer table td:nth-child(2) {
        width: 50px;
        max-width: 50px;
        min-width: 50px;
      }

      td img {
        position: relative;
        height: 40px;
      }

      img.logo {
        float: right;
        height: 40px;
      }

      img.logo-thumb {
        float: left;
      }
    </style>
    <table>
      <tr>
        <td colspan="3" style="height: 60px;">
          <img class='logo-thumb' src="{{base_path('public/img/pattern.png')}}" />
          <img class="logo" src="{{base_path('public/img/logo.png')}}" />
        </td>
      </tr>
      <tr>
        <td>Agency Agreement – Offer № {{ $id }}</td>
        <td></td>
        <td>სააგენტო ხელშეკრულება - ოფერტა № {{ $id }}</td>
      </tr>
      @if ($date ?? false)
      <tr>
        <td>Acceptance date: {{ $date }}</td>
        <td></td>
        <td>აქცეპტის თარიღი: {{ $date }}</td>
      </tr>
      @endif
      <tr>
        <td>The city of {{ $hotel->city->en }}</td>
        <td></td>
        <td>{{ $hotel->city->en }}</td>
      </tr>
      <tr>
        <td>This Agency Agreement is an offer of the Limited Liability Company ROOMP GEORGIA, hereinafter referred to as the
          Agent or Roomp, addressed to <!-- a limited liability company/private entrepreneur/joint stock company
          “___________________”-->{{ $organization->name }}, hereinafter referred to as the Principal, to enter into an agency agreement on the
          following terms and conditions. This Agency Agreement - the Offer (hereinafter referred to as the Agreement) is
          recognized as concluded from the moment of its acceptance by the Principal and has legal force in accordance with
          Article 328 of the Civil Code of Georgia, being equivalent to an agreement signed by the parties with their own
          hands.
        </td>
        <td></td>
        <td>წინამდებარე სააგენტო ხელშეკრულება წარმოადგენს შეზღუდული საზოგადოება  შპს რუუმპ ჯორჯია-ს (შემდგომში მოხსენიენული
          როგორც „აგენტი“ ან „რუუმპ“) ოფერტას <!--შეზღუდული პასუხისმგებლობის საზოგადოებისთვის/ ინდივიდუალური მეწარმისთვის/
          სააქციო საზოგადოებისთვის „____________________________“-->{{ $organization->name }}, რომელიც შემდგომ იწოდება „პრინციპალი“, მისამართით, რათა
          დადებულ იქნეს სააგენტო ხელშეკრულება ქვემოთ ჩამოთვლილი პირობებით. წინამდებარე სააგენტო ხელშეკრულება - ოფერტა
          (შემდგომ ხელშეკრულება) ითვლება დადებულად პრინციპალის მიერ მისი აქცეპტის მომენტიდან და საქართველოს სამოქალაქო
          კოდექსის მუხლი 328 შესაბამისად არის იურიდიული ძალის მქონე და მხარეთა მიერ პირადად ხელმოწერილი ხელშეკრულების
          თანაბარზომიერი.
        </td>
      </tr>
      </tr>
      <tr>
        <td>The fact confirming the full and unconditional acceptance of the terms and conditions set forth below and the
          acceptance of this offer is clicking by the Principal the “Accept” button at the end of this Agreement.
        </td>
        <td></td>
        <td>ქვემოთ მოყვანილი პირობების მიღების სრული და უპირობო დადასტურების აქტს და წინამდებარე ოფერტის აქცეპტას
          წარმოადგენს პრინციპალის მიერ წინამდებარე ხელშეკრულების ბოლოს განთავსებულ ღილაკზე „აქცეპტი“ დაჭერა.
        </td>
      </tr>
      <tr>
        <td>To confirm the acceptance, the Principal also represents that it has provided complete and reliable data
          allowing it to be accurately identified when registering as a partner of ROOMP GEORGIA LLC on the site
          https://extranet.roomp.co/ . The Agent reserves the right to verify the data provided during registration and to
          withdraw the offer in case of their inaccuracy or incompleteness.
        </td>
        <td></td>
        <td>აქცეპტის დადასტურებისთვის პრინციპალი ასევე გარანტიას იძლევა, რომ წარმოადგინა სრული და უტყუარი მონაცემები,
          რომლებიც მისი ზუსტი იდენტიფიცირების საშუალებას იძლევა საიტზე https://extranet.roomp.co/ შპს „რუუმპ ჯორჯიას“
          პარტნიორის სახით რეგისტრაციის დროს. აგენტი უფლებას იტოვებს ჩაატაროს რეგისტრაციის დროს წარმოდგენილი მონაცემების
          შემოწმება და გაიწვიოს ოფერტა, თუ დადგინდა, რომ ის არ არის უტყუარი ან სრული.
        </td>
      </tr>
      <tr>
        <td>The Agent and the Principal are jointly referred to as the Parties, and individually as the Party.</td>
        <td></td>
        <td>აგენტი და პრინციპალი შემდგომ ერთობლივად იწოდებიან „მხარეები“, ხოლო ცალ-ცალკე - „მხარე“.</td>
      </tr>
      <tr>
        <td><b> SUBJECT OF THE AGREEMENT AND GENERAL PROVISIONS</b></td>
        <td>1.</td>
        <td><b>ხელშეკრულების საგანი და საერთო დებულებები</b></td>
      </tr>
      <tr>
        <td>The Agent undertakes for the fee and on behalf of the Principal to search for the clients, individuals and legal
          entities, on the services provided by the Principal, to sell these services on own behalf in the interests and for
          the account of the Principal. Acting in own right, the Agent independently enters into contracts for the provision
          of hotel services with third parties, or third parties independently conclude agreements with the Principal on the
          application (booking) of the Agent.
        </td>
        <td>1.1.</td>
        <td>აგენტი ვალდებულებას იღებს, რომ ანაზღაურების პირობით პრინციპალის დავალებით მოიძიოს კლიენტი-ფიზიკური პირები
          პრინციპალის მიერ გასაწევი მომსახურებისთვის, მოახდინოს მათი რეალიზება საკუთარი სახელით პრინციპალის ინტერესებსა და
          მის ხარჯზე. გამოდის რა საკუთარი სახელით, აგენტი დამოუკიდებლად სდებს ხელშეკრულებებს მესამე პირებთან სასტუმროს
          მომსახურების გაწევაზე, ან მესამე პირები განაცხადის (ჯავშნის) მიხედვით დამოუკიდებლად დებენ ხელშეკრულებებს
          პრინციპალთან.
        </td>
      </tr>
      <tr>
        <td>The Principal undertakes to provide services for the temporary accommodation of clients - individuals attracted
          by the Agent (hereinafter referred to as the ROOMP Clients or Clients) in the rooms of the hotel
          “{{ $hotel->regular_name }}” specially prepared by the Parties to this Agreement at the address:
          {{ $hotel->address_en }} (hereinafter referred to as the Hotel) , hereinafter referred to as the
          Services.
        </td>
        <td>1.2.</td>
        <td>პრინციპალი ვალდებულებას იღებს განახორციელოს აგენტის მიერ მოზიდული კლიენტი - ფიზიკური პირების (შემდგომ ტექსტის
          მიხედვით - „რუუმპის კლიენტები“ ან „კლიენტები“) მომსახურება მოცემული ხელშეკრულების მხარეთა მიერ მათი დროებითი
          განთავსებით სასტუმრო „{{ $hotel->regular_name }}“ სპეციალურად მომზადებულ ნომრებში მისამართზე:
          „{{ $hotel->address_en }}“ (შემდგომ - „სასტუმრო“), რომლებიც შემდგომ იწოდებიან „მომსახურება“.
        </td>
      </tr>
      <tr>
        <td>The Principal undertakes to provide the Agent with the Rooms for sale in accordance with their availability
          throughout the term of this Agreement. The Principal will accommodate ROOMP’s Clients in the rooms according to
          the categories specified in the personal account (hereinafter referred to as the ROOMP Rooms).
        </td>
        <td>1.3.</td>
        <td>პრინციპალი ვალდებულებას იღებს წინამდებარე ხელშეკრულების მთელი ვადის მოქმედების განმავლობაში აგენტს
          რეალიზებისთვის გამოუყოს ნომრები მათი წვდომადობის შესაბამისად. რუუმპის კლიენტებს პრინციპალი განათავსებს პირად
          კაბინეტში განსაზღვრული კატეგორიის ნომრებში (შემდგომ ტექსტის მიხედვით - „რუუმპის ნომრები“).
        </td>
      </tr>
      <tr>
        <td>The Parties agree that payment to the Principal for booked rooms may be calculated by various methods, agreed by
          the parties, in particular:
        </td>
        <td>1.4.</td>
        <td>მხარეები თანხმდებიან, რომ პრინციპალისთვის გადასახდელი დაჯავშნული ნომრების საფასური შეიძლება იქნეს
          გაანგარიშებული: მხარეთა მიერ შეთანხმებული სხვადასხვა ხერხებით:
        </td>
      </tr>
      <tr>
        <td>(1) For each room booked through the Agent during the term of this Agreement, the Agent will pay the Principal a
          daily fee placed in the Principal’s account;
        </td>
        <td></td>
        <td>(1) აგენტის მეშვეობით წინამდებარე ხელშეკრულების მოქმედების ვადის განმავლობაში ყოველ დაჯავშნულ ნომერში აგენტი
          პრინციპალს გადაუხდის სადღეღამისო ტარიფს, რომელიც განთავსებული არის პრინციპალის პირად კაბინეტში;
        </td>
      </tr>
      <tr>
        <td>(2) The Parties shall agree a certain rate (in percentage) of the cost of the room at which the Agent has the
          right to sell services (to place the information about the rooms) without fixing the specific cost of the room.
          The amount of such a rate is specified in the Principal’s account;
        </td>
        <td></td>
        <td>(2) ნომრის ღირებულებიდან გამომდინარე მხარეთა მიერ თანხმდება განსაზღვრული განაკვეთი (პროცენტებში), რომლის
          მიხედვითაც აგენტს უფლება აქვს განახორციელოს მომსახურების რეალიზება (განათავსოს ინფორმაცია ნომრების შესახებ) ნომრის
          კონკრეტული ღირებულების ფიქსირების გარეშე. ასეთი განაკვეთის ოდენობა დგინდება პრინციპალის პირად კაბინეტში;
        </td>
      </tr>
      <tr>
        <td>(3) Another method agreed by the Parties.</td>
        <td></td>
        <td>(3) მხარეთა მიერ შეთანხმებული სხვა წესით.</td>
      </tr>
      <tr>
        <td><b>RIGHTS AND OBLIGATIONS OF THE PARTIES</b></td>
        <td>2.</td>
        <td><b>მხარეთა უფლებები და მოვალეობები</b></td>
      </tr>
      <tr>
        <td><b>Obligations of the Principal:</b></td>
        <td>2.1.</td>
        <td><b>პრინციპალის მოვალეობები:</b></td>
      </tr>
      <tr>
        <td>To provide Services of appropriate quality in accordance with the Quality Standards, and in the absence or
          incompleteness of the terms of the Agreement - in accordance with the requirements usually specified for the
          services provided by the Principal.
        </td>
        <td>2.1.1.</td>
        <td>გაწეულ იქნეს სათანადო ხარისხის მომსახურება ხარისხის სტანდარტების, შესაბამისად, ხოლო ხელშეკრულების პირობების
          არარსებობის ან არასრულყოფილების შემთხვევაში - იმ მოთხოვნებით, რომლებიც ჩვეულებრივ წაეყენება პრინციპალის მიერ
          გაწეულ მომსახურებას.
        </td>
      </tr>
      <tr>
        <td>To book Roomp rooms in accordance with the Agent’s e-application.</td>
        <td>2.1.2.</td>
        <td>რუუმპის ნომრების დაჯავშნა ხორციელდებოდეს აგენტის ელექტრონული განაცხადის შესაბამისად.</td>
      </tr>
      <tr>
        <td>To ensure that the room is ready for the arrival of Roomp's Clients by the check-in time set in the Hotel. The
          check-in time is specified by the Principal in its account.
        </td>
        <td>2.1.3.</td>
        <td>სასტუმროში განთავსების დადგენილი დროისთვის იქნეს უზრუნველყოფილი ნომრის მზადყოფნა რუუმპის კლიენტების
          დაბინავებისთვის. განთავსების დრო მიეთითოს პრინციპალის მიერ პირად კაბინეტში.
        </td>
      </tr>
      <tr>
        <td>To accommodate Roomp’s Clients in accordance with the document confirming the booking received from the Agent.
        </td>
        <td>2.1.4.</td>
        <td>რუუმპის კლიენტების განთავსება ხდებოდეს აგენტისაგან მიღებული იმ დოკუმენტის შესაბამისად, რომელიც ადასტურებს
          დაჯავშნას.
        </td>
      </tr>
      <tr>
        <td>To monitor the status of booking of the Roomp rooms by receiving emails from the Agent and using the account on
          the Roomp website. Subject to availability of Roomp rooms on the Agent’s platform, the booking made by the Roomp’s
          Client through the Agent’s platform is automatically regarded as confirmed by the Principal.
        </td>
        <td>2.1.5.</td>
        <td>აგენტისგან ელექტრონული წერილების მიღების მეშვეობით და რუუმპის საიტზე პირადი კაბინეტის დახმარებით თვალი მიედევნოს
          რუუმპის ნომრების დაჯავშნის სტატუსს. აგენტის პლატფორმაზე რუუმპის წვდომადი ნომრების არსებობის შემთხვევაში რუუმპის
          კლიენტის მიერ აგენტის პლატფორმის მეშვეობით განხორციელებული დაჯავშნა ავტომატურად ფასდება როგორც პრინციპალის მიერ
          დადასტურებული.
        </td>
      </tr>
      <tr>
        <td>To settle with the Agent for the reporting period in the amount and on the conditions set forth in Section 3 of
          this Agreement.
        </td>
        <td>2.1.6.</td>
        <td>ანგარიშსწორება აგენტთან საანგარიშო პერიოდის მიხედვით განხორციელდეს იმ ოდენობით და პირობებით, რომლებიც
          გადმოცემული არის წინამდებარე ხელშეკრულების მე-3 განაყოფში.
        </td>
      </tr>
      <tr>
        <td>In case the Roomp room has been booked through channels other than Roomp channels, the Principal undertakes to
          notify the Agent within one hour after the fact of booking through the software of the Channel Manager type (if
          any) or by entering relevant information in the account of the Agent’s platform (Extranet), or by sending a
          confirmation by email to partners@roomp.co . In case of violation of this condition, the Principal agrees to pay
          the penalty to the Agent in the amount of the Roomp’s fee per day of accommodation in the corresponding Roomp
          room.
        </td>
        <td>2.1.7.</td>
        <td>იმ შემთხვევაში, თუ რუუმპის ნომერი დაჯავშნულ იქნა რუუმპის არხებისგან განსხვავებული არხებით, პრინციპალი
          ვალდებულებას იღებს აცნობოს აგენტს დაჯავშნის ფაქტის შემდეგ ერთი საათის განმავლობაში ChannelManager-ის (თუ ასეთი
          არსებობს) ტიპის პროგრამული უზრუნველყოფის მეშვეობით ან აგენტის პლატფორმის პირად კაბინეტში (ექსტრანეტში) შესაბამისი
          ინფორმაციის შეტანის დახმარებით, ან partners@roomp.co. ელექტრონული ფოსტით დადასტურების გაგზავნით. მოცემული პირობის
          დარღვევის შემთხვევაში პრინციპალი ვალდებულებას იღებს გადაუხადოს აგენტს საჯარიმო პირგასამტეხლო რუუმპის შესაბამის
          ნომერში ერთი დღე-ღამით განთავსების რუუმპის ტარიფის ღირებულების ოდენობით.
        </td>
      </tr>
      <tr>
        <td>To provide the Agent the right to carry out marketing and organizational activities related to the
          implementation of the Agency’s activities for the entire term of this Agreement.
        </td>
        <td>2.1.8.</td>
        <td>წინამდებარე ხელშეკრულების მოქმედების მთელი ვადით გადასცეს აგენტს უფლება განახორციელოს მარკეტინგული და
          ორგანიზაციული ღონისძიებები, რომლებიც დაკავშირებული არის სააგენტო საქმიანობის განხორციელებესთან.
        </td>
      </tr>
      <tr>
        <td>The Principal has the right:</td>
        <td>2.2.</td>
        <td>პრინციპალს უფლება აქვს:</td>
      </tr>
      <tr>
        <td>To withdraw the unpaid booking from the previously booked Roomp room in case of no-show of the Roomp’s Client
          from 18:00 on the day of the estimated time of arrival, notifying the Agent of the no-show of the Client by
          entering the relevant information in the account on the Agent’s website, or by sending a confirmation by email to
          partners@roomp.co.
        </td>
        <td>2.2.1.</td>
        <td>რუუმპის კლიენტის შემოუსვლელობის შემთხვევაში მოხსნას ფასგადაუხდელი ჯავშანი რუუმპის ადრე დაჯავშნილი ნომრიდან
          შემოსვლის სავარაუდო დროის დღის 18:00 საათიდან და კლიენტის შემოუსვლელობის შესახებ აცნობოს აგენტს შესაბამისი
          ინფორმაციის დადებით პირად კაბინეტში აგენტის საიტზე, ან დადასტურების გაგზავნით ელექტრონული ფოსტით partners@roomp
          .co.
        </td>
      </tr>
      <tr>
        <td>To settle clients other than Roomp’s clients in the Roomp rooms with whatever rooms remain, provided that all
          other rooms of the Hotel of the same class as the Roomp room are occupied.
        </td>
        <td>2.2.2.</td>
        <td>რუუმპის კლიენტებისგან განსხვავებული კლიენტების შესახლება რუუმპის ნომრებში განახორციელოს დანაშთი პრინციპით იმ
          პირობით, რომ სასტუმროს ყველა სხვა ნომერი იმავე კლასის, როგორიც რუუმპის ნომრებია, დაკავებული არის.
        </td>
      </tr>
      <tr>
        <td>Obligations of the Agent:</td>
        <td>2.3.</td>
        <td>აგენტის მოვალეობები:</td>
      </tr>
      <tr>
        <td>To send clients to the Hotel, for which the Principal pays the fee to the Agent.</td>
        <td>2.3.1.</td>
        <td>გააგზავნოს სასტუმროში კლიენტები, რისთვისაც პრინციპალი აგენტს უხდის ანაზღაურებას.</td>
      </tr>
      <tr>
        <td>To inform the Principal about each booking by automatically updating the information in the Principal’s account
          on the Agent’s platform and/or sending the application via email, and (in the absence of the above-mentioned
          communication channels) using other means of communication. The application shall indicate the name and surname of
          the Roomp’s Client, the date of arrival and departure, the category of the room, the fee and the form of payment
          for accommodation.
        </td>
        <td>2.3.2.</td>
        <td>თითოეული დაჯავშნის შესახებ ინფორმაცია მიაწოდოს პრინციპალს ინფორმაციის ავტომატური განახლებით პრონციპალის პირად
          კაბინეტში აგენტის პლატფორმაზე და/ან განაცხადის გაგზავნით ელექტრონული ფოსტით, აგრეთვე (კავშირის ზემოხსენებული
          არხების არარსებობის შემთხვევაში) კომუნიკაციის სხვა საშუალებების დახმარებით. განაცხადში მიეთითება რუუმპის კლიენტის
          გვარი და სახელი, შესვლისა და გასვლის თარიღები, ნომრის კატეგორია, ტარიფი და განთავსებისთვის საფასურის გადახდის
          ფორმა.
        </td>
      </tr>
      <tr>
        <td>On the basis of the results of each month, to send to the Principal the acts on the provision of services
          attached with the reports on the provision of services, to the contact email address or by posting in the
          Principal’s account on the Agent’s platform before the 5th day of the month following the reporting one. In the
          Agent’s report the surnames of the Clients, the dates of stay, Roomp’s fees, and the total amount for
          accommodation, the size of the agency fee are indicated. The Principal approves the Act by clicking the “Accept”
          button in the Principal’s Account on the Agent’s platform within 3 working days from the moment of placing the Act
          in the Principal’s Account on the Agent’s platform. If there are objections to the Act, the Principal has the
          right not to click the “Accept” button and must send motivated objections to the Agent by email and by filling in
          the “Objections to Act” form (with or without attachment of scanned written objections) and clicking the “Send”
          button. In the event that the Principal does not approve the Act and does not send motivated objections within the
          specified 3 working days from the moment of placement, the Act is recognized as duly signed and the services
          provided.
        </td>
        <td>2.3.3.</td>
        <td>ყოველი თვის შედეგების მიხედვით საანგარიშოს შემდეგი თვის 5 რიცხვამდე ელექტრონული ფოსტის საკონტაქტო მისამართზე ან
          განთავსებით პრინციპალის პირად კაბინეტში აგენტის პლატფორმაზე პრინციპალს გაუგზავნოს მომსახურების გაწევის აქტები
          მომსახურების გაწევის შესახებ ანგარიშების დართვით. ანგარიშში მიეთითება კლიენტების გვარები, ცხოვრების ვადები,
          რუუმპის ტარიფი, განთავსებისთვის ანგარიშის საერთო თანხა, სააგენტო ანაზღაურების ოდენობა. პრინციპალის პირად კაბინეტში
          აგენტის პლატფორმაზე აქტის განთავსებიდან 3 სამუშაო დღის განმავლობაში პრინციპალი ამტკიცებს აქტს დაჭერით ღილაკზე
          „აქცეპტი“ პრინციპალის პირად კაბინეტში აგენტის პლატფორმაზე. აქტის მიხედვით საწინააღმდეგო აზრის არსებობის
          შემთხვევაში პრინციპალს უფლება აქვს არ დააჭიროს ღილაკს „აქცეპტი“ და აგენტის მისამართით უნდა გააგზავნოს მოტივირებული
          საწინააღმდეგო მოსაზრება ელექტრონული ფოსტით და ფორმა „შედავება აქტზე“ შევსების მეშვეობით (სკანირებული წერილობითი
          შედავებების დართვით ან თანდართვის გარეშე) და ღილაკზე „გაგზავნა“ დაჭერით. იმ შემთხვევაში, თუ განთავსებიდან
          მითითებული 3 დღის განმავლობაში პრინციპალი არ ამტკიცებს აქტს და არ აგზავნის მოტივირებულ საწინააღმდეგო აზრს, აქტი
          მიიჩნევა სათანადო წესით ხელმოწერილად, ხოლო მომსახურება გაწეულად.
        </td>
      </tr>
      <tr>
        <td>The Agent has the right:</td>
        <td>2.4.</td>
        <td>აგენტს უფლება აქვს:</td>
      </tr>
      <tr>
        <td>At any time, to inspect the Roomp’s Rooms, in order to verify the Principal’s compliance with the Quality
          Standards for rooms agreed by the Parties and the provision of services by the Principal without prior warning to
          the hotel administration and staff at the time of the lack of booking for each particular Roomp’s rooms.
        </td>
        <td>2.4.1.</td>
        <td>ნომრების ხარისხის მომსახურების გაწევის მხარეთა მიერ შეთანხმებული სტანდარტების პრინციპალის მიერ უზრუნველყოფის
          შემოწმების მიზნით ნებისმიერ დროს განახორციელოს რუუმპის ნომრების ინსპექტირება სასტუმროს ადმინისტრაციისა და
          პერსონალის წინასწარი გაფრთხილების გარეშე რუუმპის თითოეული კონკრეტული ნომრის დაჯავშნის არარსებობის მომენტში.
        </td>
      </tr>
      <tr>
        <td>At its discretion, to use third-party hotel reservation services to post information about the Hotel in order to
          attract new clients.
        </td>
        <td>2.4.2.</td>
        <td>ახალი კლიენტების მოზიდვის მიზნით სასტუმროს შესახებ ინფორმაციის განთავსებისთვის საკუთარი შეხედულებით გამოიყენოს
          სასტუმროების დაჯავშნის გარეშე სერვისები.
        </td>
      </tr>
      <tr>
        <td>Under its brand, to post and update any information about the Hotel (photos, description, amenities, how to get,
          nearest places, tariffs, address and name)/availability of rooms and prices on any available resources and on the
          Platform.
        </td>
        <td>2.4.3.</td>
        <td>ნებისმიერ წვდომად რესურსზე და თავისთან პლატფორმაზე საკუთარი ბრენდის ქვეშ განათავსოს და შეცვალოს ნებისმიერი
          ინფორმაცია სასტუმროს (ფოტოსურათები, აღწერილობა, კეთილმოწყობა, როგორ შეიძლება მისვლა, უახლოესი ადგილები, ტარიფები,
          მისამართი და სახელწოდება) / ნომრებთან წვდომისა და ფასების შესახებ.
        </td>
      </tr>
      <tr>
        <td>On their behalf, to communicate with guests and respond to guest reviews on third-party sites/channels of hotel
          promotion under the brand name “Roomp”.
        </td>
        <td>2.4.4.</td>
        <td>საკუთარი სახელით იქონიოს ურთიერთობა სტუმრებთან და ოტელების დაწინაურების გარეშე მოედნებზე/არხებზე რუუმპის ბრენდის
          ქვეშ უპასუხოს სტუმრების გამოძახილებს.
        </td>
      </tr>
      <tr>
        <td>To set the final price of the rooms on channels available to the Agent, including on its Platform.</td>
        <td>2.4.5.</td>
        <td>აგენტისთვის წვდომად არხებზე, მათ რიცხვში საკუთარ პლატფორმაზე დააწესოს ნომრების საბოლოო ფასი.</td>
      </tr>
      <tr>
        <td>To remove the issued branded products from the Principal.</td>
        <td>2.4.6.</td>
        <td>პრინციპალისგან ამოიღოს გაცემული ბრენდირებული პროდუქცია.</td>
      </tr>
      <tr>
        <td>To provide an alternative name to the Hotel for the user on the Platform and on other promotion channels.</td>
        <td>2.4.7.</td>
        <td>პლატფორმაზე და დაწინაურების სხვა არხებზე მომხმარებლისთვის სასტუმროს მიანიჭოს ალტერნატიული სახელი.</td>
      </tr>
      <tr>
        <td>To cancel or change the details of the booking at any time either on behalf of the Client or on own behalf in
          the event that the Client does not answer to the Agent’s calls and messages and is at risk of no-show.
        </td>
        <td>2.4.8.</td>
        <td>იმ შემთხვევაში, თუ კლიენტი არ პასუხობს ზარებს და აგენტის შეტყობინებებს და ხვდება შემოუსვლელობის რისკის ქვეშ
          ნებისმიერ მომენტში კლიენტის ან საკუთარი სახელით გააუქმოს ან შეცვალოს დაჯავშნის დეტალები.
        </td>
      </tr>
      <tr>
        <td><b>CONDITIONS AND PAYMENT PROCEDURE</b></td>
        <td>3.</td>
        <td><b>გადახდის პირობები და წესი</b></td>
      </tr>
      <tr>
        <td><b>The following payment options for accommodation are possible:</b></td>
        <td>3.1.</td>
        <td><b>განთავსებისთვის შესაძლებელი არის საფასურის გადახდის შემდეგი ვარიანტები:</b></td>
      </tr>
      <tr>
        <td>The Roomp Clients can pay for accommodation services independently at the Hotel cash desk at the price specified
          in the electronic confirmation of the room booking, sent by the Agent to the Client’s email.
        </td>
        <td>3.1.1.</td>
        <td>რუუმპის კლიენტებს შეუძლიათ განთავსებასთან დაკავშირებული მომსახურების საფასურის გადახდა დამოუკიდებლად სასტუმროს
          სალაროში იმ ფასად, რომელიც მითითებული არის კლიენტის ელექტრონულ ფოსტაში აგენტის მიერ გაგზავნილი ნომრის დაჯავშნის
          ელექტრონულ დადასტურებაში.
        </td>
      </tr>
      <tr>
        <td>The Clients can also pay for booking on the Agents website. In this case, the Agent shall pay for the
          accommodation of his Clients by transferring funds to the Principal’s settlement account specified in this
          Agreement on the basis of an invoice issued by the Principal in accordance with the settlement reconciliation
          report.
        </td>
        <td>3.1.2.</td>
        <td>კლიენტებს ასევე შეუძლიათ დაჯავშნის საფასურის გადახდა აგენტის საიტზე. ამ შემთხვევაში აგენტი ახორციელებს გადახდას
          თავისი კლიენტების განთავსებისთვის ფულადი სახსრების გადარიცხვით პრინციპალის წინამდებარე ხელშეკრულებაში მითითებულ
          საანგარიშსწორებო ანგარიშზე, პრინციპალის მიერ ურთიერთანგარიშსწორების აქტის შესაბამისად პრინციპალის მიერ გამოტანილი
          ანგარიშის შესაბამისად.
        </td>
      </tr>
      <tr>
        <td>For each booking, the Agent agrees to inform the Principal of the payment method chosen by the Client – the
          payment shall be made by the Agent in a cashless form or by the Roomp Client in the Hotel upon arrival.
        </td>
        <td>3.2.</td>
        <td>აგენტი ვალდებულებას იღებს პრინციპალს თითოეული დაჯავშნისთვის მიაწოდოს ინფორმაცია კლიენტის მიერ შერჩეული გადახდის
          წესის შესახებ - გადახდა ხორციელდება აგენტის მიერ უნაღდო ანგარიშსწორებით ან რუუმპის კლიენტის მიერ სასტუმროში
          შესახლებისას.
        </td>
      </tr>
      <tr>
        <td>The payment for early check-in/late check-out is made in accordance with the Guest Policy.</td>
        <td>3.3.</td>
        <td>ადრეული შესახლების/გვიანი გამგზავრების ანაზღაურება ხორციელდება სასტუმრო პოლიტიკის შესაბამისად.</td>
      </tr>
      <tr>
        <td>The payment for additional services is made by the Roomp Client in cash at the Hotel in accordance with the
          tariffs in force in the Hotel, or by cashless payment on the Agent’s website, if such possibility is provided.
        </td>
        <td>3.4.</td>
        <td>დამატებითი მომსახურების საფასურის ანაზღაურება რუუმპის კლიენტის მიერ ხორციელდება სასტუმროში ნაღდი ფულით
          სასტუმროში მოქმედი ტარიფების შესაბამისად, ან უნაღდო ანგარიშსწორებით აგენტის საიტზე, თუ ასეთი შესაძლებლობა
          გათვალისწინებული არის.
        </td>
      </tr>
      <tr>
        <td>The penalty is imposed on the Agent in case of advance payment by the card through the Agent’s website.</td>
        <td>3.5.</td>
        <td>ბარათის მიხედვით წინასწარი გადახდის შემთხვევაში საჯარიმო პირგასამტეხლოს გადახდევინება ეკისრება აგენტს მისი
          საიტის მეშვეობით.
        </td>
      </tr>
      <tr>
        <td>Penalties for cancellation of booking are calculated in accordance with the Guest Policy.</td>
        <td>3.6.</td>
        <td>დაჯავშნის გაუქმებისთვის ჯარიმების დარიცხვა ხდება სასტუმრო პოლიტიკის შესაბამისად.</td>
      </tr>
      <tr>
        <td>The Agent is not responsible for the conscientious payment of penalties by Roomp Clients.</td>
        <td>3.7.</td>
        <td>აგენტი არ არის პასუხისმგებელი რუუმპის კლიენტების მიერ ჯარიმების კეთილსინდისიერ გადახდაზე.</td>
      </tr>
      <tr>
        <td><b>PROCEDURE FOR PAYMENT OF AGENCY REMUNERATION</b></td>
        <td>4.</td>
        <td><b>სააგენტო ანაზღაურების გადახდის წესი</b></td>
      </tr>
      <tr>
        <td>The amount of the Agent’s fee is determined as the difference between the actual amount paid by the Client for
          the services of accommodation in the Hotel (Price for the Client) and payment in accordance with Clause 1.4 of the
          Agreement.
        </td>
        <td>4.1.</td>
        <td>აგენტის ანაზღაურების ოდენობა განისაზღვრება როგორც სხვაობა კლიენტის მიერ სასტუმროში ყოფნის მომსახურებისთვის
          გადახდილ თანხას (ფასი კლიენტისთვის) და ხელშეკრულების 1.4 პუნქტის შესაბამისად გადახდას შორის.
        </td>
      </tr>
      <tr>
        <td>On the basis of the results of each month, not later than the 5th day of the month following the reporting one,
          the Agent and the Principal will make reconciliation by the Clients sent by the Agent on the basis of the Agent’s
          report and, following the reconciliation, the Agent will draw up the offset certificate and send it to the
          Principal by email and by posting in the Principal’s account on the platform of the Agent. The Principal shall
          approve the Offset Certificate by clicking the “Accept” button in the Principal’s Account on the Agent’s platform
          within 3 working days from the moment of posting the Offset Certificate in the Principal’s Account on the Agent’s
          platform. If there are objections to the Certificate, the Principal has the right not to click the Accept button
          and must send the motivated objections to the Agent by email and by filling in the “Objections to Certificate”
          form (with or without attachment of scanned written objections) and clicking the “Send” button. In the event that
          the Principal does not approve the Certificate and does not send motivated objections within the specified 3
          working days from the moment of placement, the Certificate is considered to be duly signed, and the calculation is
          considered to be payable by the debtor in accordance with this Certificate.
        </td>
        <td>4.2.</td>
        <td>ყოველი თვის შედეგების მიხედვით არაუგვიანეს საანგარიშოს შემდგომი თვის 5 რიცხვის ვადაში, აგენტი და პრინციპალი
          აგენტის ანგარიშის საფუძველზე ახორციელებენ შედარებას აგენტის მიერ გაგზავნილი კლიენტების მიხედვით და შედარების
          შედეგების მიხედვით აგენტი ადგენს ურთიერთჩათვლის აქტს და უგზავნის მას პრინციპალს ელექტრონული ფოსტით და პრინციპალის
          პირად კაბინეტში აგენტის პლატფორმაზე განთავსების მეშვეობით. პრინციპალი ამტკიცებს ურთიერთჩათვლის აქტს პრინციპალის
          პირად კაბინეტში აგენტის პლატფორმაზე ღილაკზე „აქცეპტი“ დაჭერით პრინციპალის პირად კაბინეტში აგენტის პლატფორმაზე
          ურთიერთჩათვლის აქტის განთავსებიდან 3 სამუშაო დღის განმავლობაში. აქტის მიხედვით საწინააღმდეგო აზრის არსებობის
          შემთხვევაში პრინციპალს უფლება აქვს არ დააჭიროს ღილაკს „აქცეპტი“ და აგენტის მისამართით უნდა გააგზავნოს მოტივირებული
          საწინააღმდეგო მოსაზრება ელექტრონული ფოსტით და ფორმა „შედავება აქტზე“ შევსების მეშვეობით (სკანირებული წერილობითი
          შედავებების დართვით ან თანდართვის გარეშე) და ღილაკზე „გაგზავნა“ დაჭერით. იმ შემთხვევაში, თუ განთავსებიდან
          მითითებული 3 დღის განმავლობაში პრინციპალი არ ამტკიცებს აქტს და არ აგზავნის მოტივირებულ საწინააღმდეგო აზრს, აქტი
          მიიჩნევა სათანადო წესით ხელმოწერილად, ხოლო მოცემული აქტის შესაბამისად ანგარიში ექვემდებარება ანაზღაურებას
          დებიტორის მიერ.
        </td>
      </tr>
      <tr>
        <td>Following the reconciliation, the debtor is obliged to settle with the creditor within 5 banking days from the
          receipt of the relevant creditor’s account.
        </td>
        <td>4.3.</td>
        <td>შედარების შედეგების მიხედვით დებიტორი მოვალე არის კრედიტორისგან შესაბამისი ანგარიშის მიღების მომენტიდან 5
          საბანკო დღის განმავლობაში განახორციელოს ანგარიშსწორება კრედიტორთან.
        </td>
      </tr>
      <tr>
        <td>The invoice for payment shall be sent by the Party to the Agreement to the other Party via email to the
          addresses specified in this Agreement and by posting in the Principal’s Account on the Agent’s platform.
        </td>
        <td>4.4.</td>
        <td>ხელშეკრულების მხარე ანგარიშს გადახდისთვის მეორე მხარეს უგზავნის ელექტრონული ფოსტით ხელშეკრულებაში მითითებულ
          მისამართებზე და პრინციპალის პირად კაბინეტში აგენტის პლატფორმაზე განთავსების გზით.
        </td>
      </tr>
      <tr>
        <td><b>REGISTRATION UPON CHECK-IN</b></td>
        <td>5.</td>
        <td><b>რეგისტრაცია სასტუმროში შესახლების დროს</b></td>
      </tr>
      <tr>
        <td>The Principal undertakes to professionally perform all the necessary procedures for registering Roomp’s Clients
          upon check-in. The Principal must ensure that the Roomp’s Client has provided a passport or other identity
          document, the information in which corresponds to the information specified in the electronic booking
          confirmation. In case of inconsistency of personal data, the Principal is obliged to contact the Agent immediately
          before the Client checks in.
        </td>
        <td>5.1.</td>
        <td>პრინციპალი ვალდებულებას იღებს რუუმპის კლიენტების სასტუმროში შესახლებისას პროფესიონალურად შეასრულოს გაფორმების
          ყველა აუცილებელი პროცედურა. პრინციპალი უნდა დარწმუნდეს, რომ რუუმპის კლიენტმა წარმოადგინა პასპორტი ან პირადობის
          სხვა მოწმობა, რომელშიც მოწოდებული ინფორმაცია შეესაბამება დაჯავშნის ელექტრონულ დადასტურებაში მითითებულ ინფორმაციას.
          პერსონალური მონაცემების შეუსაბამობის შემთხვევაში პრინციპალი მოვალე არის დაუყონებლივ დაუკავშირდეს აგენტს კლიენტის
          შესახლების მომენტამდე.
        </td>
      </tr>
      <tr>
        <td>The Principal agrees to notify the Agent of any no-show of Roomp’s Clients by email to partners@roomp.co and/or
          by making appropriate changes in the account on the Agent’s platform within 48 hours after the compulsory
          withdrawal of the booking provided for in Clause 2.2.1. of this Agreement. In the event that the Principal has not
          notified the Agent in due time about the Client’s no-show, the Principal undertakes to pay the difference between
          the Roomp’s Client Price and Roomp’s rate per day during which Roomp was misinformed.
        </td>
        <td>5.2.</td>
        <td>პრინციპალი ვალდებულებას იღებს რუუმპის კლიენტების ნებისმიერი გამოუცხადებლობის შესახებ აცნობოს აგენტს ელექტრონული
          ფოსტით მისამართზე partners@roomp.co და/ან შესაბამისი ცვლილებების შეტანის გზით პირად კაბინეტში აგენტის პლატფორმაზე
          ჯავშნის იძულებითი მოხსნის შემდეგ 48 საათის განმავლობაში, რაც გათვალისწინებული არის წინამდებარე ხელშეკრულების
          2.2.1. პუნქტით. იმ შემთხვევაში, თუ პრინციპალმა არ აცნობა დროულად აგენტს კლიენტის გამოუცხადებლობის შესახებ,
          პრინციპალი ვალდებულებას იღებს გადაიხადოს სხვაობა რუუმპის კლიენტისთვის ფასსა და რუუმპის ტარიფს შორის თითოეულ
          დღე-ღამეზე, რომელთა განმავლობაშიც იყო რუუმპი დეზინფორმირებული.
        </td>
      </tr>
      <tr>
        <td>In the event that the Principal provided false information about the no-show of the Client to Roomp, Roomp
          reserves the right to double his fee for the relevant booking as a fine.
        </td>
        <td>5.3.</td>
        <td>იმ შემთხვევაში, თუ პრინციპალმა წარმოადგინა ყალბი ინფორმაცია რუუმპის კლიენტის გამოუცხადებლობის შესახებ, რუუმპი
          უფლებას იტოვებს გააორმაგოს საკუთარი ანაზღაურება შესაბამისი დაჯავშნისთვის ჯარიმის სახით.
        </td>
      </tr>
      <tr>
        <td>In the event that the Principal cannot settle Roomp’s Clients in the Roomp’s rooms due to non-fulfillment of his
          direct duties stipulated in Clause 2.1. of this Agreement, the Principal undertakes to pay the penalty to the
          Agent in the amount of the cost of two nights of accommodation at the rate provided to the Roomp’s Client, and to
          settle the Roomp Client(s) in the room(s) of higher category without charging additional funds, having previously
          agreed this with the Agent’s representative.
        </td>
        <td>5.4.</td>
        <td>იმ შემთხვევაში, თუ წინამდებარე ხელშეკრულების 2.1. პუნქტით გათვალისწინებული თავისი პირდაპირი მოვალეობების
          შეუსრულებლობის შედეგად პრინციპალს არ შეუძლია რუუმპის კლიენტების შესახლება რუუმპის სანომრე ფონდში, პრინციპალი
          ვალდებულებას იღებს გადაუხადოს აგენტს საჯარიმო პირგასამტეხლო ორი ღამის ცხოვრების საფასურის ოდენობით რუუმპის
          კლიენტისთვის შეთავაზებული ტარიფის მიხედვით და აგენტის წარმომადგენელთან წინასწარი შეთანხმების შემდეგ დამატებითი
          ფულადი სახსრების გადახდის დაკისრების გარეშე შეასახლოს რუუმპის კლიენტ(ებ)ი უფრო მაღალი დონის ნომერში (ებში).
        </td>
      </tr>
      <tr>
        <td>If the Principal can settle the Roomp’s Client neither in the Roomp’s rooms, nor in other rooms of the Hotel due
          to a full occupancy and failure to perform his direct duties as specified in Clause 2.1. of this Agreement, the
          Principal undertakes to pay the penalty to the Agent in the amount of one fourth of the invoice issued to the
          Roomp’s Client, but not less than the cost of two nights of stay at the rate provided to the Roomp’s Client.
        </td>
        <td>5.5.</td>
        <td>იმ შემთხვევაში, თუ წინამდებარე ხელშეკრულების 2.1. პუნქტით გათვალისწინებული თავისი პირდაპირი მოვალეობების
          შეუსრულებლობის და სრული დატვირთვის შედეგად პრინციპალს არ შეუძლია რუუმპის კლიენტების შესახლება რუუმპის სანომრე
          ფონდში და არც სასტუმროს სხვა ნომრებში, პრინციპალი ვალდებულებას იღებს გადაუხადოს აგენტს საჯარიმო პირგასამტეხლო
          რუუმპის კლიენტისთვის წარდგენილი ანგარიშის ერთი მეოთხედის ოდენობით, მაგრამ არანაკლებ ორი ღამის ცხოვრების საფასურის
          ოდენობისა რუუმპის კლიენტისთვის შეთავაზებული ტარიფის მიხედვით.
        </td>
      </tr>
      <tr>
        <td>The Principal reserves the right to refuse settlement or to demand early check-out from the Hotel for the
          Clients violating the rules of stay and use of the Hotel services established by the Principal, without a refund.
          In this case, the Agent’s fee shall be paid in full.
        </td>
        <td>5.6.</td>
        <td>პრინციპალი უფლებას იტოვებს უარი თქვას კლიენტის შესახლებაზე ან მოითხოვოს იმ კლიენტების ვადაზე ადრე გასვლა
          სასტუმროდან ფულადი სახსრების დაბრუნების გარეშე, რომლებიც არღვევენ სასტუმროში ცხოვრებისა და მომსახურებით
          სარგებლობის წესებს. ამასთან ანაზღაურების გადახდა აგენტისთვის ხდება სრული ოდენობით.
        </td>
      </tr>
      <tr>
        <td><b>SIGNAGES AND BRANDED PRODUCTS</b></td>
        <td>6.</td>
        <td><b>სარეკლამო მაჩვენებლები და პროდუქციის ბრენდირება</b></td>
      </tr>
      <tr>
        <td>The Principal agrees and allows the Agent to install interior and exterior sign ages and branded products (guest
          sets, poster or an advertising signboard at the main entrance). All branded products shall be provided free of
          charge.
        </td>
        <td>6.1.</td>
        <td>პრინციპალი ეთანხმება და შესაძლებლობას აძლევს აგენტს დააყენოს საინტერიერო და ბრენდირებული პროდუქცია (სასტუმრო
          ნაკრებები, პოსტერი ან სარეკლამო აბრა მთავარ შესასვლელზე). მთელი ბრენდირებული პროდუქციის შეთავაზება ხდება უფასოდ.
        </td>
      </tr>
      <tr>
        <td>The Principal allows the Agent to make new photos of the rooms provided. The cost of photographing and
          processing images is borne by the Agent. The Principal guarantees not to use photographs taken by the Agent.
        </td>
        <td>6.2.</td>
        <td>პრინციპალი საშუალებას აძლევს აგენტს შექმნას შესათავაზებელი ნომრების ახალი ფოტოსურათები. ფოტოგრაფირებისა და
          გამოსახულების დამუშავების ხარჯები ეკისრება აგენტს. პრინციპალი გარანტიას იძლევა, რომ არ გამოიყენებს აგენტის მიერ
          გაკეთებულ ფოტოსურათებს.
        </td>
      </tr>
      <tr>
        <td>The Principal guarantees not to use the branded products provided by the Agent for guests who are not Roomp’s
          Clients in the Roomp’s rooms and other Hotel rooms. In the event of non-compliance with this Clause, the Principal
          agrees to pay the Agent a penalty in the amount of three Roomp’s Rates for each guest.
        </td>
        <td>6.3.</td>
        <td>პრინციპალი გარანტიას იძლევა, რომ უარს იტყვის აგენტის მიერ წარმოდგენილი ბრენდირებული პროდუქციის გამოყენებაზე
          სტუმრებისთვის, რომლებიც არ არიან რუუმპის კლიენტები, რუუმპის ნომრებსა და სასტუმროს სხვა ნომრებში. აღნიშნული პუნქტის
          პრინციპალის მიერ დაუცველობის შემთხვევაში პრინციპალი გარანტიას იძლევა გადაუხადოს აგენტს საჯარიმო პირგასამტეხლო
          რუუმპის სამი ტარიფის ოდენობით თითოეულ სტუმარზე.
        </td>
      </tr>
      <tr>
        <td><b>CONFIDENTIALITY OF INFORMATION</b></td>
        <td>7.</td>
        <td><b>ინფორმაციის კონფიდენციალურობა</b></td>
      </tr>
      <tr>
        <td>The Parties are obliged to ensure the confidentiality of any information relating to this Agreement, its terms
          and conditions, progress and results, and not disclose such information without the consent of the other Party.
          All documentation related to this Agreement is also confidential.
        </td>
        <td>7.1.</td>
        <td>მხარეები მოვალენი არიან უზრუნველყონ ნებისმიერი იმ მონაცემის კონფიდენციალურობა, რომელიც განეკუთვნება მოცემულ
          ხელშეკრულებას, მის პირობებს, შესრულების მიმდინარეობას და მიღებულ შედეგებს და არ გაამჟღავნონ მითითებული მონაცემები
          მეორე მხარის თანხმობის გარეშე. კონფიდენციალურს წარმოადგენს ასევე მთელი დოკუმენტაცია, რომელიც განეკუთვნება მოცემულ
          ხელშეკრულებას.
        </td>
      </tr>
      <tr>
        <td>The Parties are obliged to respect the confidentiality of the terms and conditions of this Agreement by their
          staff, as well as persons who are not employees of the Parties, but who are involved in the execution of this
          Agreement on the basis of agreements and contracts of a civil law nature.
        </td>
        <td>7.2.</td>
        <td>მხარეები მოვალენი არიან დაიცვან მოცემული ხელშეკრულების პირობების კონფიდენციალურობა თავიანთი მუშაკების, აგრეთვე
          იმ პირთა მხრიდან, რომლებიც არ არიან მხარეთა მუშაკები, მაგრამ მოწვეულნი არიან მოცემული ხელშეკრულების შესრულებისთვის
          ხელშეკრულებებისა და სამოქალაქო-სამართლებრივი ხასიათის შეთანხმებების საფუძველზე.
        </td>
      </tr>
      <tr>
        <td>If pursuant to this Agreement the Parties receive or pass on to each other or to third parties personal data of
          their employees, clients or other persons, each of the Parties is obliged to ensure the confidentiality of
          personal data. The party transmitting the personal data must obtain the consent of the subjects of personal data
          for the transfer of their personal data to the other Party and the processing of their personal data by the other
          Party. The Parties hereby instruct each other to process personal data of persons who are direct beneficiaries of
          the services of the Parties for the purpose of performing this Agreement as well as agreements with these persons.
          The list of actions with personal data that are carried out by the Parties includes: collection, recording,
          systematization, storage, clarification, use, transfer, removal, destruction. The requirements for the protection
          of personal data must comply with the applicable legislation of Georgia.
        </td>
        <td>7.3.</td>
        <td>იმ შემთხვევაში, თუ წინამდებარე ხელშეკრულების შესრულებიდან გამომდინარე მხარეები იღებენ ან ერთმანეთს ან მესამე
          პირებს გადასცემენ თავიანთი მუშაკების, კლიენტების ან სხვა პირების პერსონალურ მონაცემებს, თითოეული მხარე მოვალე არის
          უზრუნველყოს პერსონალური მონაცემების კონფიდენციალურობა. მხარე, რომელიც გადასცემს პერსონალურ მონაცემებს, მოვალე არის
          მიიღოს პერსონალური მონაცემების სუბიექტების თანხმობა მათი პერსონალური მონაცემების სხვა მხარისთვის გადაცემასა და
          მათი პერსონალური მონაცემების სხვა მხარის მიერ დამუშავებაზე. წინამდებარეთი მხარეები ერთმანეთს აძლევენ დავალებას იმ
          პირთა პერსონალური მონაცემების დამუშავებაზე, რომლებიც არიან მხარეთა მომსახურების უშუალო მიმღებები წინამდებარე
          ხელშეკრულების, ამ პირებთან ხელშეკრულებების მიზნებიდან გამომდინარე. პერსონალური მონაცემებით იმ მოქმედებათა
          ჩამონათვალი, რომლებსაც ახორციელებენ მხარეები: შეკრება, ჩაწერა, სისტემატიზაცია, შენახვა, დაზუსტება, გამოყენება,
          გადაცემა, მოცილება, განადგურება. მოთხოვნები პერსონალური მონაცემების დაცვასთან მიმართებაში უნდა შეესაბამებოდეს
          რუსეთის ფედერაციის მოქმედ კანონმდებლობას.
        </td>
      </tr>
      <tr>
        <td><b>EARLY TERMINATION OF THIS AGREEMENT</b></td>
        <td>8.</td>
        <td><b>მოცემული ხელშეკრულების ვადამდელი შეწყვეტა</b></td>
      </tr>
      <tr>
        <td>The Principal can terminate this Agreement unilaterally, having informed the Agent about this one month in
          advance by email to partners@roomp.co .
        </td>
        <td>8.1.</td>
        <td>პრინციპალს შეუძლია ცალმხრივი წესით შეწყვიტოს წინამდებარე ხელშეკრულება და ამის თაობაზე წინასწარ ერთი თვით ადრე
          აცნობოს აგენტს ელექტრონული ფოსტით partners@roomp.co.
        </td>
      </tr>
      <tr>
        <td>The Agent can terminate this Agreement unilaterally under a written notification given to the Principal by
          email.
        </td>
        <td>8.2.</td>
        <td>აგენტს შეუძლია ცალმხრივი წესით შეწყვიტოს წინამდებარე ხელშეკრულება და ამის თაობაზე წერილობით აცნობოს პრინციპალს
          ელექტრონული ფოსტით.
        </td>
      </tr>
      <tr>
        <td>In case of termination of the Agreement, the Principal undertakes to return all separable improvements and all
          branded products, which the Agent has previously provided, to the address of the nearest office of the Agent.
        </td>
        <td>8.3.</td>
        <td>ხელშეკრულების გაწყვეტის შემთხვევაში პრინციპალი ვალდებულებას იღებს აგენტის უახლოესი ოფისის მისამართზე დააბრუნოს
          ყველა განცალკევადი გაუმჯობესებები და აგენტის მიერ ადრე წარმოდგენილი მთელი ბრენდირებული პროდუქცია.
        </td>
      </tr>
      <tr>
        <td>The Party has the right to propose amendments to this Agreement by notification of the other Party through the
          extranet system (http://extranet.roomp.co ). The Party receiving the notification is obliged within 10 (ten)
          business days to send through the Extranet system a notification to the other Party about the agreement or
          disagreement with the changes. To avoid a doubt, in the event that the answer was not sent within the specified
          period, the consent to amend the Agreement is deemed to have been received.
        </td>
        <td>8.4.</td>
        <td>მხარეს უფლება აქვს შესთავაზოს წინამდებარე ხელშეკრულებაში ცვლილებების შეტანა მეორე მხარისთვის შეტყობინების
          გაგზავნით სისტემა „ექსტრანეტის“(http://extranet.roomp.co) მეშვეობით. შეტყობინების მიმღები მხარე მოვალე არის 10
          (ათი) სამუშაო დღის განმავლობაში გაუგზავნოს სისტემა „ექსტრანეტის“ მეშვეობით მესამე მხარეს შეტყობინება ცვლილებები
          შეტანაზე თანხმობის ან უარის თქმით. ყოველგვარი ეჭვების აცილების მიზნით, იმ შემთხვევაში, თუ პასუხი არ იქნა
          გაგზავნილი მითითებული ვადის განმავლობაში, თანხმობა ხელშეკრულების ცვლილებაზე ჩაითვლება მიღებულად.
        </td>
      </tr>
      <tr>
        <td>Overdue payments and any other payment obligations between the Parties are payable within 5 working days after
          the termination of this Agreement.
        </td>
        <td>8.5.</td>
        <td>ვადაგადაცილებული გადახდები და ნებისმიერი სხვა საგადახდო ვალდებულებები მხარეებს შორის ექვემდებარება გადახდას
          წინამდებარე ხელშეკრულების მოქმედების ვადის შეწყვეტიდან 5 სამუშაო დღის განმავლობაში.
        </td>
      </tr>
      <tr>
        <td><b>TERM OF THE AGREEMENT</b></td>
        <td>9.</td>
        <td><b>ხელშეკრულების მოქმედების ვადა</b></td>
      </tr>
      <tr>
        <td>This Agreement shall enter into force upon its signing by the Parties and is valid until terminated by either
          party, in accordance with the provisions of this Agreement.
        </td>
        <td>9.1.</td>
        <td>წინამდებარე ხელშეკრულება ძალაში შედის მხარეთა მიერ მისი ხელმოწერის მომენტიდან და მოქმედებს ნებისმიერი მხარის
          მიერ მისი შეწყვეტის მომენტამდე, წინამდებარე ხელშეკრულების დებულებების შესაბამისად.
        </td>
      </tr>
      <tr>
        <td>All disputes arising between the Parties in the course of the performance of this Agreement shall be resolved
          through negotiations. The deadline for responding to the Party’s claim is 10 calendar days from the date of
          receipt of the claim.
        </td>
        <td>9.2.</td>
        <td>ყველა დავა, რომელიც მხარეებს შორის წარმოიქმნება მოცემული ხელშეკრულების შესრულებისას, წყდება მოლაპარაკებების
          გზით. მხარის პრეტენზიაზე პასუხის გაცემის ვადა - პრეტენზიის მიღებიდან 10 კალენდარული დღის განმავლობაში.
        </td>
      </tr>
      <tr>
        <td><b>REGISTRATION ON THE AGENT’S PORTAL, NOTIFICATION OF THE PARTIES AND USE OF SIMPLE ELECTRONIC SIGNATURES</b></td>
        <td>10.</td>
        <td><b>რეგისტრაცია აგენტის პორტალზე, მხარეთა შეტყობინებები და მარტივი ელექტრონული ხელმოწერის გამოყენება.</b></td>
      </tr>
      <tr>
        <td>The Parties have agreed to use a simple electronic signature when concluding, amending and terminating the
          Agreement, additional agreements and annexes hereto, signing of primary and other documents under the Agreement,
          including the Agent’s Report, the Service Providing Report, the Offset Certificate and other documents.
        </td>
        <td>10.1.</td>
        <td>მხარეები შეთანხმდნენ, რომ ხელშეკრულების, დამატებითი შეთანხმებების და ხელშეკრულების დანართების დადების, ცვლილების
          და გაუქმების, ხელშეკრულების მიხედვით პირველადი და სხვა დოკუმენტების, მათ რიცხვში აგენტის ანგარიშის, მომსახურების
          გაწევის შესახებ აქტის, ურთიერთჩათვლის აქტის და სხვა დოკუმენტების ხელმოწერისას გამოიყენონ მარტივი ელექტრონული
          ხელმოწერა.
        </td>
      </tr>
      <tr>
        <td>In accordance with the Preamble to this Agreement, the Principal accepts all the terms and conditions of this
          Agreement, agrees that the Agreement is signed by a simple electronic signature; thereby the Principal accepts the
          Agent’s offers. The Parties acknowledge that the Agreement is not signed by handwritten signatures of the
          representatives of the Parties.
        </td>
        <td>10.2.</td>
        <td>წინამდებარე ხელშეკრულების პრეამბულის შესაბამისად პრინციპალი იღებს წინამდებარე დებულების ყველა პირობას, ეთანხმება
          იმას, რომ ხელშეკრულებას ხელი ეწერება მარტივი ელექტრონული ფოსტით, ამდენად პრინციპალი ახდენს აგენტის ოფერტის
          აქცეპტს. მხარეები აღიარებენ, რომ ხელშეკრულების ხელმოწერა არ ხდება მხარეთა წარმომადგენლების საკუთარი ხელმოწერებით.
        </td>
      </tr>
      <tr>
        <td>The Parties have agreed to use a simple electronic signature by generating the Principal’s account on the
          Agent’s portal, and by using the email addresses of the Parties specified in Clause 12 of the Agreement.
        </td>
        <td>10.3.</td>
        <td>მხარეები შეთანხმდნენ მარტივი ელექტრონული ხელმოწერა გამოიყენონ აგენტის პორტალზე პრინციპალის პირადი კაბინეტის
          ფორმირების გზით, აგრეთვე ხელშეკრულების პ.12-ში მითითებული მხარეთა ელექტრონული ფოსტის მისამართების გამოყენებით.
        </td>
      </tr>
      <tr>
        <td>To conclude this Agreement, the Principal must register on the Agent’s portal http://extranet.roomp.co by
          creating an account (generating a simple electronic signature). For this purpose, when concluding this Agreement,
          the Principal fills out the registration application form (with the help of the Agent). After that, a unique link
          is sent to the email address specified by the Principal to confirm the registration (confirmation of the
          authenticity of the Principal’s signature - the identification of the person signing the electronic document, in
          accordance with subparagraphs “b” and “c” of paragraph 1 of Article 6 of the Law of Georgia N639-IIS“ “On
          Electronic Document and Reliable Electronic Service”.). To complete the creation of the account, the Principal
          creates a login and password or a password (in case the login is an email address), which will be used to enter
          the account on the Agent’s portal and served as a simple electronic signature. At the first attempt to enter the
          Account, the Principal should familiarize himself with this Agreement and accept its terms and conditions (to
          accept the offer) by clicking the “Accept” button by the Principal, without which it is impossible to further use
          the Account. All actions of the Principal through the Account on the Agent’s portal are recognized as performed by
          the Principal, and clicking the “Accept” button or other buttons confirming any actions of the Principal is
          recognized as commitment of these actions and signing of documents by the Principal by a simple electronic
          signature in accordance with the Law of Georgia N639-IIS “On Electronic Document and Reliable Electronic Service”.
          Placement of the document by the Agent in the Principal’s Account is recognized through signing by the Agent of
          this document with a simple electronic signature. The Parties recognize the validity of electronic documents,
          emails sent using the Principal’s Account on the Agent's portal, and recognize them as equivalent to documents on
          paper carriers signed with a handwritten signature.
        </td>
        <td>10.4.</td>
        <td>წინამდებარე ხელშეკრულების დადებისთვის პრინციპალი უნდა დარეგისტრირდეს აგენტის პორტალზე http://extranet.roomp.co
          პირადი კაბინეტის (მარტივი ელექტრონული ხელმოწერის ფორმირების) შექმნის გზით. ამისათვის წინამდებარე ხელშეკრულების
          დადების დროს პრინციპალი ავსებს რეგისტრაციის ანკეტას (აგენტის დახმარებით). რის შემდგომაც პრინციპალის მიერ მითითებულ
          ელექტრონული ფოსტის მისამართზე რეგისტრაციის დადასტურებისთვის იგზავნება უნიკალური დამოწმება (პრინციპალის ხელმოწერის
          ნამდვილობის დადასტურება - ელექტრონული დოკუმენტის ხელმომწერი პირის განსაზღვრა „ელექტრონული დოკუმენტისა და
          ელექტრონული სანდო მომსახურების შესახებ“ საქართველოს კანონის №639-IIს მე-6 მუხლის 1-ლი პუნქტის „ბ“ და „გ“ ნაწილების
          შესაბამისად). პირადი კაბინეტის შექმნის დასრულებისთვის პრინციპალი ქმნის ლოგინსა და პაროლს ან პაროლს (იმ
          შემთხვევაში, თუ ლოგინს წარმოადგენს ელექტრონული ფოსტის მისამართი), რომლებიც შემდგომ გამოიყენება აგენტის პორტალზე
          პირად კაბინეტში შესვლისთვის და გასწევენ მარტივი ელექტრონული ხელმოწერის სამსახურს. პირად კაბინეტში შესვლის პირველი
          მცდელობისას პრინციპალი უნდა გაეცნოს წინამდებარე ხელშეკრულებას და მიიღოს მისი პირობები (განახორციელოს ოფერტის
          აქცეპტი) პრინციპალის მიერ ღილაკზე „აქცეპტი“ დაჭერით, რომლის გარეშეც შეუძლებელი არის პირადი კაბინეტის შემდგომი
          გამოყენება. პრინციპალის ყველა მოქმედება აგენტის პორტალზე პირადი კაბინეტის მეშვეობით აღიარებული იქნება პრინციპალის
          მიერ განხორციელებულად, ხოლო პრინციპალის მიერ რაიმე მოქმედების დამადასტურებელი დაჭერა ღილაკზე „აქცეპტი“ და სხვა
          ღილაკებზე, აღიარებული იქნება პრინციპალის მიერ ამ მოქმედებების განხორციელებად და მის მიერ დოკუმენტების ხელმოწერად
          მარტივი ელექტრონული ხელმოწერით „ელექტრონული დოკუმენტისა და ელექტრონული სანდო მომსახურების შესახებ“ საქართველოს
          კანონის №639-IIს შესაბამისად. აგენტის მიერ დოკუმენტის განთავსება პრინციპალის პირად კაბინეტში ცნობილი იქნება
          აგენტის მიერ ამ დოკუმენტის ხელმოწერად მარტივი ელექტრონული ხელმოწერით. მხარეები აღიარებენ აგენტის პორტალზე პირადი
          კაბინეტის გამოყენებით გაგზავნილი ელექტრონული დოკუმენტების, ელექტრონული წერილების იურიდიულ ძალას და აღიარებენ მათ
          ქაღალდის გადამზიდიანი იმ დოკუმენტების თანაბარზომიერად, რომლებიც დამოწმებული არის პირადი ხელმოწერით.
        </td>
      </tr>
      <tr>
        <td>The Principal has no right to transfer his login and password to third parties and is fully responsible for
          maintaining their confidentiality. In order to protect the information stored in the account in order to ensure
          the confidentiality of the login and password, and in order to identify the person signing electronic documents
          using a simple electronic signature (of the Principal), the Principal provides information about the contact
          telephone number to which the Agent in case of necessity and at the conclusion of the Agreement, shall send a
          confirmation code of the action to be entered in the “Confirmation code” window. In the event of a breach of
          confidentiality, the Principal must immediately notify the Agent about it by using a contact phone number or
          email. In this case, the password shall be changes by clicking the “Recover password” button, after which the
          Agent sends a message with a confirmation code to the contact phone number or email. After entering the
          confirmation code, the Principal can change the password.
        </td>
        <td>10.5.</td>
        <td>პრინციპალს არ აქვს უფლება მესამე პირებს გადასცეს საკუთარი ლოგინი და პაროლი და სრულად არის პასუხისმგებელი მათი
          კონფიდენციალურობის დაცვაზე. პირად კაბინეტში არსებული ინფორმაციის დაცვის გარანტიის სახით, ლოგინისა და პაროლის
          კონფიდენციალურობის დაცვის მიზნით, და ელექტრონული დოკუმენტის მარტივი ელექტრონული ხელმოწერით ხელმომწერი პირის
          (პრინციპალის) განსაზღვრისთვის ხელშეკრულების დადების დროს პრინციპალი წარადგენს ინფორმაციას საკონტაქტო სატელეფონო
          ნომრის შესახებ, რომელზეც აუცილებლობის წარმოქმნის შემთხვევაში და ხელშეკრულების დადების დროს აგენტი აგზავნის იმ
          ქმედებების განხორციელების დადასტურების კოდს, რომელიც უნდა იქნეს შეყვანილი ფანჯარაში „დადასტურების კოდი“.
          კონფიდენციალურობის დარღვევის შემთხვევაში პრინციპალი მოვალე არის დაუყონებლივ აცნობოს ამის შესახებ აგენტს საკონტაქტო
          სატელეფონო ნომრის ან ელექტრონული ფოსტის გამოყენებით. მოცემულ შემთხვევაში პაროლი ექვემდებარება შეცვლას ღილაკზე
          „აღდგენილ იქნეს პაროლი“ დაჭერით, რომლის შემდეგაც საკონტაქტო სატელეფონო ნომერზე ან ელექტრონულ ფოსტაზე აგენტი
          აგზავნის შეტყობინებას დადასტურების კოდით. დადასტურების კოდის შეყვანის შემდეგ პრინციპალს შეუძლია პაროლის შეცვლა.
        </td>
      </tr>
      <tr>
        <td>The Parties also confirm that the documents sent from the email addresses of the Parties specified in Clause 12
          of this Agreement are deemed to be signed by a simple electronic signature - an electronic address. The Parties
          are obliged to notify each other about changes in their email addresses within 1 working day from the date of such
          change. Responsibility for receiving emails and confirmation of their receipt lies with the receiving Party. The
          Party sending the email is not responsible for the delay in its delivery, if such delay was the result of a
          malfunction of the communication systems, the action/inaction of the providers or other force majeure
          circumstances. The Parties undertake to keep confidential the password for their email.
        </td>
        <td>10.6.</td>
        <td>მხარეები ასევე ადასტურებენ, რომ მხარეთა ელექტრონული ფოსტის იმ მისამართებიდან გაგზავნილი დოკუმენტები, რომლებიც
          მითითებული არის წინამდებარე ხელშეკრულების მე-12 პუნქტში, მიიჩნევა ხელმოწერილად მარტივი ელექტრონული ხელმოწერით -
          ელექტრონული მისამართით. მხარეები მოვალენი არიან ინფორმაცია მიაწოდონ ერთმანეთს მათი ელექტრონული მისამართების
          ცვლილებების შესახებ ასეთი შეცვლის მომენტიდან 1 სამუშაო დღის განმავლობაში. პასუხისმგებლობა ელექტრონული წერილების
          მიღებაზე და მათი მიღების დადასტურებაზე ეკისრება მიმღებ მხარეს. მხარე, რომელმაც გააგზავნა ელექტრონული წერილი, არ
          არის პასუხიმგებელი მათ დაგვიანებით მიტანაზე, თუ ასეთი დაგვიანება გახდა კავშირის სისტემის გაუმართაობის,
          პროვაიდერების მოქმედების/უმოქმედობის, სხვა ფორს-მაჟორული გარემოებების დადგომის შედეგი. მხარეები ვალდებულებას
          იღებენ დაიცვან პაროლის კონფიდენციალურობა თავისი ელექტრონული ფოსტისთვის.
        </td>
      </tr>
      <tr>
        <td>The Parties recognize the validity of electronic documents; emails sent to/from the email addresses of the
          Parties and recognize them as equivalent to documents on paper carriers signed by a handwritten signature.
        </td>
        <td>10.7.</td>
        <td>მხარეები აღიარებენ მხარეთა ელექტრონულ მისამართებ-ზე(-დან) გაგზავნილი ელექტრონული დოკუმენტების, ელექტრონული
          წერილების იურიდიულ ძალას და აღიარებენ მათ ქაღალდის გადამზიდიანი იმ დოკუმენტების თანაბარზომიერად, რომლებიც
          დამოწმებული არის პირადი ხელმოწერით.
        </td>
      </tr>
      <tr>
        <td>The Parties confirm that the key of a simple electronic signature is applied in accordance with the rules
          established by the information system operator, with the use of which the electronic document is created and/or
          sent. The created and/or sent electronic documents should contain information indicating the person on whose
          behalf an electronic document was created and/or sent.
        </td>
        <td>10.8.</td>
        <td>მხარეები ადასტურებენ, რომ მარტივი ელექტრონული ხელმოწერის გასაღები გამოიყენება ინფორმაციული სისტემის ოპერატორის
          მიერ დადგენილი წესების შესაბამისად, რომლის გამოყენებით ხდება ელექტრონული დოკუმენტის შექმნა და/ან გაგზავნა.
          შექმნილი და (ან) გაგზავნილი ელექტრონული დოკუმენტები უნდა შეიცავდნენ ინფორმაციას, რომელიც მიუთითებს იმ პირზე,
          რომლის სახელითაც იქნა შექმნილი და (ან) გაგზავნილი ელექტრონული დოკუმენტი.
        </td>
      </tr>
      <tr>
        <td><b>FINAL PROVISIONS</b></td>
        <td>11.</td>
        <td><b>დასკვნითი დებულებები</b></td>
      </tr>
      <tr>
        <td>The party is relieved of responsibility if the failure to perform or improper performance of obligations was due
          to force majeure circumstances such as: natural disasters, extreme weather conditions, terrorism, government
          actions, activities limiting party’s activity, fires, war, blockade, insurrection, epidemic, nuclear conflicts and
          other circumstances beyond the reasonable control of the Party. Unfavorable economic conditions or general
          financial and/or operational restrictions are not considered to be force majeure.
        </td>
        <td>11.1.</td>
        <td>მხარე თავისუფლდება პასუხისმგებლობისგან, თუ ვალდებულებათა შეუსრულებლობა ან არასათანადო შესრულება დაკავშირებული
          არის გადაულახავი ძალის ისეთ გარემოებებთან, როგორიც არის: სტიქიური უბედურებები, ამინდის ექსტრემალური პირობები,
          ტერორიზმი, მთავრობის ისეთი მოქმედებები, რომლებიც ზღუდავენ მხარის მოქმედებას, ხანძრები, ომები, ბლოკადა, აჯანყებები,
          ეპიდემიები, ბირთვული კონფლიქტები და სხვა გარემოებები, რომლებიც სცდება მხარის გონივრული კონტროლის ფარგლებს.
          არასახარბიელო ეკონომიკური პირობები ან საერთო ფინანსური და/ან ოპერაციული შეზღუდვები არ მიიჩნევა ფორს-მაჟორულ
          გარემოებებად.
        </td>
      </tr>
      <tr>
        <td>For failure to perform or improper performance of obligations under this Agreement, the Parties shall be liable
          under the laws of Georgia.
        </td>
        <td>11.2.</td>
        <td>წინამდებარე ხელშეკრულებით გათვალისწინებული ვალდებულებების შეუსრულებლობის ან არასათანადო შესრულებისთვის მხარეებს
          ეკისრებათ პასუხისმგებლობა, რომელიც გათვალისწინებული არის რუსეთის ფედერაციის კანონმდებლობით.
        </td>
      </tr>
      <tr>
        <td>In the event that the retail price at which the Principal sells the Hotel rooms, similar to the Roomp’s Rooms by
          class, falls below the level indicated in Clause 1.4. of this Agreement the Agent has the right to adjust the
          Roomp’s Rate in accordance with the changes after receiving appropriate approval from the Hotel.
        </td>
        <td>11.3.</td>
        <td>იმ შემთხვევაში, თუ საცალო ღირებულება, რომლის მიხედვითაც პრინციპალი ახორციელებს კლასის მიხედვით სასტუმროს რუუმპის
          ნომრების ანალოგიური ნომრების რეალიზებას, ეცემა იმ დონეზე უფრო ქვევით, რომელიც მითითებული არის წინამდებარე
          ხელშეკრულების 1.4. პუნქტში, აგენტს უფლება აქვს სასტუმროს მხრიდან შესაბამისი მოწონების მიღების შემდეგ განახორციელოს
          რუუმპის ტარიფის კორექტირება ცვლილებების შესაბამისად.
        </td>
      </tr>
      <tr>
        <td>In order to maintain the no conflict during the term of this Agreement and for 6 months after the termination of
          this Agreement, the Principal undertakes not to enter into partnerships with any other partner networks of budget
          hotels without the prior written consent of the Agent.
        </td>
        <td>11.4.</td>
        <td>იმისთვის, რომ შენარჩუნებულ იქნეს ინტერესთა კონფლიქტის არარსებობა წინამდებარე ხელშეკრულების მოქმედების ვადის
          განმავლობაში და მისი მოქმედების შეწყვეტიდან 6 თვის განმავლობაში, პრინციპალი ვალდებულებას იღებს, რომ აგენტის
          წინასწარი წერილობითი თანხმობის გარეშე არ შევიდეს საპარტნიორო ურთიერთობებში საბიუჯეტო ოტელების ნებისმიერ სხვა
          საპარტნიორო ქსელთან.
        </td>
      </tr>
      <tr>
        <td>Both Parties guarantee full compliance with the applicable legislation and regulations of Georgia in the
          performance of their obligations under this Agreement.
        </td>
        <td>11.5.</td>
        <td>ორივე მხარე გარანტიას იძლევა, რომ წინამდებარე ხელშეკრულების მიხედვით ვალდებულებების შესრულების დროს მათ მიერ
          სრულად იქნება დაცული რუსეთის ფედერაციის მოქმედი კანონმდებლობა და ნორმატიული აქტები.
        </td>
      </tr>
      <tr>
        <td>The brand “Roomp” is fully owned and controlled by Roomp Georgia LLC. Except as with the prior written consent
          of the Agent, nothing in this Agreement grants the Principal the title or other rights and licenses for the use or
          other operations with trademarks, brand names, service names, trade and other corporate documents of Roomp Georgia
          LLC.
        </td>
        <td>11.6.</td>
        <td>ბრენდი რუუმპი სრულად ეკუთვნის და კონტროლდება შპს „რუუმპ ჯორჯიას“ მიერ. აგენტის წინასწარი წერილობითი თანხმობის
          გარეშე წინამდებარე ხელშეკრულებაში არაფერს არ გადასცემს პრინციპალს საკუთრებაში ან სხვა უფლებებს და ლიცენზიებს
          გამოყენებაზე და სხვა ოპერაციებს სასაქონლო ნიშნებით, სავაჭრო მარკებით, მომსახურების სახელწოდებებით, სავაჭრო და შპს
          „რუუმპ ჯორჯიას“ სხვა კორპორატიული დოკუმენტებით.
        </td>
      </tr>
      <tr>
        <td><b>BANK DETAILS AND CONTACT DETAILS OF THE PARTIES</b></td>
        <td>12.</td>
        <td><b>მხარეთა რეკვიზიტები და საკონტაქტო მონაცემები</b></td>
      </tr>
      <tr>
        <td><b>Agent:</b></td>
        <td></td>
        <td><b>აგენტი:</b></td>
      </tr>
      <tr>
        <td>Roomp Georgia LLC</td>
        <td></td>
        <td>შპს რუუმპ ჯორჯია</td>
      </tr>
      <tr>
        <td>Id Number: 402080289</td>
        <td></td>
        <td>საიდენტიფიკაციო ნომერი: 402080289</td>
      </tr>
      <tr>
        <td>BENEFICIARY’S BANK: JSC TBC BANK</td>
        <td></td>
        <td>მიმღების ბანკი: ს.ს. „თიბისი ბანკი“</td>
      </tr>
      <tr>
        <td>BANK CODE: TBCBGE22</td>
        <td></td>
        <td>ბანკის კოდი: TBCBGE22</td>
      </tr>
      <tr>
        <td>BEN’S IBAN: GE26TB7572536080100004</td>
        <td></td>
        <td>მიმღების ანგარიში: GE26TB7572536080100004</td>
      </tr>
      <tr>
        <td>Legal Address: Georgia, Tbilisi, Didube District, Uznazde Str., N111, Flat N11, building N2</td>
        <td></td>
        <td>იურიდიული მისამართი: საქართველო, თბილისი, დიდუბის რაიონი, უზნაძის ქ. N 111, ბინა N11, შენობა N2</td>
      </tr>
      <tr>
        <td>Email: partners@roomp.co</td>
        <td></td>
        <td>ელ. ფოსტა: partners@roomp.co</td>
      </tr>
      <tr>
        <td>Telephone: +7 (812) 242-84-58</td>
        <td></td>
        <td>ტელეფონი: +7 (812) 242-84-58</td>
      </tr>

      <tr>
        <td><b>Principal:</b></td>
        <td></td>
        <td><b>პრინციპალი:</b></td>
      </tr>
      <tr>
        <td>{{ $organization->name }}</td>
        <td></td>
        <td>{{ $organization->name }}</td>
      </tr>
      <tr>
        <td>Id Number: {{ $organization->id_number }}</td>
        <td></td>
        <td>საიდენტიფიკაციო ნომერი: {{ $organization->id_number }}</td>
      </tr>
      <tr>
        <td>BENEFICIARY’S BANK: {{ $organization->bank }}</td>
        <td></td>
        <td>მიმღების ბანკი: {{ $organization->bank }}</td>
      </tr>
      <tr>
        <td>BANK CODE: {{ $organization->bank_code }}</td>
        <td></td>
        <td>ბანკის კოდი: {{ $organization->bank_code }}</td>
      </tr>
      <tr>
        <td>BEN’S IBAN: {{ $organization->IBAN }}</td>
        <td></td>
        <td>მიმღების ანგარიში: {{ $organization->IBAN }}</td>
      </tr>
      <tr>
        <td>Legal Address: {{ $organization->legal_address }}</td>
        <td></td>
        <td>იურიდიული მისამართი: {{ $organization->legal_address }}</td>
      </tr>
      <tr>
        <td>Email: {{ $organization->email }}</td>
        <td></td>
        <td>ელ. ფოსტა: {{ $organization->email }}</td>
      </tr>
      <tr>
        <td>Telephone: {{ $organization->phone }}</td>
        <td></td>
        <td>ტელეფონი: {{ $organization->phone }}</td>
      </tr>
    </table>

  </div>
@endsection
