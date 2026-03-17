@extends('layouts.main')

@section('content')

<div class="container my-5">

    <h3 class="mb-4">Order Tracking - #{{ $order->id }}</h3>

    <div class="card p-4">

        <ul class="list-group">

            @foreach($order->tracking as $track)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ ucfirst($track->status) }}</strong>
                    <br>
                    <small>{{ $track->message }}</small>
                </div>

                <span class="text-muted">
                    {{ $track->created_at->format('d M Y, h:i A') }}
                </span>
            </li>
            @endforeach

        </ul>

    </div>

</div>

@endsection