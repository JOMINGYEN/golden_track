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
                                <a href="{{URL::to('/product')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Sản Phẩm</a>
                                <a href="{{URL::to('/product-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Cập Nhật Sản Phẩm</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Thông Tin Sản Phẩm</h4>
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
                                        <form action="{{ URL::to('/product-save-edit/'.$product->id) }}" enctype="multipart/form-data"class="form-horizontal" role="form"  method="post" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                                            {{ csrf_field() }}
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Thông Tin Sản Phẩm</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Mã Sản Phẩm</label>
                                                    <input type="text" value="{{$product->sanpham_ma_san_pham }}" name="product_code" required="" class="form-control" placeholder="Example: SP01">
                                                    <label class="col-form-label">Tên Sản Phẩm</label>
                                                    <input type="text" value="{{$product->sanpham_ten }}" name="product_name" required="" class="form-control" placeholder="Example: AF1,..">
                                                    
                                                    
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                          
                                                          
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label  class="col-form-label">Nơi Sản Xuất</label>
                                                            <input type="text" value="{{$product->sanpham_noi_san_xuat }}" required="" name="product_production"  class="form-control"  placeholder="Nơi sản xuất">
                                                        </div>
                                                        

                                                       
                                                        <div class="form-group col-md-6">
                                                            <label  class="col-form-label">Chất Liệu</label>
                                                            <input type="text" value="{{$product->sanpham_chat_lieu }}" required="" name="product_material"  class="form-control"  placeholder="Chất liệu">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label  class="col-form-label">Bảo Hành</label>
                                                            <input type="text" value="{{$product->sanpham_bao_hanh }}" required="" name="product_guarantee" class="form-control"  placeholder="Bảo hành">
                                                        </div>
                                                    </div>
                                                    <label  class="col-form-label">Tính Năng</label>
                                                    <textarea name="product_feature" class="form-control" required="" placeholder="Tính năng">{{$product->sanpham_tinh_nang }}</textarea>
                                                    <label class="col-form-label">Mô Tả</label>
                                                    <textarea class="form-control" required="" name="product_description" placeholder="Mô tả">{{$product->sanpham_mo_ta }}</textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Loại Sản Phẩm</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Loại</label>
                                                    <select name="product_type" class="form-control">
                                                        @foreach ($product_type as $key => $pro_type)
                                                            @if($pro_type->id==$product->loaisanpham_id)
                                                                <option selected value="{{ $pro_type->id }}">{{ $pro_type->loaisanpham_ten }}</option>
                                                            @else
                                                                <option value="{{ $pro_type->id }}">{{ $pro_type->loaisanpham_ten }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Thương Hiệu</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Thương Hiệu</label>
                                                    <select name="brand" class="form-control">
                                                        @foreach ($product_brand as $key => $brand)
                                                            @if($brand->id==$product->thuonghieu_id)
                                                                <option selected value="{{ $brand->id }}">{{ $brand->thuonghieu_ten }}</option>
                                                            @else
                                                                <option value="{{ $brand->id }}">{{ $brand->thuonghieu_ten }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Dòng Sản Phẩm</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Dòng</label>
                                                    <select name="collection" class="form-control">
                                                        @foreach ($product_collection as $key => $collection)
                                                            @if($collection->id==$product->dongsanpham_id)
                                                                <option selected value="{{ $collection->id }}">{{ $collection->dongsanpham_ten }}</option>
                                                            @else
                                                                <option value="{{ $collection->id }}">{{ $collection->dongsanpham_ten }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>

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
                                                        <input type="file" class="upload custom-file-input" accept=".jpeg,.png,.gif,.jpg" value="{{ $product->sanpham_anh }}" name="product_img" id="images">
                                                        <label class="custom-file-label" for="images">Chọn Ảnh</label>
                                                    </div>
                                                    <label class="col-form-label mt-3">Ảnh</label>
                                                    <img class=" mt-3" width="100px" height="100px" id="image" src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" />
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Trạng Thái</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Trạng Thái</label>
                                                    <select name="product_status" class="form-control">
                                                        @if ($product->sanpham_trang_thai==0)
                                                        <option value="1">Hiển Thị</option>
                                                        <option selected value="0">Ẩn</option>
                                                        @else
                                                        <option selected value="1">Hiển Thị</option>
                                                        <option value="0">Ẩn</option>
                                                        @endif
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
{{-- form collection
<form action="">
    <div id="con-close-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Collection</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Name</label>
                                <input type="text" class="form-control" id="field-1" placeholder="Run">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                            <label class="col-form-label">Description</label>
                            <textarea class="form-control" placeholder="Des.."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info waves-effect waves-light">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
</form>
{{-- end form colleciton --}}
{{-- form brand --}}
<form action="">
    <div id="con-close-modal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Brand</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Name</label>
                                <input type="text" class="form-control" id="field-1" placeholder="Adidas">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                            <label class="col-form-label">Description</label>
                            <textarea class="form-control" placeholder="Des.."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info waves-effect waves-light">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
</form>

{{-- end form brand --}}

{{-- form product type --}}
<form action="">
    <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Product Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Name</label>
                                <input type="text" class="form-control" id="field-1" placeholder="AF1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                            <label class="col-form-label">Description</label>
                            <textarea class="form-control" placeholder="Des.."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info waves-effect waves-light">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

</form> --}}
@endsection
