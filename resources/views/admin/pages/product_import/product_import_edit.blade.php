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
                            <li class="breadcrumb-item active">Cập Nhật Nhập Hàng</li>
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
                                            <form action="{{URL::to('/product-import-edit-save/'.$product_import->id)}}" method="POST" class="form-horizontal">
                                                {{ csrf_field() }}
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                    <div class="card-body">
                                                        <label class="col-form-label">Mã Đơn Nhập</label>
                                                        <input type="text" name="product_import_no" required="" value="{{ $product_import->donnhaphang_ma_don_nhap_hang }}" readonly class="form-control product_import_no">
                                                        <label class=" col-form-label">Ngày Nhập</label>
                                                        <input type="date" name="product_import_day" value="{{ $product_import->donnhaphang_ngay_nhap }}" required="" class="form-control product_import_day" >
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label  class="col-form-label">Nhân Viên</label>
                                                                @foreach ($get_admin as $key => $admin )
                                                                    <input type="text" value="{{ $admin->admin_ten  }}" readonly class="form-control product_import_staff">
                                                                    <input type="hidden" name="product_import_staff" value="{{ $admin->id }}"  class="form-control product_import_staff">
                                                                @endforeach
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="col-form-label">Trạng Thái</label>
                                                                <select name="product_import_status" required="" class="form-control product_import_status">
                                                                        @if($product_import->donnhaphang_trang_thai==1)
                                                                            <option selected value="1">Hoàn Thành</option>
                                                                            <option value="0">Chưa Hoàn Thành</option>
                                                                        @else
                                                                            <option value="1">Hoàn Thành</option>
                                                                            <option selected value="0">Chưa Hoàn Thành</option>
                                                                        @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label class="col-form-label">Nhà Cung Cấp</label>
                                                                <select name="product_import_supplier" required="" class="form-control product_import_supplier">
                                                                    @foreach ($all_supplier as $key => $supplier)
                                                                        @if($supplier->id == $product_import->nhacungcap_id)
                                                                            <option selected value="{{ $supplier->id }}">{{ $supplier->nhacungcap_ten }}</option>
                                                                        @else
                                                                            <option value="{{ $supplier->id }}">{{ $supplier->nhacungcap_ten}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <div class="text-lg-right mt-3 mt-lg-0">
                                                                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-content-save mr-1"></i>Lưu Đơn Nhập</button>
                                                                </div>
                                                            </div>
                                                        </div
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </form>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Chi Tiết Nhập</label>
                                                    <div class="table-responsive" id="ajax-queue">
                                                        <table class="table table-hover  mb-0">
                                                            <thead>
                                                            <tr>
                                                                <td>Ảnh</td>
                                                                <td>Sản Phẩm</td>
                                                                <td>Số Lượng Nhập</td>
                                                                <td>Giá Nhập</td>
                                                                <td>Size</td>
                                                                <td>Tổng</td>
                                                                <td>#</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="show-list-product">
                                                                @foreach ($get_product_import_detail as $key=> $product_import_detail )
                                                                    <tr>
                                                                        <td><img src="{{asset('public/uploads/admin/product/'.$product_import_detail->Product->sanpham_anh)}}" width="70px" height="75px" alt=""></td>
                                                                        <td>{{ $product_import_detail->Product->sanpham_ten}}</td>
                                                                        <td >{{ $product_import_detail->chitietnhap_so_luong_nhap }} </td>
                                                                        <td>{{number_format($product_import_detail->chitietnhap_gia_nhap,0,',','.' ).' VNĐ' }}</td>
                                                                        <td>{{ $product_import_detail->Size->size }}</td>
                                                                        <td>{{number_format($product_import_detail->chitietnhap_so_luong_nhap * $product_import_detail->chitietnhap_gia_nhap ).' VNĐ' }}</td>
                                                                        <td>
                                                                            <div class="btn-group dropdown">
                                                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                                    <a class="dropdown-item" href="{{URL::to('/product-import-edit-detail/'.$product_import_detail->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Cập Nhật Chi Tiết</a>
                                                                                    <a class="dropdown-item" onclick="return confirm('Xóa chi tiết nhập?')" href="{{URL::to('/product-import-delete-detail/'.$product_import_detail->id)}}"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Xóa Chi Tiết</a>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <a href="{{URL::to('/product-import-add-detail/'.$product_import->id)}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới Chi Tiết Nhập</a>
                                                    </div>
                                                    <div class="text-lg-left mt-3 mt-lg-0">
                                                        <div class="float-left">
                                                            <p><b>Tổng Cộng :</b></p>
                                                            <h3 class="total">{{number_format($product_import->donnhaphang_tong_tien ).' VNĐ' }} </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div
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
