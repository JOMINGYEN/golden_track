@extends('admin.pages.auth.index_auth')
@section('content')
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <a href="index.html">
                                <span><img src="{{asset('public/backend/images/logo-dark.png')}}" alt="" height="22"></span>
                            </a>
                            <p class="text-muted mb-4 mt-3">Nhập Email Và Mật Khẩu Để Truy Cập Trang Quản Trị.</p>
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    {!! session()->get('message') !!}
                                    {!! session()->forget('message') !!}
                                </div>
                            @elseif(session()->has('error'))
                                <div class="alert alert-danger">
                                    {!! session()->get('error') !!}
                                    {!! session()->forget('error') !!}
                                </div>
                            @endif
                        </div>
                        <form action="{{ URL::to('/login') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group mb-3">
                                <label for="emailaddress">Email</label>
                                <input name="admin_email" class="form-control" type="email" id="emailaddress" required="" placeholder="Email">
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Mật Khẩu</label>
                                <input name="admin_password" class="form-control" type="password" required="" id="password" placeholder="Mật khẩu">
                            </div>
                            {{--  <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="admin_remember_login" value="1" class="custom-control-input" id="checkbox-signin">
                                    <label class="custom-control-label"  for="checkbox-signin">Remember me</label>
                                </div>
                            </div>  --}}
                            <div class="form-group mb-0 text-center">
                                <button name="login" class="btn btn-primary btn-block" type="submit"> Đăng Nhập </button>
                            </div>

                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p> <a href="{{URL::to('/get-email-admin')}}" class="text-primary font-weight-medium ml-1">Quên Mật Khẩu?</a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->
@endsection
