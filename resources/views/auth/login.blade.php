@extends('layouts.auth')

@section('content')
    <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
        <div class="block-content block-content-full px-lg-4 px-xl-5 py-6 bg-white">
            <div class="mb-2 text-center">
                <a class="link-fx font-w700 font-size-h1" href="{{route('home')}}">
                    <span class="text-dark">TROPICAL</span><span class="text-primary">GIDA</span>
                </a>
                <p class="text-uppercase font-w700 font-size-sm text-muted">Sign In</p>
            </div>
            <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group mt-4">
                    <div class="input-group">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="login-username" name="name" placeholder="Username" required autocomplete="name" autofocus />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-user-circle"></i>
                            </span>
                        </div>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mt-5">
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="login-password" name="password" placeholder="Password" required autocomplete="current-password" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-asterisk"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mt-4 d-sm-flex justify-content-sm-between align-items-sm-center text-center text-sm-left">
                    <div class="custom-control custom-checkbox custom-control-primary">
                        <input type="checkbox" class="custom-control-input" id="login-remember-me" name="remember"  {{ old('remember') ? 'checked' : '' }} />
                        <label class="custom-control-label" for="login-remember-me">Remember Me</label>
                    </div>
                </div>
                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-hero-primary">
                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Sign In
                    </button>
                </div>
            </form>
        </div>
        {{-- <div class="block-content bg-body">
            <div class="d-flex justify-content-center text-center push">
                <a class="item item-circle item-tiny mr-1 bg-default" data-toggle="theme" data-theme="default" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny mr-1 bg-xwork" data-toggle="theme" data-theme="{{asset('master/css/themes/xwork.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny mr-1 bg-xmodern" data-toggle="theme" data-theme="{{asset('master/css/themes/xmodern.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny mr-1 bg-xeco" data-toggle="theme" data-theme="{{asset('master/css/themes/xeco.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny mr-1 bg-xsmooth" data-toggle="theme" data-theme="{{asset('master/css/themes/xsmooth.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny mr-1 bg-xinspire" data-toggle="theme" data-theme="{{asset('master/css/themes/xinspire.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny mr-1 bg-xdream active" data-toggle="theme" data-theme="{{asset('master/css/themes/xdream.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny mr-1 bg-xpro" data-toggle="theme" data-theme="{{asset('master/css/themes/xpro.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
                <a class="item item-circle item-tiny bg-xplay" data-toggle="theme" data-theme="{{asset('master/css/themes/xplay.min-2.0.css')}}" href="op_auth_signin_box_alt.html#"></a>
            </div>
        </div> --}}
    </div>
@endsection
