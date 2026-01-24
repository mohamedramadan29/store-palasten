
@php

$setting = \App\Models\admin\PublicSetting::first();


@endphp


<!DOCTYPE html>
<html dir="rtl" xmlns="http://www.w3.org/1999/xhtml" xml:lang="ar-EG" lang="ar-EG">
<head>
    <meta charset="utf-8">
    <title> @yield('title') - {{$setting['website_name']}} </title>
    <meta name="description" content="{{$setting['website_description']}}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="keywords" content="{{$setting['website_keywords']}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="{{$setting['website_description']}}">
    <meta property="og:image" content="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}">
    <meta property="og:type" content="website"> <!-- يجب أن يكون النوع "website" أو نوع مناسب آخر -->

    <!-- يمكنك أيضًا إضافة Twitter Cards إذا كنت ترغب في ذلك -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title')" />
    <meta name="twitter:description" content="{{$setting['website_description']}}" />
    <meta name="twitter:image" content="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}" />


    <!-- font -->
    <link rel="stylesheet" href="{{asset('assets/front/fonts/fonts.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/fonts/font-icons.css')}}">
{{--    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-DOXMLfHhQkvFFp+rWTZwVlPVqdIhpDVYT9csOnHSgWQWPX0v5MCGtjCJbY6ERspU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{asset('assets/front/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/animate.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('assets/uploads/PublicSetting/'.$setting['website_logo'])}}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @toastifyCss
    @yield('css')
</head>
<body>
