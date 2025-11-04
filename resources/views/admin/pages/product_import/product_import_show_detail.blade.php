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
                                <a href="{{URL::to('/product-import')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Nhập Hàng</a>
                                <a href="{{URL::to('/product-import-add-multiple')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Chi Tiết Đơn Nhập</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title">Thông Tin Đơn Nhập</h4>
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
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="col-form-label"> <h4>Thông Tin Nhà Cung Cấp</h4></label>
                                                    <div class="table-responsive" id="ajax-queue">
                                                        <table class="table table-hover  mb-0">
                                                            <thead>
                                                            <tr>
                                                                <td>Tên</td>
                                                                <td>Số Điện Thoại</td>
                                                                <td>Email</td>
                                                                <td>Địa Chỉ</td>
                                                                <td>Trạng Thái</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td >{{ $product_import->Supplier->nhacungcap_ten}} </td>
                                                                    <td>{{ $product_import->Supplier->nhacungcap_so_dien_thoai}}</td>
                                                                    <td>{{$product_import->Supplier->nhacungcap_email }}</td>
                                                                    <td>{{ $product_import->Supplier->nhacungcap_dia_chi}}</td>
                                                                    <td>
                                                                        <span class="badge">
                                                                            <?php
                                                                            if($product_import->Supplier->nhacungcap_trang_thai==1)
                                                                            { ?>
                                                                            <a href="#"> <i class="fa fa-thumbs-styling fa-thumbs-up"></i></a>
                                                                            <?php
                                                                            }else
                                                                            { ?>
                                                                                <a href="#"> <i class="fa fa-thumbs-styling fa-thumbs-down"></i></a>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                           </span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                            <label class="col-form-label"> <h4>Thông Tin Nhập</h4></label>
                                            <div class="table-responsive" id="ajax-queue">
                                                <table class="table table-hover  mb-0">
                                                    <thead>
                                                    <tr>
                                                        <td>Mã Đơn Nhập</td>
                                                        <td>Ngày Nhập</td>
                                                        <td>Tổng Cộng</td>
                                                        <td>Trạng Thái</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody >
                                                        <tr>
                                                            <td>
                                                                {{ $product_import->donnhaphang_ma_don_nhap_hang}}
                                                            </td>
                                                            <td>
                                                                {{ date('d-m-Y', strtotime( $product_import->donnhaphang_ngay_nhap)) }}
                                                            </td>
                                                            <td>
                                                                {{number_format($product_import->donnhaphang_tong_tien ).' VNĐ' }}
                                                            </td>
                                                            <td>
                                                                <span class="badge">
                                                                    <?php
                                                                    if($product_import->donnhaphang_trang_thai==1)
                                                                    { ?>
                                                                    <a href="#"> <i class="fa fa-thumbs-styling fa-thumbs-up"></i></a>
                                                                    <?php
                                                                    }else
                                                                    { ?>
                                                                        <a href="#"> <i class="fa fa-thumbs-styling fa-thumbs-down"></i></a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                   </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-2">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="col-form-label"> <h4>Chi Tiết Nhập</h4></label>
                                                    <div class="table-responsive" id="ajax-queue">
                                                        <table class="table table-hover  mb-0">
                                                            <thead>
                                                            <tr>
                                                                <td>Ảnh</td>
                                                                <td>Sản Phẩm</td>
                                                                <td>Size</td>
                                                                <td>Số Lượng Nhập</td>
                                                                <td>Giá Nhập</td>
                                                                <td>Tổng</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($product_import_detail as $key=> $product )
                                                                    <tr>
                                                                        <td><img src="{{asset('public/uploads/admin/product/'.$product->Product->sanpham_anh)}}" width="70px" height="75px" alt=""></td>
                                                                        <td>{{ $product->Product->sanpham_ten}}</td>
                                                                        <td>{{ $product->Size->size }}</td>
                                                                        <td >{{ $product->chitietnhap_so_luong_nhap }} </td>
                                                                        <td>{{number_format($product->chitietnhap_gia_nhap ,0,',','.').' VNĐ' }}</td>
                                                                        <td>{{number_format($product->chitietnhap_so_luong_nhap * $product->chitietnhap_gia_nhap ,0,',','.').' VNĐ' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- end row -->
                    </div> <!-- end card-box -->
                </div><!-- end col -->
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
