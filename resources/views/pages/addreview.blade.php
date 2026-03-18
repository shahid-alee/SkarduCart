@extends('layouts.main')

@section('content')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 30px;
        color: #ccc;
        cursor: pointer;
        transition: 0.3s;
    }

    .star-rating label:hover,
    .star-rating label:hover~label {
        color: #ffc107;
    }

    .star-rating input:checked~label {
        color: #ffc107;
    }
</style>

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
                <label class="mb-2">Rating</label>

                <div class="star-rating me-5">

                    @for($i = 5; $i >= 1; $i--)
                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required>
                    <label for="star{{ $i }}">★</label>
                    @endfor

                </div>

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