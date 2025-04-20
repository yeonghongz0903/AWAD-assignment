@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1>Your Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <div class="alert alert-info">
            Your cart is empty. <a href="{{ route('products.index') }}">Continue shopping</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px;">
                                @endif
                                {{ $item->product->name }}
                            </td>
                            <td>RM{{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                           min="1" max="{{ $item->product->stock }}" 
                                           class="form-control d-inline-block" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>RM{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>RM{{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
            <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-shopping-cart"></i> Checkout Now
                </button>
            </form>
        </div>
    @endif
</div>
@endsection 