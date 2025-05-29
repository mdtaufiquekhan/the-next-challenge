@extends('layouts.app')

@section('title', $challenge->title)

@section('content')
<div class="container my-5 show-main-body">

    <a href="{{ route('challenges.index') }}" class="btn btn-sm btn-outline-secondary mb-4">&larr; Back to All Challenges</a>

    <div class="card shadow-sm">
        {{-- Challenge Cover Image --}}
        <img 
            src="{{ $challenge->cover_image ? asset($challenge->cover_image) : asset('images/dummy-card.jpg') }}" 
            class="card-img-top" 
            alt="Challenge cover image" 
            style="max-height: 300px; object-fit: cover;"
        >

        <div class="card-body">
            <h2 class="card-title mb-3">{{ $challenge->title }}</h2>
            <p><strong>Status:</strong> {{ ucfirst($challenge->status) }}</p>

            @if($challenge->review_challenge)
                <div class="mb-4">
                    <h5 class="mb-2">The Challenge</h5>
                    {!! $challenge->review_challenge !!}
                </div>
            @endif

            @if($challenge->review_solution)
                <div class="mb-4">
                    <h5 class="mb-2">Solution Requirements</h5>
                    {!! $challenge->review_solution !!}
                </div>
            @endif

            @if($challenge->review_submission)
                <div class="mb-4">
                    <h5 class="mb-2">Your Submission</h5>
                    {!! $challenge->review_submission !!}
                </div>
            @endif

            @if($challenge->review_evaluation)
                <div class="mb-4">
                    <h5 class="mb-2">Evaluation Criteria</h5>
                    {!! $challenge->review_evaluation !!}
                </div>
            @endif

            @if($challenge->review_awards)
                <div class="mb-4">
                    <h5 class="mb-2">Awards</h5>
                    {!! $challenge->review_awards !!}
                </div>
            @endif

            @if($challenge->review_participation)
                <div class="mb-4">
                    <h5 class="mb-2">Participation Guidance</h5>
                    {!! $challenge->review_participation !!}
                </div>
            @endif

            @if($challenge->review_deadline)
                <div class="mb-4">
                    <h5 class="mb-2">Submission Deadline</h5>
                    {!! $challenge->review_deadline !!}
                </div>
            @endif

            @if($challenge->review_resources)
                <div class="mb-4">
                    <h5 class="mb-2">Supporting Resources</h5>
                    {!! $challenge->review_resources !!}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
