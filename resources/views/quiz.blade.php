@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Quiz Game Question: <span id="index_number"></span> out of <span id="total_number"></span>
                </div>
                <div class="card-body">
                    <h5 class="card-title" id="question"></h5>
                    <input type="hidden" id="current_index" value="{{$index}}">
                    <div id="answers">
                        {{-- populated by js --}}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-wrapper text-center d-flex justify-content-between">
                        <a class="btn btn-secondary {{ $back ? '' : 'disabled' }}"
                            href="{{ $back ?? '#' }}">
                            Back
                        </a>
                        <a class="btn btn-primary"
                            href="{{ $next ?? '#' }}">
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