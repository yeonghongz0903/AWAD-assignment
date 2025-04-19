@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Products</h1>
        </div>
        @can('create', App\Models\Product::class)
        <div class="col-md-6 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
        </div>
        @endcan
    </div>

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text"><strong>Price: RM{{ number_format($product->price, 2) }}</strong></p>
                        <p class="card-text">Stock: {{ $product->stock }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-info">View Details</a>
                            
                            @auth
                                <form action="{{ route('cart.store', $product) }}" method="POST">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control d-inline-block" style="width: 70px;">
                                    <button type="submit" class="btn btn-success">Add to Cart</button>
                                </form>
                            @endauth
                        </div>

                        @can('update', $product)
                            <div class="mt-2">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        @endcan
                    </div>

                </div>

            </div>
        @endforeach
    </div>
</div>
@endsection 