@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ auth()->user()->name }}! 🎉</h5>
                            <p class="mb-4">
                                Anda login sebagai <span class="fw-bold">{{ ucfirst(auth()->user()->role) }}</span>. 
                                Silakan gunakan menu di samping untuk mengelola Master Data.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
