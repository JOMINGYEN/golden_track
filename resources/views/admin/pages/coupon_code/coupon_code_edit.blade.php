@extends('admin.index_layout_admin')
@section('content')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                <a href="{{URL::to('/coupon-code')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Mã Khuyến Mãi</a>
                                <a href="{{URL::to('/coupon-code-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Cập Nhật Mã Khuyến Mãi</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Thông Tin Mã Khuyến Mãi</h4>
                            <hr>
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
                                <div class="col-12">
                                    <div class="p-2">
                                        <form action="{{URL::to ('/coupon-code-save-edit/'.$coupon_code->id)}}" class="form-horizontal" role="form"  method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Tên</label>
                                                    <input type="text" value="{{ $coupon_code->makhuyenmai_ten_ma }}" name="coupon_code_name" class="form-control" required="" placeholder="Tên">
                                                    @error('coupon_code_name')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Mã Code</label>
                                                    <input type="text" name="coupon_code_code" value="{{ $coupon_code->makhuyenmai_ma }}" class="form-control" required="" placeholder="Mã Code">
                                                    @error('coupon_code_code')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Số Lượng</label>
                                                    <input type="number" value="{{ $coupon_code->makhuyenmai_so_luong }}" min="1" name="coupon_code_quantity" class="form-control" required="" placeholder="Số lượng">
                                                    @error('coupon_code_quantity')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Loại Mã</label>
                                                    <select name="coupon_code_type" class="form-control">
                                                        @if( $coupon_code->makhuyenmai_loai_ma ==1)
                                                            <option selected value="1">$</option>
                                                            <option value="0">%</option>
                                                        @else
                                                            <option value="1">$</option>
                                                            <option selected value="0">%</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Giá Trị Khuyến Mãi</label>
                                                    <input type="number" value="{{ $coupon_code->makhuyenmai_gia_tri }}" min="1" name="coupon_code_value" class="form-control" required="" placeholder="Giá trị">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Từ Ngày</label>
                                                    <input type="date" value="{{ $coupon_code->makhuyenmai_ngay_bat_dau }}" name="coupon_code_from_day" class="form-control">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Đến Ngày</label>
                                                    <input type="date" value="{{ $coupon_code->makhuyenmai_ngay_ket_thuc }}" name="coupon_code_to_day" class="form-control">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <button type="submit" class="btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Lưu</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end col-->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card-box -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
            <!-- end content -->
            <!-- end page title -->
        </div>
        <!-- container -->
    </div>
    <!-- content -->
    <!-- Footer Start -->
    @include('admin.blocks.footer_admin')
    <!-- end Footer -->
</div>
@endsection
