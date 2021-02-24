@extends('layouts.app')

@section('content')
    <div class="">
        <div class="content">
            <div class="card-header" style="color:white;">
                <div class="container form-container"><h1>Sign-Up / Register</h1>
                    <p style="color:white;">Complete all the steps starting with signing in. You are moments away from
                        getting a job shadow!</p>
                    <h5>NOTE: no more than one scholar per registration. </h5>
                </div>
                    
            </div>

        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="background-color:#6fc7c0;color:white;">
                        <div class="container form-container">
                            <form method="POST" action="{{ route('register') }}" class="register-form">
                                @csrf

                                <div class="form-group row">
                                    <label for="name"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text"
                                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               name="name" value="{{ old('name') }}" placeholder="NAME" required
                                               autofocus>

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <label for="email"
                                           class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               name="email" value="{{ old('email') }}" placeholder="EMAIL ADDRESS"
                                               required>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password"
                                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                               name="password" placeholder="PASSWORD">

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <label for="password-confirm"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" placeholder="REPEAT PASSWORD" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col-md-6">
                                        <label for="where_hear"
                                           class="col-md-4 col-form-label text-md-right">{{ __('Where did you hear about Job Shadow?') }}</label>
                                        <input id="where_hear" type="text"
                                               class="form-control{{ $errors->has('where_hear') ? ' is-invalid' : '' }}"
                                               name="where_hear" value="{{ old('where_hear') }}" placeholder="WHERE DID YOU HEAR ABOUT JOB SHADOW?" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
