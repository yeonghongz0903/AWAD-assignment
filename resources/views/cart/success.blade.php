@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Order Successful!</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                        <h5 class="mt-3">Thank you for your purchase!</h5>
                        <p>Your order has been successfully processed.</p>
                    </div>

                    <div class="receipt border p-3 mb-4">
                        <h5>Order Receipt</h5>
                        <hr>
                        <div class="items-list">
                            @foreach($cartItems as $item)
                            <div class="row mb-2">
                                <div class="col-6">
                                    {{ $item->product->name }} × {{ $item->quantity }}
                                </div>
                                <div class="col-6 text-end">
                                    RM{{ number_format($item->product->price * $item->quantity, 2) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="row fw-bold">
                            <div class="col-6">
                                Total
                            </div>
                            <div class="col-6 text-end">
                                RM{{ number_format($total, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Return to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 