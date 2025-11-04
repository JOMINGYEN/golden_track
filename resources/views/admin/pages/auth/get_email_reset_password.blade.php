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
                            <p class="text-muted mb-4 mt-3">Nhập Email nhận mã xác thực để đặt lại mật khẩu.</p>
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
                        <form action="{{URL::to('/verification-email-admin')}}" method="POST" >
                            @csrf
                            <div class="form-group mb-3">
                                <label for="emailaddress">Email</label>
                                <input class="form-control" name="verification_password" type="email" id="emailaddress" required="" placeholder="Email">
                            </div>
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" type="submit">Lấy Mã Xác Thực </button>
                            </div>
                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Quay Lại <a href="{{URL::to('/admin')}}" class="text-primary font-weight-medium ml-1">Đăng Nhập</a></p>
                        <p class="text-muted">Nếu Đã Có Mã Xác Thực <a href="{{URL::to('/reset-password-admin')}}" class="text-primary font-weight-medium ml-1">Đặt Lại Mật Khẩu Ngay</a></p>
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
