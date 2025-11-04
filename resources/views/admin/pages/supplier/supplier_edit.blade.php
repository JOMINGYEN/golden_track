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
                                <a href="{{URL::to('/supplier')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại</a>
                                <a href="{{URL::to('/supplier-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Cập Nhật Nhà Cung Cấp</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Thông Tin Nhà Cung Cấp</h4>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">
                                         <form action="{{ URL::to('/supplier-save-edit/'.$supplier->id) }}" class="form-horizontal" enctype="multipart/form-data" role="form"  method="post" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                                            {{ csrf_field() }}
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Thông Tin Nhà Cung Cấp</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Tên</label>
                                                    <input type="text" value="{{ $supplier->nhacungcap_ten }}" required="" name="supplier_name"  class="form-control" placeholder="Tên">
                                                    @error('supplier_name')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                    <label class="col-form-label">Địa Chỉ</label>
                                                    <input type="text" value="{{ $supplier->nhacungcap_dia_chi }}" required="" name="supplier_address" class="form-control" placeholder="Địa chỉ">
                                                    @error('supplier_address')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                    <label class="col-form-label">Số Điện Thoại</label>
                                                    <input type="number" value="{{ $supplier->nhacungcap_so_dien_thoai }}" required="" name="supplier_phone_number" class="form-control" placeholder="Số điện thoại">
                                                    @error('supplier_phone_number')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                    <label class="col-form-label">Email</label>
                                                    <input type="text" value="{{ $supplier->nhacungcap_email }}" required="" name="supplier_email"  class="form-control" placeholder="Email">
                                                    @error('supplier_email')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Ảnh</label>
                                                <div class="col-sm-10">
                                                    <div class="user-image mb-3 text-center">
                                                        <div class="imgPreview" >

                                                        </div>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" class="upload custom-file-input" value="{{ $supplier->nhacungcap_anh }}" required="" accept=".jpeg,.png,.gif,.jpg" name="supplier_img" id="images">
                                                        <label class="custom-file-label" for="images">Chọn Ảnh</label>
                                                        @error('supplier_img')
                                                        <p class="alert alert-danger"> {{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <label class="col-form-label mt-3">Ảnh</label>
                                                    <img class=" mt-3" width="100px" height="100px" id="image" src="{{asset('public/uploads/admin/supplier/'.$supplier->nhacungcap_anh)}}" />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" for="example-email">Trạng Thái</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Trạng Thái</label>
                                                    <select name="supplier_status" class="form-control">

                                                        @if(  $supplier->nhacungcap_trang_thai ==1)
                                                            <option selected value="1">Hiển Thị</option>
                                                            <option value="0">Ẩn</option>
                                                        @else
                                                            <option value="1">Hiển Thị</option>
                                                            <option selected value="0">Ẩn</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <button type="submit" class="btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Luu</button>
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
