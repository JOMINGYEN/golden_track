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
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Ảnh Sản Phẩm</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
                <!-- end row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
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
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Ảnh</th>
                                    <th class="font-weight-medium">Mã Sản Phẩm</th>
                                    <th class="font-weight-medium">Sản Phẩm</th>
                                    <th class="font-weight-medium">Thương Hiệu</th>
                                    <th class="font-weight-medium">Loại Sản Phẩm</th>
                                    <th class="font-weight-medium">Dòng Sản Phẩm</th>
                                  
                                    <th class="font-weight-medium">Trạng Thái</th>
                                </tr>
                                </thead>

                                <tbody class="font-14">
                                    <tr>
                                        <td>
                                            <a href="javascript: void(0);">
                                                <img src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                            </a>
                                        </td>
                                        <td>
                                            {{ $product->sanpham_ma_san_pham }}
                                        </td>
                                        <td>
                                            {{ $product->sanpham_ten }}
                                        </td>
                                        <td>
                                            {{ $product->brand->thuonghieu_ten }}
                                        </td>
                                        <td>
                                            {{ $product->producttype->loaisanpham_ten }}
                                        </td>
                                        <td>
                                            {{ $product->collection->dongsanpham_ten }}
                                        </td>
                                       
                                        <td>
                                            <span class="badge">
                                                <?php
                                                if($product->sanpham_trang_thai==1)
                                                { ?>
                                                <a href="{{URL::to ('/unactive-product/'.$product->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-up"></i></a>
                                                <?php
                                                }else
                                                { ?>
                                                    <a href="{{URL::to ('/active-product/'.$product->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-down"></i></a>
                                                <?php
                                                }
                                                ?>
                                               </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div><!-- end col -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Sản Phẩm</th>
                                    <th class="font-weight-medium">Tên Ảnh</th>
                                    <th class="font-weight-medium">Ảnh</th>
                                    <th class="font-weight-medium">Thao Tác</th>
                                </tr>
                                </thead>

                                <tbody class="font-14">
                                    @foreach ($all_product_images as $key=>$product_image)
                                    <tr>
                                        <td>
                                            {{ $product_image->Product->sanpham_ten }}
                                        </td>
                                        <td>
                                            {{ $product_image->anhsanpham_ten }}
                                        </td>
                                        <td>
                                            <a href="javascript: void(0);">
                                                <img src="{{asset('public/uploads/admin/productimages/'.$product_image->anhsanpham_ten)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{URL::to('/product-images-delete/'.$product_image->id)}}" onclick="return confirm('Xóa Ảnh?')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Xóa</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->
                <nav>
                <ul class="pagination pagination-rounded mb-3">
                    {{ $all_product_images->links('layout.paginationlinks') }}
                </ul>
            </nav>
            <div class="col-12">
                <div class="card-box">
                    <h4 class="header-title">Add Image</h4>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                <form action="{{ URL::to('/product-image-add/'.$product->id)}}" class="form-horizontal" enctype="multipart/form-data"  method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="user-image mb-3 text-center">
                                            <div class="imgPreview" >

                                            </div>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="product_image[]" class="custom-file-input" accept=".jpeg,.png,.gif,.jpg" id="images" multiple="multiple">
                                            <label class="custom-file-label" for="images">Chọn Ảnh</label>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                                            Thêm Ảnh
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end col-->
                </div>
                <!-- end row -->
            </div>
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
