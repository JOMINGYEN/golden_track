@extends('client.index_layout')
@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="row">
        <div class="col-12">
            <div class="breadcrumb_content">
                <ul>
                    <li><a href="index.html">Trang Chủ</a></li>
                    <li><i class="fa fa-angle-right"></i></li>
                    <li>Đăng nhập</li>
                </ul>

            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
<!-- customer login start -->
<div class="customer_login">
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
<div class="row">
           <!--login area start-->

           <div class="col-md-3"></div>
            <div class="col-lg-6 col-md-6">
                <div class="account_form">
                    <h2 style="text-align: center">Đăng nhập</h2>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error}}</p>
                        @endforeach
                    @endif
                    <form action="{{ URL::to('/check-login-customer')}}" method="POST">
                        @csrf
                        <p>
                            <label>Email <span>*</span></label>
                            <input type="email" required="" name="customer_email_login">
                        </p>
                        <p>
                            <label>Mật Khẩu <span>*</span></label>
                            <input type="password" required="" name="customer_password_login">
                        </p>
                        <div class="login_submit">
                            <button type="submit">Đăng nhập</button>
                            {{--  <label for="remember">
                                <input id="remember" type="checkbox">
                                Remember me
                            </label>  --}}
                            <a href="{{ URL::to('/show-verification-password-customer')}}">Quên Mật Khẩu?</a>
                        </div>
                    </form>
                 </div>
            </div>
            <!--login area start-->
        </div>
</div>
<!-- customer login end -->
@endsection
