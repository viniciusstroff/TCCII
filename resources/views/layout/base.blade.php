<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laravel 8 & MySQL CRUD Tutorial</title>
  {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" /> --}}
  <!-- <script src="/js/app.js"></script> -->
  {{-- <script src="{{ mix('js/app.js') }}" type="text/javascript"></script> --}}
  <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>

</head>
<body >
  <div class="container">
    @yield('main')


  </div>

</body>
</html>
{{-- <script src="{{ asset('js/app.js') }}" type="text/js"></script> --}}

<script src="{{mix('js/app.js')}}" type="application/javascript"></script>
