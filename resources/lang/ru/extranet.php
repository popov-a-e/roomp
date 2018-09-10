<?php

return [
  'back_to_results' => 'Назад к результатам',
  'changes_saved' => 'Ваши изменения успешно сохранены!',
  'header' => [
    'calendar' => 'Календарь',
    'reservations' => 'Бронирования',
    'booking_confirmation' => 'Подтверждение заездов',
    'reconciliation' => 'Сверка',
    'finance' => 'Финансы',
    'help' => 'Помощь',
    'settings' => 'Настройки',
    'property' => 'Объект размещения',
    'organization' => 'Организация',
    'offer' => 'Оферта',
    'log_out' => 'Выход',
    'reminder' => 'Памятка',
    'instruction' => 'Инструкция'
  ],
  'new_hotel' => [
    'name' => 'Отель :name',
    'address' => 'Адрес',
    'room_number' => 'Номерной фонд',
    'reception_phone' => 'Телефон рецепции',
    'reception_email' => 'E-mail рецепции',
    'agreement' => "Добавить отель и принять <a href=':link' target='_blank'>условия соглашения</a>",
    'apply' => 'Подтвердить'
  ],
  'legend' => [
    'bookable' => 'Открыт',
    'has_restrictions' => 'Есть ограничения',
    'no_room' => 'Нет номера/цены',
    'closed' => 'Закрыт'
  ],
  'errors' => [
    'address.required' => 'Вы должны указать адрес',
    'room_number.required' => 'Вы должны ввести номерной фонд',
    'room_number.numeric' => 'Номерной фонд должен быть числом',
    'reception_phone.required' => 'Вы должны указать телефон рецепции',
    'reception_email.required' => 'Вы должны указать e-mail рецепции',
    'reception_email.email' => 'E-mail рецепции указан неверно',
    'acceptance.required' => 'Необходимо принять условия соглашения',
    'acceptance.accepted' => 'Необходимо принять условия соглашения',
  ],
  'mail' => [
    'subject' => [
      'registration' => 'Регистрация в Roomp',
      'registered' => 'Данные для работы с Roomp',
      'noshow_reminder' => 'Проверка заселений Roomp'
    ],
    'from' => [
      'partners@roomp' => 'Roomp'
    ],
  ],
  'footer' => [
    'chat' => 'Чат с менеджером',
    'feedback' => 'Обратная связь',
  ],
  'calendar' => [
    'header' => 'Календарь в отеле',
    'filter' => 'Фильтр по периоду',
    'room' => 'Номер',
    'min_stay' => 'Мин. проживание',
  ],
  'calendar_changes' => [
    'header' => 'Добавить изменения',
    'period' => 'Период',
    'availability' => 'Доступность номера',
    'closed' => 'Закрыт',
    'closed_to_arrival' => 'Закрыт к прибытию',
    'closed_to_departure' => 'Закрыт к отъезду',
    'price_and_restricitons' => 'Цены и ограничения',
    'price' => 'Цены',
    'restrictions' => 'Ограничения',
    'min_stay' => 'Мин. проживание',
    'max_stay' => 'Макс. проживание',
    'min_stay_to_arrival' => 'Мин. проживание к прибытию',
    'cancel' => 'Отменить',
    'save' => 'Сохранить',
  ],
  'finance' => [
    'header' => 'Финансы отеля',
    'help_info_text' => 'Здесь представлены все закрывающие документы по отчетным периодам. В случае, если мы не выставили счет на оплату в отчетный период, мы проводим платеж до 12 числа следующего от отчетного месяца. Просим производить оплату по счету до 12 числа следующего за отчетным месяцем.',
    'payment_status' => 'Статусы по расчетам:',
    'invoice_wait' => 'Ожидается оплата',
    'invoice_done' => 'Счет оплачен',
    'invoice_roomp' => 'Оплата от Roomp',
    'period' => 'Период',
    'sum' => 'Сумма',
    'discount_rate' => 'Ставка дисконта по вашему отелю составляет :discount%',
    'agent_payment' => 'Агентские',
    'net_revenue' => 'Чистый доход',
    'agent_report' => 'Отчет агента',
    'act_services' => 'Акт об оказании услуг',
    'act_mutual_settlement' => 'Акт взаимозачета',
    'invoice' => 'Счет',
    'status' => 'Статус',
    'no_document' => 'Документа не существует',
    'no_bill' => 'Счёт не выставлен',
    'no_finance_records' => 'У вас пока нет финансовых документов',
  ],
  'booking' => [
    'header' => 'Бронирование в отеле',
    'guest' => 'Гость',
    'arrival' => 'Заезд',
    'departure' => 'Выезд',
    'status' => 'Статус',
    'created_at' => 'Дата брони',
    'occupancies' => 'Номера',
    'total' => 'Сумма',
    'rate' => 'Нетто',
    'code' => 'Код брони',
    'no_reservations' => 'Здесь будут все бронирования, которые пришли от Roomp'
  ],
  'date_filter' => [
    'name' => 'Фильтр даты',
    'created_at' => 'Создание бронирования',
    'arrival' => 'Заезд',
    'departure' => 'Выезд',
    'from' => 'Начало периода',
    'till' => 'Конец периода',
  ],
  'booking-confirmation' => [
    'header' => 'Подтверждение заездов',
    'guest' => 'Имя гостя',
    'reservation_from' => 'Дата заезда',
    'nights' => 'Ночей',
    'occupancies' => 'Номера',
    'total' => 'Сумма',
    'arrival' => 'Заезд',
    'show' => 'Заехал',
    'no_show' => 'Не заехал',
    'save' => 'Сохранить',
    'no_records' => 'У вас не осталось ни одного неподтвержденного заезда за последние 48 часов',
  ],
  'login' => [
    'header' => 'Вход в экстранет',
    'email' => 'Email',
    'password' => 'Пароль',
    'enter' => 'Войти',
  ],
  'reconciliation' => [
    'header' => 'Сверка в отеле',
    'period_of_reconciliation' => 'Период сверки',
    'guest' => 'Гость',
    'arrival' => 'Заезд',
    'departure' => 'Выезд',
    'status' => 'Статус',
    'created_at' => 'Дата брони',
    'occupancies' => 'Номера',
    'total' => 'Сумма',
    'rate' => 'Нетто',
    'code' => 'Код брони',
    'total_sum' => 'ИТОГО',
    'no_reconciliations_records' => 'Здесь будет информация по всем исполненным бронированиям',
  ],
  'registration' => [
    'header' => 'Регистрация отельера',
    'email' => 'Email',
    'password' => 'Пароль',
    'repeat_password' => 'Повторите пароль',
    'register' => 'Зарегистрироваться',
    'you_accept_the_offer' => "Я принимаю условия <a href=':href'>электронной оферты</a>"
  ],

  'organization' => [
    'company_requisites' => 'Реквизиты компании',

    'requisites' => [
      'header' => 'Банковские реквизиты',
      'names' => [
        'name' => 'Название организации',
        'form' => 'Юридическая форма',
        'CEO' => 'Генеральный директор',
        'INN' => 'ИНН',
        'email' => 'Email',
        'legal_address' => 'Юридический адрес',
        'fact_address' => 'Фактический адрес',
        'post_address' => 'Почтовый адрес',
        'phone' => 'Телефон',
        'account' => 'Расч. счет',
        'BIC' => 'БИК',
        'bank' => 'Банк',
        'id_number' => 'ИНН',
        'OGRN' => 'ОГРН',
        'OKPO' => 'ОКПО',
        'corr_account' => 'К/с',
        'bank_code' => 'БИК',
        'IBAN' => 'Расч. счет',
        'CEO_short_name' => 'Короткая форма имени г.д.'
      ],
      'placeholders' => [
        'bank' => 'Например: АО "Тинькофф банк"',
        'IBAN' => 'Расчетный счет',
        'account' => 'Расчетный счет',
        'BIC' => 'Банковский идентификационный код',
        'bank_code' => 'Банковский идентификационный код',
      ],
    ],

    'company_requisities_header' => 'Реквизиты компании',
    'contacts_header' => 'Контакты',
    'no_contacts' => 'Вы пока не добавили ни одного контактного лица',
    'account_details_feedback' => 'Если у вас изменились реквизиты компании, напишите нам актуальные данные на partners@roomp.co',
    'confirm_delete' => 'Удалить контакт?',

    'contacts' => [
      'header' => ':name?(Новый контакт)',
      'names' => [
        'new' => 'Новый контакт',
        'name' => 'ФИО',
        'position' => 'Должность',
        'email' => 'Email',
        'phone' => 'Телефон',

      ],
      'placeholders' => [
        'name' => 'Например: Ермольев Игорь Анатольевич',
        'position' => 'Например: Менеджер отдела продаж',
        'email' => 'Например: ermolyev@gmail.com',
        'phone' => 'Например: +79167479041'
      ],

    ],

    'save' => 'Сохранить',
    'cancel' => 'Отменить',


  ]
];