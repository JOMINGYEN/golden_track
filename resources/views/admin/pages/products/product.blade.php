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
                                <a href="{{URL::to('/product-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Sản Phẩm</li>
                        </ol>
                    </div>

                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                     <form class="form-inline" action="{{URL::to('/admin-search-product')}}" method="GET">
                                        <div class="form-group mr-3">
                                            <input type="number" min="1"
                                            @if(isset( $search_filter_admin[0]['search_admin_from_price_product']))
                                            value="{{ $search_filter_admin[0]['search_admin_from_price_product'] }}"
                                            @endif
                                            name="search_admin_from_price_product" class="form-control" placeholder="Giá từ">
                                        </div>
                                        <div class="form-group mr-3">
                                            <input type="number" min="1"
                                            @if(isset( $search_filter_admin[0]['search_admin_to_price_product']))
                                            value="{{ $search_filter_admin[0]['search_admin_to_price_product'] }}"
                                            @endif
                                            name="search_admin_to_price_product" class="form-control" placeholder="Đến giá">
                                        </div>
                                        <div class="form-group mr-3">
                                            <label for="inputPassword2" class="sr-only">Search</label>
                                            <input type="search" class="form-control" name="search_product_keyword"
                                            @if(isset( $search_filter_admin[0]['search_product_keyword']))
                                            value="{{ $search_filter_admin[0]['search_product_keyword'] }}"
                                            @endif
                                            placeholder="Từ khóa">
                                        </div>
                                    <div class="form-group mx-sm-3">
                                        <button type="submit" class="btn btn-success waves-effect waves-light search-admin-order">Tìm</button>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{URL::to('/product')}}" class="btn btn-success waves-effect waves-light">Tất Cả</a>
                                    </div>
                                    </form>
                                </div>
                               <!-- end col-->
                            </div> <!-- end row -->
                        </div> <!-- end card-box -->
                    </div><!-- end col-->
                </div>
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
                                    <th class="font-weight-medium">Loại</th>
                                    <th class="font-weight-medium">Dòng</th>
                            
                                    <th class="font-weight-medium">Trạng Thái</th>
                                    <th class="font-weight-medium">Thao Tác</th>
                                </tr>
                                </thead>

                                <tbody class="font-14">
                                    @foreach ($all_product as $key=>$product)
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
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{URL::to('/product-edit/'.$product->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Cập Nhật</a>
                                                    <a class="dropdown-item" href="{{URL::to('/product-images/'.$product->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Thêm Ảnh</a>
                                                    <a class="dropdown-item" href="{{URL::to('/product-delete/'.$product->id)}}" onclick="return confirm('Xóa sản phẩm?')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Xóa</a>
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
                    {{ $all_product->links('layout.paginationlinks') }}
                </ul>
            </nav>
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