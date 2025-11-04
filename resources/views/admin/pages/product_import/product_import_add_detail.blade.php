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
                                <a href="{{URL::to('/product-import-edit/'.$product_import->id)}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Thêm Chi Tiết Nhập</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
             <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title">Thông Tin Chi Tiết Nhập</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-2">
                                    <form action="{{ URL::to('product-import-add-detail-save/'.$product_import->id) }}" enctype="multipart/form-data" class="form-horizontal" role="form"  method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-sm-4 img-product-import-select">

                                            </div>
                                            <div class="col-sm-8">
                                                <label class="col-form-label">Mã Đơn Nhập</label>
                                                <input type="text" name="product_import_no" value="{{ $product_import->donnhaphang_ma_don_nhap_hang }}" required="" readonly class="form-control" >
                                                <input type="hidden" name="product_import_id" value="{{ $product_import->id }}" required="" class="form-control" >
                                                <hr>
                                                <label class="col-form-label">Sản Phẩm</label>
                                                <select name="product_import_detail_product_id" id="product-import-add" class="form-control select-product-import-add">
                                                    @foreach ($all_product as $key => $product)
                                                        <option value="{{ $product->id }}">{{ $product->sanpham_ten }} - {{ $product->sanpham_ma_san_pham }}</option>
                                                    @endforeach
                                                </select>
                                                <label class="col-form-label">Size</label>
                                                
                                                <label class="col-form-label">Số Lượng</label>
                                                <input type="text" name="product_import_detail_quantity" required="" class="form-control">
                                                <label class="col-form-label">Giá Nhập</label>
                                                <input type="text" name="product_import_detail_price" required="" class="form-control" >
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
            </div><!-- end row -->
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