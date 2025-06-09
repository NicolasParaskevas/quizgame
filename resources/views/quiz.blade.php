@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Quiz Game <span id="category"></span>: <span id="current_index"></span> out of {{$total}}
                </div>
                <div class="card-body">
                    <h5 class="card-title" id="question"></h5>
                    <div id="answers">
                        {{-- populated by js --}}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-wrapper text-end">
                        @csrf
                        <a class="btn btn-primary text" href="{{ $next ?? '#' }}" id="next-button">
                            Next
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@vite(['resources/js/quiz.js'])
@endsection