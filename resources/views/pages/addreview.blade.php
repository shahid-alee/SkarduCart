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
<form action="{{ route('review.store') }}" method="POST">
    @csrf

    @foreach($order->items as $index => $item)

    <div class="card mb-4 p-3">
        <h5>{{ $item->product_name }}</h5>

        <input type="hidden" name="reviews[{{ $index }}][product_id]" value="{{ $item->product_id }}">
        <input type="hidden" name="reviews[{{ $index }}][order_id]" value="{{ $order->id }}">

        <div class="mb-3">
            <label>Rating</label>

            <div class="star-rating">
                @for($i = 5; $i >= 1; $i--)
                    <input type="radio"
                           name="reviews[{{ $index }}][rating]"
                           id="star{{ $index }}_{{ $i }}"
                           value="{{ $i }}" required>

                    <label for="star{{ $index }}_{{ $i }}">★</label>
                @endfor
            </div>
        </div>

        <div class="mb-3">
            <label>Review</label>
            <textarea name="reviews[{{ $index }}][review]" class="form-control"></textarea>
        </div>
    </div>

    @endforeach

    <button class="btn btn-primary">Submit Reviews</button>
</form>

</div>

@endsection