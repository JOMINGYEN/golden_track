@extends('client.index_layout')
@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="row">
        <div class="col-12">
            <div class="breadcrumb_content">
                <ul>
                    <li><a href="index.html">home</a></li>
                    <li><i class="fa fa-angle-right"></i></li>
                    <li>Cửa Hàng</li>
                </ul>

            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
<!--about section area -->
@foreach ($all_about_us as $key=>$about_us)
    <div class="about_section section_two">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="about_content">
                    <h1>{{ $about_us->cuahang_tieu_de }}</h1>
                   <p>{{ $about_us->cuahang_mo_ta }} </p>
                   <p><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $about_us->cuahang_dia_chi }}</p>
                   <p><i class="fa fa-mobile" aria-hidden="true"></i> (84+) {{ $about_us->cuahang_so_dien_thoai }}</p>
                   <a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $about_us->cuahang_email }} </a>
                </div>
            </div>
            <div class="col-12">
                <div class="about_thumb">
                    <img src="{{asset('public/uploads/admin/aboutstore/'.$about_us->cuahang_anh)}}" width="710px" height="340px"  alt="">
                </div>
            </div>
        </div>
    </div>
@endforeach
<!--about section end-->

@endsection
