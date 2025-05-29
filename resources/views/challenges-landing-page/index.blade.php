@extends('layouts.app')

@section('title', 'All Challenges')

@section('content')
<div class="container">


    <div class="mb-4 mt-4">
        <h2 class="d-flex justify-content-between align-items-center">
            <span>All Challenges</span>
        </h2>
    </div>

    @if($challenges->isEmpty())
        <div class="alert alert-info">
            No challenges found. Click the "Create New Challenge" card to start.
        </div>
    @endif

    <div class="row">
        {{-- Create New Challenge Card --}}
        <div class="col-md-4 mb-3 ">
            <div class="card-create-button">
                <a href="{{ route('challenges.create') }}" class="text-decoration-none">
                    <h4 class="mb-0">+ Create New Challenge</h4>
                </a>
            </div>
            
        </div>

        {{-- List of Challenges --}}
        @foreach($challenges as $challenge)
            <div class="col-md-4 mb-3 index-each-card">
                <div class="card h-100 shadow-sm">
                    {{-- Challenge Image (with fallback) --}}
                    <img 
                        src="{{ $challenge->cover_image ? asset($challenge->cover_image) : asset('images/dummy-card.jpg') }}" 
                        class="card-img-top" 
                        alt="Challenge cover image" 
                        style="height: 180px; object-fit: cover;"
                    >

                    <div class="card-body">
                        <h5 class="card-title">{{ $challenge->title }}</h5>
                        <p class="card-text">
                            <strong>Status:</strong> {{ ucfirst($challenge->status) }}
                        </p>
                        <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


</div>

@endsection
