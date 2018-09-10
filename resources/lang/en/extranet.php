<?php

return [
  'back_to_results' => 'Back to results',
  'changes_saved' => 'Your changes were successfully saved!',
  'header' => [
    'calendar' => 'Calendar',
    'reservations' => 'Reservations',
    'booking_confirmation' => 'Confirmation of Arrivals',
    'reconciliation' => 'Reconciliation',
    'finance' => 'Finance',
    'help' => 'Help',
    'settings' => 'Settings',
    'property' => 'Property',
    'organization' => 'Organization',
    'offer' => 'Offer',
    'log_out' => 'Log Out',
    'reminder' => 'Reminder',
    'instruction' => 'Channel manager set up'
  ],
  'new_hotel' => [
    'name' => 'Hotel :name',
    'address' => 'Address',
    'room_number' => 'Number of Rooms',
    'reception_phone' => 'Reception Phone',
    'reception_email' => 'Reception E-mail',
    'agreement' => "Add a hotel and accept the <a href=':link' target='_blank'>terms of offer</a>",
    'apply' => 'Confirm'
  ],
  'legend' => [
    'bookable' => 'Bookable',
    'has_restrictions' => 'Has restrictions',
    'no_room' => 'No room/price',
    'closed' => 'Closed'
  ],
  'errors' => [
    'address.required' => 'Address is required',
    'room_number.required' => 'Please fill in the number of rooms',
    'room_number.numeric' => 'Number of rooms should be numeric',
    'reception_phone.required' => 'Please fill in the reception phone number',
    'reception_email.required' => 'Please fill in the reception email',
    'reception_email.email' => 'Reception E-mail is invalid',
    'acceptance.required' => 'You need to accept the terms of offer',
    'acceptance.accepted' => 'You need to accept the terms of offer',
  ],
  'mail' => [
    'subject' => [
      'registration' => 'Registration at Roomp',
      'registered' => 'Credentials for accessing Roomp extranet',
      'noshow_reminder' => 'Roomp Reservations Check'
    ],
    'from' => [
      'partners@roomp' => 'Roomp - chain of independent hotels'
    ],
  ],
  'footer' => [
    'chat' => 'Chat With a manager',
    'feedback' => 'Feedback',
  ],
  'calendar' => [
    'header' => 'Calendar',
    'filter' => 'Filter by period',
    'room' => 'Room',
    'min_stay' => 'Min. Stay',
  ],
  'calendar_changes' => [
    'header' => 'Add changes',
    'period' => 'Period',
    'availability' => 'Availability',
    'closed' => 'Closed',
    'closed_to_arrival' => 'Closed to arrival',
    'closed_to_departure' => 'Closed to departure',
    'price_and_restricitons' => 'Rates and restrictions',
    'price' => 'Rates',
    'restrictions' => 'Restrictions',
    'min_stay' => 'Min. Stay',
    'max_stay' => 'Max. Stay',
    'min_stay_to_arrival' => 'Min. Stay to arrival',
    'cancel' => 'Cancel',
    'save' => 'Save',
  ],
  'finance' => [
    'header' => 'Finances',
    'help_info_text' => 'All closing documents for reporting periods are presented here. In the event that we did not issue an invoice for payment during the reporting period, we make payment until the 15th day of the next month. Please make payment on the account before the 12th of the month following the reporting month.',
    'payment_status' => 'Payment Status',
    'invoice_wait' => 'Waiting for payment',
    'invoice_done' => 'Paid',
    'invoice_roomp' => 'Payment from Roomp',
    'period' => 'Period',
    'sum' => 'Total Sum',
    'agent_payment' => 'Commission ',
    'net_revenue' => 'Net Revenue',
    'discount_rate' => 'Discount rate for your property is :discount%',
    'agent_report' => 'Report',
    'act_services' => 'Act of acceptance',
    'act_mutual_settlement' => 'Act of reconciliation',
    'invoice' => 'Bill',
    'status' => 'Status',
    'no_document' => 'No document',
    'no_bill' => 'No Bill',
    'no_finance_records' => 'You haven\'t added any financials yet',
  ],
  'booking' => [
    'header' => 'Reservations',
    'guest' => 'Guest',
    'arrival' => 'Arrival',
    'departure' => 'Departure',
    'status' => 'Status',
    'created_at' => 'Created at',
    'occupancies' => 'Rooms',
    'total' => 'Total',
    'rate' => 'Net',
    'code' => 'Code',
    'no_reservations' => 'Here are all the reservations that come from Room'
  ],
  'date_filter' => [
    'name' => 'Date filter',
    'created_at' => 'Date of reservation',
    'arrival' => 'Arrival',
    'departure' => 'Departure',
    'from' => 'From',
    'till' => 'Till',
  ],
  'booking-confirmation' => [
    'header' => 'Confirmation of Arrivals',
    'guest' => 'Guest Name',
    'reservation_from' => 'Date of Arrival',
    'nights' => 'Nights',
    'occupancies' => 'Rooms',
    'total' => 'Total',
    'arrival' => 'Arrival',
    'show' => 'Arrived',
    'no_show' => 'No show',
    'save' => 'Save',
    'no_records' => 'You don\'t have any unconfirmed arrivals in the last 48 hours'
  ],
  'login' => [
    'header' => 'Sign In to Extranet',
    'email' => 'Email',
    'password' => 'Password',
    'enter' => 'Sign In',
  ],
  'reconciliation' => [
    'header' => 'Reconciliation',
    'period_of_reconciliation' => 'Period of reconciliation',
    'guest' => 'Guest',
    'arrival' => 'Arrival',
    'departure' => 'Departure',
    'status' => 'Status',
    'created_at' => 'Created At',
    'occupancies' => 'Rooms',
    'total' => 'Total',
    'rate' => 'Net',
    'code' => 'Code',
    'total_sum' => 'Total Sum',
    'no_reconciliations_records' => 'Here is all the information on executed reservations',
  ],
  'registration' => [
    'header' => 'Partner Sign Up',
    'email' => 'Email',
    'password' => 'Password',
    'repeat_password' => 'Repeat Password',
    'register' => 'Sign Up',
    'you_accept_the_offer' => "I accept the terms of <a href=':href' target='_blank'>the offer</a>"
  ],

  'organization' => [
    'company_requisites' => 'Company requisites',

    'requisites' => [
      'header' => 'Bank requisities',
      'names' => [
        'name' => 'Name of organization',
        'form' => 'Legal form',
        'CEO' => 'Position',
        'INN' => 'ITN',
        'email' => 'Email',
        'legal_address' => 'Legal address',
        'phone' => 'Phone',
        'account' => 'Current account',
        'BIC' => 'BIC',
        'bank' => 'Bank',
        'id_number' => 'TIN',
        'bank_code' => 'Bank code',
        'IBAN' => 'Current account'
      ],
      'placeholders' => [
        'bank' => 'e.g. Deutsche Bank AG',
        'account' => 'International Bank Account Number',
        'bank_code' => 'Bank Identification Code',
        'BIC' => 'Bank Identification Code',
        'IBAN' => 'International Bank Account Number',
      ],
    ],

    'company_requisities_header' => 'Account details',
    'contacts_header' => 'Contacts',
    'no_contacts' => 'You haven\'t added any contact person yet',
    'account_details_feedback' => 'If your company\'s account details change, provide us with relevant data by emailing partners@roomp.co',
    'confirm_delete' => 'Delete contact?',

    'contacts' => [
      'header' => ':name?(New contact)',
      'names' => [
        'new' => 'New contact',
        'name' => 'Full name',
        'position' => 'Position',
        'email' => 'Email',
        'phone' => 'Phone',

      ],
      'placeholders' => [
        'name' => 'e.g. Swan Anna',
        'position' => 'e.g. Sales manager',
        'email' => 'e.g. anna@gmail.com',
        'phone' => 'e.g. +79165432813'
      ],

    ],

    'save' => 'Save',
    'cancel' => 'Cancel',

  ]
];