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
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Thêm Sản Phẩm</li>
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
                                        <form action="{{ URL::to('/product-save') }}" enctype="multipart/form-data"class="form-horizontal" role="form"  method="post">
                                            {{ csrf_field() }}
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Thông Tin Sản Phẩm</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Mã Sản Phẩm</label>
                                                    <input type="text" name="product_code"  class="form-control" required="" placeholder="Mã sản phẩm">
                                                    <label class="col-form-label">Tên Sản Phẩm</label>
                                                    <input type="text" name="product_name" class="form-control" required="" placeholder="Tên sản phẩm">
                                                   
                                                    
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label  class="col-form-label">Nơi Sản Xuất</label>
                                                            <input type="text" name="product_production" required="" class="form-control"  placeholder="Nơi sản xuất">
                                                        </div>
                                                        

                                                        
                                                        <div class="form-group col-md-6">
                                                            <label  class="col-form-label">Chất Liệu</label>
                                                            <input type="text" name="product_material" required=""  class="form-control"  placeholder="Chất liệu">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label  class="col-form-label">Bảo Hành</label>
                                                            <input type="text" name="product_guarantee" required="" class="form-control"  placeholder="Bảo hành">
                                                        </div>
                                                    </div>
                                                    <label  class="col-form-label">Tính Năng</label>
                                                    <textarea name="product_feature" required="" class="form-control" placeholder="Tính năng"></textarea>
                                                    <label class="col-form-label">Mô Tả</label>
                                                    <textarea class="form-control" name="product_description" required=""  placeholder="Mô tả"></textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Loại Sản Phẩm</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Loại</label>
                                                    <select name="product_type" class="form-control">
                                                        @foreach ($product_type as $key => $pro_type)
                                                            <option value="{{ $pro_type->id }}">{{ $pro_type->loaisanpham_ten }}</option>
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
                                                            <option value="{{ $brand->id }}">{{ $brand->thuonghieu_ten }}</option>
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
                                                            <option value="{{ $collection->id }}">{{ $collection->dongsanpham_ten }}</option>
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
                                                        <input type="file" class="upload custom-file-input" required="" accept=".jpeg,.png,.gif,.jpg" name="product_img" id="images">
                                                        <label class="custom-file-label" for="images">Chọn Ảnh</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Trạng Thái</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Trạng Thái</label>
                                                    <select name="product_status" class="form-control">
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
