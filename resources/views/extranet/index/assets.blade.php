@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/extranet/index.css') }}"/>
@endsection

@section('jstop')
  <!-- Chatra {literal} -->
  <script>
    (function(d, w, c) {
      w.ChatraID = '4pyANeDZkSACK8sC6';
      var s = d.createElement('script');
      w[c] = w[c] || function() {
          (w[c].q = w[c].q || []).push(arguments);
        };
      s.async = true;
      s.src = 'https://call.chatra.io/chatra.js';
      if (d.head) d.head.appendChild(s);
    })(document, window, 'Chatra');
  </script>
  <!-- /Chatra {/literal} -->
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
@endsection

@section('title', 'Roomp Extranet')

@section('js')
  <script src="{{ cdn('/js/extranet/index.js') }}"></script>
@endsection
