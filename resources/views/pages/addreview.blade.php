@extends('layouts.main')

@section('content')

<div class="container my-5">

<h2 class="mb-4">Leave a Review</h2>

@foreach($order->items as $item)

<div class="card mb-4 p-3">

<h5>{{ $item->product_name }}</h5>

<form action="{{ route('review.store') }}" method="POST">
@csrf

<input type="hidden" name="product_id" value="{{ $item->product_id }}">
<input type="hidden" name="order_id" value="{{ $order->id }}">

<div class="mb-3">

<label>Rating</label>

<select name="rating" class="form-control" required>
<option value="">Select Rating</option>
<option value="5">⭐⭐⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="2">⭐⭐</option>
<option value="1">⭐</option>
</select>

</div>

<div class="mb-3">
<label>Review</label>
<textarea name="review" class="form-control"></textarea>
</div>

<button class="btn btn-primary">Submit Review</button>

</form>

</div>

@endforeach

</div>

@endsection