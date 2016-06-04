@extends('layouts.app')

@section('content')
<div class="row">
    <h1>Register</h1>
    <form class="col s12" method="POST" action="{{ url('/register') }}">
        {{ csrf_field() }}
        <div class="row">
            <div class="input-field col s12">
                <input id="name" name="name" type="text" class="validate" value="{{ old('name') }}">
                <label for="name">Name</label>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <input id="email" name="email" type="email" class="validate" value="{{ old('email') }}">
                <label for="email">Email</label>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <input id="password" name="password" type="password" class="validate">
                <label for="password">Password</label>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <input id="password_confirmation" name="password_confirmation" type="password" class="validate">
                <label for="password_confirmation">Confirm Password</label>
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <button class="btn waves-effect waves-light" type="submit" name="action">Register
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
