@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">{{ $product->name }}</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <span class="text-muted">No image available</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-3">Product Details</h3>
                            <p class="lead">RM{{ number_format($product->price, 2) }}</p>
                            <p>{{ $product->description }}</p>
                            
                            @auth
                                <form action="{{ route('cart.store', $product) }}" method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="10">
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info mt-4">
                                    <p>Please <a href="{{ route('login') }}">login</a> to add items to your cart.</p>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 