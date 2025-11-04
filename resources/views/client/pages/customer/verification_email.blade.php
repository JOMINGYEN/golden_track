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
                    <li>Nhập Email Đăng Ký</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
<!-- customer login start -->
<div class="customer_login">
<div class="row">
    <!--blog area start-->
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
    <div class="main_blog_area">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-6">
                <div class="blog_details_left">
                    <div class="blog_gallery">
                        <form action="{{URL::to('/verification-email-customer')}}" method="POST">
                            @csrf
                            <div class="blog_thumb blog__hover">
                                <input type="email" required="" placeholder="Email" name="verification_email">
                            </div>
                            <div class="blog_fullwidth_desc">
                                <p>Nhập email đăng ký để nhận mã xác thực</p>
                                 <button type="submit">Xác Nhận</button>
                            </div>
                            <div class="blog_fullwidth_desc">
                                <p>Nếu đã có mã xác thực</p>
                                 <a href="{{URL::to('/register-customer')}}">Đăng ký</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div
        </div>
    </div>
    <!--blog area end-->
        </div>
</div>
<!-- customer login end -->
@endsection
