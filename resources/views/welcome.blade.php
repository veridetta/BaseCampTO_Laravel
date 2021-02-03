@extends('template')
@section('title', 'BasecampTO ')
 
@section('intro-header')
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
        <div class="jumbotron bg-white" style="min-height:100vh;">
            <div class="row align-items-center justify-content-center h-100">
                <div class="col-md-6 order-2 mt-3 mt-md-0 order-md-first">
                <p class="h2 text-danger">Try Out UTBK 2020-2021 hanya di BaseCampTO by Bagja College!</p>
                <p class="h5">Dengan sistem penilain dan analisa jawaban yang lengkap dan relevan berdasarkan UTBK 2021</p>
                <p class="h3 "><span class="badge badge-primary"><i class="fa fa-file text-white"></i> Road To Warrior!</span></p>
                <p class="h2"><i class="fa fa-calendar text-danger"></i> <span class="badge badge-warning"></i> 06 - 08 Februari 2021</span></p>
                <hr>
                <a type="button" href="{{route('register')}}" class="btn btn-info"><p class="h4">Daftar</p></a> <a type="button" href="{{route('login')}}" class="btn btn-danger"><p class="h4">Masuk</p></a>
                <hr>
                <p class="h5 text-primary">#AyoIkutTOSekarang #LebihCepatLebihBaik</p>
                </div>
                <div class="col-md-6 col-8 h-100 order-first order-md-2 d-flex flex-column justify-content-end">
                <img class="img img-fluid img-block" src="{{ asset('images/cbt.png') }}"/>
                </div>
            </div>
        </div>
@endsection

@section('main')
    <div class="container">
        <div class="col-12" style="min-height:100vh;margin-bottom:12px;" id="tentang" name="tentang">
            <p class="display-4 text-center text-info">Mengapa BC TryOut?</p>
            <hr>
            <div class="row align-items-center justify-content-center h-100">
            <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
                <div class="card border-info">
                <div class="card-body">
                    <p class="h5 text-center"><span class="badge badge-info">Our System</span></p>
                    <img class="img img-fluid" src="{{ asset('images/system.png') }}"/>
                    <p class="">Try Out Sudah menggunakan sistem UTBK 2020</p>
                </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
                <div class="card border-warning">
                <div class="card-body">
                    <p class="h5 text-center"><span class="badge badge-warning">Our Ratings</span></p>
                    <img class="img img-fluid" src="{{ asset('images/ratings.png') }}"/>
                    <p class="">Soal sudah menggunakan sistem penilaian butir sehingga membuat soal yang mudah mendapatkan nilai kecil begitu sebaliknya</p>
                </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
                <div class="card border-danger">
                <div class="card-body">
                    <p class="h5 text-center"><span class="badge badge-danger">Our Analytics</span></p>
                    <img class="img img-fluid" src="{{ asset('images/graph.png') }}"/>
                    <p class="">BC TO menyediakan analisa jawaban masing-masing siswa secara lengkap, sehingga siswa dapat melakukan evaluasi terhadap jawabannya.</p>
                </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
                <div class="card border-secondary">
                <div class="card-body">
                    <p class="h5 text-center"><span class="badge badge-secondary">Our Session</span></p>
                    <img class="img img-fluid" src="{{ asset('images/conversation.png') }}"/>
                    <p class="">BC TO menyediakan pembahasan yang mudah dipahami.</p>
                </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-10 col-xs-10 col-lg-4 col-xl-4 h-100" style="margin-top:12px;">
                <div class="card border-success">
                <div class="card-body">
                    <p class="h5 text-center"><span class="badge badge-success">Our Payment</span></p>
                    <img class="img img-fluid" src="{{ asset('images/bill.png') }}"/>
                    <p class="">Pembayaran yang mudah</p>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection

