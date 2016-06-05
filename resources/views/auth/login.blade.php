@extends('layouts.app')

@section('content')
<div class="row">
	<h1>Login</h1>
	<form class="col s12" method="POST" action="/login">
		{{ csrf_field() }}
		<div class="row">
			<div class="input-field col s6">
				<input id="name" name="name" type="text" class="validate">
				<label for="name">Name</label>
				@if ($errors->has('name'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('name') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="input-field col s6">
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
			<div class="col s6">
				<button class="btn waves-effect waves-light" type="submit" name="action">Login
					<i class="material-icons right">send</i>
				</button>
			</div>
			<div class="col s6 right-align">
				<a href="{{ url('/register') }}" class="waves-effect waves-light btn">Register</a>
			</div>
		</div>
	</form>
</div>
@endsection
