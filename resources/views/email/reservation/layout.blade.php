@component('mail::layout')
  {{-- Header --}}
  @slot('header')
    @component('mail::header', ['url' => config('app.url')])
      {{ config('app.name') }}
    @endcomponent
  @endslot

  {{-- Body --}}
  {{ $slot }}

  {{-- Footer --}}
  @slot('footer')
    @component('mail::footer', ['locale' => $locale])
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    @endcomponent
  @endslot
@endcomponent
