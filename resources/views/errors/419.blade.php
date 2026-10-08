@extends('errors.layout')

@section('title', 'Sesi Berakhir')
@section('code', '419')
@section('heading', 'Sesi kamu sudah berakhir')
@section('message', 'Halaman ini sudah terlalu lama terbuka. Muat ulang halaman lalu coba lagi, ya.')
@section('icon-bg', 'bg-amber-50')
@section('icon-text', 'text-amber-600')

@section('icon')
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
    </svg>
@endsection

@section('actions')
    <a href="javascript:location.reload()" class="btn-primary">Muat ulang halaman</a>
    <a href="{{ url('/dashboard') }}" class="btn-outline">Ke Dashboard</a>
@endsection
