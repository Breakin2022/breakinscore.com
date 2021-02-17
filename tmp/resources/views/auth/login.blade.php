@extends('layouts.app')

@section('content')
  <img src="{{ asset('public/img/bg.jpg') }}" id="bg" alt="">

    <div class="row" style="margin:0px;">
        <div id="login-div" class="col-md-3 login-main">
          <img src="{{ asset('public/img/logo.jpg') }}" style="width:100%;" alt="">
            <div class="panel panel-default" style="background-color:transparent;border-color:transparent; padding-bottom:20px;">
                {{-- <div class="panel-heading">Login</div> --}}
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {{-- <label for="email" class="col-md-4 control-label">E-Mail Address</label> --}}

                            <div class="col-md-12">
                                <input id="email" type="text" placeholder="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if (Session::has('status'))
                                  <span class="help-block bg-danger">
                                      <strong>{{ Session('status') }}</strong>
                                  </span>
                                @endif
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {{-- <label for="password" class="col-md-4 control-label">Password</label> --}}

                            <div class="col-md-12">
                                <input id="password" type="password" placeholder="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 ">
                                <div class="checkbox">
                                    <label style="    color: white;font-weight: bold;">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit"  style="width:100%" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                            {{-- <div class="col-md-12">
                              <a class="btn btn-link" href="{{ route('password.request') }}">
                                  Forgot Your Password?
                              </a>

                            </div> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
