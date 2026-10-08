@extends('errors.layout')

@section('title', 'Terjadi Kesalahan')
@section('code', '500')
@section('heading', 'Ups, terjadi kesalahan')
@section('message', 'Ada masalah di sisi server. Coba beberapa saat lagi. Data transaksimu tidak hilang.')
@section('icon-bg', 'bg-rose-50')
@section('icon-text', 'text-rose-500')

@section('icon')
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
@endsection

@section('actions')
    <a href="{{ url('/dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
    <a href="javascript:location.reload()" class="btn-outline">Coba lagi</a>
@endsection
