@extends('layouts.app')

{{-- add bootstrap css file --}}
<link rel="stylesheet" href="/css/bootstrap.min.css">

{{-- add Slick css file --}}
<link rel="stylesheet" href="/css/slick.css">

{{-- add CSS file --}}
<link rel="stylesheet" href="/css/styles.css">

{{-- add bootstrap js file --}}
<link rel="stylesheet" href="/js/bootstrap.min.js">

{{-- add Slick css file --}}
<link rel="stylesheet" href="/js/jquery.slim.min.js">



{{-- section with the main VueJS component --}}
@section('content')
	<app></app>
@endsection