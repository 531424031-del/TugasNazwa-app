@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')
@section('code', '404')
@section('heading', 'Halaman tidak ditemukan')
@section('message', 'Data yang kamu cari tidak ada atau mungkin sudah dihapus. Tenang, uangmu tetap aman.')
@section('icon-bg', 'bg-amber-50')
@section('icon-text', 'text-amber-600')

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
    <a href="javascript:history.back()" class="btn-outline">Halaman sebelumnya</a>
@endsection
