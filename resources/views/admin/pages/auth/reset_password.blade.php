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
                            <p class="text-muted mb-4 mt-3">Đặt Lại Mật Khẩu</p>
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
                        <form action="{{ URL::to('/reset-password-admin-save') }}" method="POST">
                           @csrf
                            <div class="form-group mb-3">
                                <label for="emailaddress">Email</label>
                                <input name="admin_reset_confirm_email" class="form-control" type="email" id="emailaddress" required="" placeholder="Email">
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Mật Khẩu Mới</label>
                                <input name="admin_reset_new_password" class="form-control" type="password" required=""  placeholder="Mật khẩu mới">
                                @error('admin_reset_new_password')
                                <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Xác Nhận Mật Khẩu</label>
                                <input name="admin_reset_confirm_new_password" class="form-control" type="password" required="" placeholder="Xác nhận mật khẩu">
                                @error('admin_reset_confirm_new_password')
                                <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Mã Xác Thực</label>
                                <input name="admin_reset_password_verification_code" class="form-control" type="text" required="" placeholder="Mã xác thực">
                            </div>
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" type="submit">Đặt Lại Mật Khẩu</button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Quay Lại <a href="{{URL::to('/admin')}}" class="text-primary font-weight-medium ml-1">Đăng Nhập</a></p>
                        <p class="text-muted">Quay Lại<a href="{{URL::to('/get-email-admin')}}" class="text-primary font-weight-medium ml-1">Nhận Mã Xác Thực</a></p>
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
