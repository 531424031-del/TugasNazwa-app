@extends('errors.layout')

@section('title', 'Akses Ditolak')
@section('code', '403')
@section('heading', 'Akses ditolak')
@section('message', 'Kamu tidak punya izin untuk membuka halaman ini. Hanya pemilik data yang bisa mengaksesnya.')
@section('icon-bg', 'bg-rose-50')
@section('icon-text', 'text-rose-500')

@section('icon')
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </svg>
@endsection

@section('actions')
    <a href="{{ url('/dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
@endsection
