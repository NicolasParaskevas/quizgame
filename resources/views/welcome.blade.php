@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Quiz Game
                </div>
                <div class="card-body">
                    <h5 class="card-title">Complete the form to get started!</h5>
                    <form method="POST" action="{{ route('start-quiz') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Full name') }}</label>
                            <input
                                id="name"
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="questions" class="form-label">{{ __('Number of Questions') }}</label>
                            <input
                                id="questions"
                                type="number"
                                class="form-control @error('questions') is-invalid @enderror"
                                name="questions"
                                value="{{ old('questions', 10) }}"
                                min="1"
                                max="50"
                                required
                            >
                            @error('questions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="difficulty" class="form-label">{{ __('Select Difficulty') }}</label>
                            <select
                                id="difficulty"
                                name="difficulty"
                                class="form-select @error('difficulty') is-invalid @enderror"
                                required
                            >
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            @error('difficulty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Select Type') }}</label>
                            <select
                                id="type"
                                name="type"
                                class="form-select @error('type') is-invalid @enderror"
                            >
                                <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>True / False</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Start!') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
