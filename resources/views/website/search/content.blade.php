<root
  :params='{!! $params !!}'
  :request='{!! $request !!}'
  :user='{{ $user }}'
  :locale='"{{$locale}}"'
  :currency="'{{ $currency }}'"
  :settings="{{ $settings }}"
  :max-price="{{ ['RUB' => 10000, 'USD' => 200, 'GEL' => 300][$currency] }}"
>
</root>