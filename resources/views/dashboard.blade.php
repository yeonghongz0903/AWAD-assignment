@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="display-4">Welcome to Chiikawa Shop!</h1>
                    <p class="lead">Discover the cutest Chiikawa merchandise and bring home your favorite characters!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Grid -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Featured Products</h3>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">View All Products</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($featuredProducts->take(3) as $product)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                        <p class="card-text"><strong>Price: RM{{ number_format($product->price, 2) }}</strong></p>
                                        <a href="/products/{{ $product->id }}" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Special Offers -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Special Offers</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h4 class="alert-heading">Limited Time Offer!</h4>
                        <p>Get 10% off on all Chiikawa plush toys this week. Use code: <strong>CHIIKAWA10</strong> at checkout.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Why Choose Chiikawa Shop?</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-truck fa-3x mb-3"></i>
                                    <h5>Fast Shipping</h5>
                                    <p>Quick delivery to your doorstep</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-shield-alt fa-3x mb-3"></i>
                                    <h5>Authentic Products</h5>
                                    <p>100% genuine Chiikawa merchandise</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-headset fa-3x mb-3"></i>
                                    <h5>24/7 Support</h5>
                                    <p>Always here to help you</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .card {
        transition: transform 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
@endsection
