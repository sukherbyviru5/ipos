<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="theme-color" content="#625AFA">
<meta name="google" content="notranslate">
<meta translate="no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="description" content="@yield('meta_description', App\Models\SettingApp::first()->nama_madrasah ?? 'Perpustakaan Digital Parepare - Koleksi buku digital terlengkap untuk pembelajaran dan hiburan.')">
<meta name="keywords" content="@yield('meta_keywords', App\Models\SettingApp::first()->nama_madrasah ?? 'perpustakaan digital, buku digital, parepare, pustaka buku, e-book, perpustakaan online')">
<meta name="author" content="{{ App\Models\SettingApp::first()->nama_instansi ?? 'Perpustakaan Digital Parepare' }}">
<meta name="robots" content="index, follow">
<meta property="og:title" content="@yield('title', App\Models\SettingApp::first()->nama_instansi ?? 'Perpustakaan Digital Parepare')">
<meta property="og:description" content="@yield('meta_description', App\Models\SettingApp::first()->nama_madrasah ?? 'Perpustakaan Digital Parepare - Koleksi buku digital terlengkap untuk pembelajaran dan hiburan.')">
<meta property="og:image" content="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/core-img/logo-small.png') }}">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', App\Models\SettingApp::first()->nama_instansi ?? 'Perpustakaan Digital Parepare')">
<meta name="twitter:description" content="@yield('meta_description', App\Models\SettingApp::first()->nama_madrasah ?? 'Perpustakaan Digital Parepare - Koleksi buku digital terlengkap untuk pembelajaran dan hiburan.')">
<meta name="twitter:image" content="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/core-img/logo-small.png') }}">
<title>@yield('title', App\Models\SettingApp::first()->nama_instansi ?? 'Perpustakaan Digital Parepare')</title>
<link rel="icon" href="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/icons/icon-72x72.png') }}">
<link rel="apple-touch-icon" href="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/icons/icon-96x96.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/icons/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="167x167" href="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/icons/icon-167x167.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset(App\Models\SettingApp::first()->logo ?? 'mobile/dist/img/icons/icon-180x180.png') }}">