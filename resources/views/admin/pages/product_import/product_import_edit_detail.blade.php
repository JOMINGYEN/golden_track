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
                                <a href="{{URL::to('/product-import-edit/'.$product_import_detail->donnhaphang_id)}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Cập Nhật Chi Tiết Nhập</li>
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
                                    <form action="{{ URL::to('product-import-edit-detail-save/'.$product_import_detail->id) }}" enctype="multipart/form-data" class="form-horizontal" role="form"  method="post" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                                       @csrf
                                        <div class="form-group row">
                                            <div class="col-sm-4 img-product-import-select">
                                                <img class=" mt-3" width="300px" height="300px" id="image" src="{{ asset('public/uploads/admin/product/'.$product_import_detail->Product->sanpham_anh) }}" />
                                            </div>
                                            <div class="col-sm-8">
                                                <label class="col-form-label">Mã Đơn Nhập</label>
                                                <input type="text" name="product_import_no" value="{{ $product_import_detail->chitietnhap_ma_don_nhap_hang }}" required="" readonly class="form-control" >
                                                <input type="hidden" name="product_import_id" value="{{ $product_import_detail->donnhaphang_id }}" required="" class="form-control" >
                                                <label class="col-form-label">Sản Phẩm</label>
                                                <input type="text" value="{{ $product_import_detail->Product->sanpham_ten }} - {{ $product_import_detail->Product->sanpham_ma_san_pham }}" required="" readonly class="form-control">
                                                <input type="hidden" name="product_import_detail_product_id" value="{{ $product_import_detail->sanpham_id }}" required="" readonly class="form-control">
                                                <label class="col-form-label">Số Lượng Đã Bán</label>
                                                <input type="text" value="{{ $product_in_stock->sanphamtonkho_so_luong_da_ban }}" required="" readonly class="form-control">
                                                <label class="col-form-label">Số Lượng Tồn</label>
                                                <input type="text" name="product_quantity_sold_old" value="{{ $product_in_stock->sanphamtonkho_so_luong_ton }}" required="" readonly class="form-control">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="col-form-label">Số Lượng Nhập</label>
                                                <input type="text" min="1" name="product_import_detail_quantity" value="{{ $product_import_detail->chitietnhap_so_luong_nhap }}" required="" class="form-control">
                                                <input type="hidden" min="1" name="product_import_detail_quantity_old" value="{{ $product_import_detail->chitietnhap_so_luong_nhap }}" required="" class="form-control">
                                            </div>
                                            <div class="col-sm-12">
                                                <label class="col-form-label">Giá Nhập</label>
                                                <input type="text" min="1" name="product_import_detail_price" value="{{number_format($product_import_detail->chitietnhap_gia_nhap,0,',','.' ) }}" required="" class="form-control" >
                                                <input type="hidden" min="1" name="product_import_detail_price_old" value="{{ $product_import_detail->chitietnhap_gia_nhap }}" required="" class="form-control" >
                                            </div>
                                            <div class="col-sm-12">
                                                <label class="col-form-label">Size</label>
                                                <input type="hidden" min="1" name="product_import_detail_size_id_old" value="{{ $product_import_detail->size_id }}" required="" class="form-control" >
                                                <select name="product_import_detail_size_id" class="form-control">
                                                   
                                                </select>
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