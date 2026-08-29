<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ front_locale_direction() }}">

<head>
  <meta charset="utf-8">
  <title>@yield('title', system_setting_locale('meta_title', 'Travalorics DebugBar'))</title>
</head>

<body>
  @dump($data)
</body>

</html>

