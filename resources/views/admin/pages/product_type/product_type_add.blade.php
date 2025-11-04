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
                                <a href="{{URL::to('/product-type')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lai Loại Sản Phẩm</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Thêm Loại Sản Phẩm</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title">Thông Tin Loại Sản Phẩm</h4>
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
                                    <form action="{{ URL::to('product-type-save') }}" enctype="multipart/form-data" class="form-horizontal" role="form"  method="post" >
                                        {{ csrf_field() }}
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" >Thông Tin Loại Sản Phẩm</label>
                                            <div class="col-sm-10">
                                                <label class="col-form-label">Tên</label>
                                                <input type="text" name="product_type_name" required="" class="form-control" placeholder="Tên loại">
                                                @error('product_type_name')
                                                <p class="alert alert-danger"> {{ $message }}</p>
                                                @enderror
                                                <label class="col-form-label">Mô Tả</label>
                                                <textarea name="product_type_description" required="" class="form-control" placeholder="Mô Tả"></textarea>
                                                @error('product_type_description')
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
                                                    <input type="file" class="upload custom-file-input" required="" accept=".jpeg,.png,.gif,.jpg" name="product_type_img" id="images">
                                                    <label class="custom-file-label" for="images">Chọn Ảnh</label>
                                                    @error('product_type_img')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label" for="example-email">Trạng Thái</label>
                                            <div class="col-sm-10">
                                                <label class="col-form-label">Trạng Thái</label>
                                                <select name="product_type_status" class="form-control">
                                                    <option value="1">Hiển Thị</option>
                                                    <option value="0">Ẩn</option>
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
        </div><!-- end col -->

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
<script src="{{asset('public/backend/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('public/backend/js/pages/form-fileuploads.init.js')}}"></script>
@endsection
