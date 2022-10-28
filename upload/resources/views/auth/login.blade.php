@extends('layouts.front')

@section('content')
<div class="login-background"></div>
<div class="login-box">
  <div class="login-logo">
    <a href="#">
@if(!empty(setting('logo_path')))
        <img src="{{asset(setting('logo_path'))}}" alt="" height="70px">
        @else
        <img src="{{asset('images/fpos.png')}}" alt="" height="70px">
        @endif

    </a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
  <p class="login-box-msg">{{__('Sign in to start your session')}}</p>
	@if (count($errors) > 0)
		<div class="alert alert-danger">
		<strong>Whoops!</strong> {{__('There were some problems with your input.')}}<br><br>
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
    <form data-no-ajax action="{{ route('login') }}" method="post" id="login_form">
      @csrf
      <div class="form-group has-feedback">
        <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="password" class="form-control" name="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> {{__('Remember Me')}}
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
		<button type="submit" submit-text="Loading..." class="btn btn-success btn-block">{{__('Sign In')}}</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <div class="social-auth-links text-center">

    </div>
    <!-- /.social-auth-links -->

	<a href="{{ route('password.request') }}">{{__('I forgot my password')}}</a><br>
  </div>
  <!-- /.login-box-body -->
</div>
@if(config('fpos.demo'))
<div class="login-box">
  <div class="text-center">
    <a href="javascript:;" class="btn btn-success" onclick="onClickLogin('admin@flexibleit.net', 'login_form')"><b>Admin:</b> <b>User:</b> admin@flexibleit.net <b>Pass:</b> password</a>
    <a href="javascript:;" class="btn btn-primary" onclick="onClickLogin('salesman@flexibleit.net', 'login_form')"><b>Salesman:</b> <b>User:</b> salesman@flexibleit.net <b>Pass:</b> password</a>
    <a href="javascript:;" class="btn btn-info" onclick="onClickLogin('account@flexibleit.net', 'login_form')"><b>Account:</b> <b>User:</b> account@flexibleit.net <b>Pass:</b> password</a>
  </div>
</div>
@endif
@endsection
@section('script')
<script src="{{asset('dist/js/icheck.min.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });

  function onClickLogin(email, formid) {
    
    $("#email").val(email);
    $("#password").val('password');
    $("#loginSubmit").text('Loading...');
    $("#"+formid).submit();
  }
</script>
@endsection
