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
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Thống Kê Tồn Kho</li>
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
                                <form class="form-inline">
                                    @csrf
                                    <div class="form-group mr-3">
                                        <label for="status-select" class="mr-2">Sản Phẩm</label>
                                        <input type="search" id="search_name_statistics_product_in_stock" class="form-control search_name_statistics_product_in_stock">
                                    </div>
                                    <div class="form-group">
                                        <label for="status-select" class="mr-2">Size</label>
                                        
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <a type="button" class="btn btn-success waves-effect waves-light clear-search-statistics-product-in-stock">Đặt Lại</a>
                                    </div>
                                </form>
                                <form action="{{url('export-in-stock-xlsx')}}" method="POST" class="form-inline mt-2">
                                    @csrf
                                    <input type="submit" value="Xuất File Excel" onclick="return confirm('Xuất File Excel?')" name="export_in_stock_xlsx" class="btn btn-success">
                                </form>
                            </div>
                           <!-- end col-->
                        </div> <!-- end row -->
                    </div> <!-- end card-box -->
                </div><!-- end col-->
            </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Ảnh</th>
                                    <th class="font-weight-medium">Sản Phẩm</th>
                                    
                                    <th class="font-weight-medium">Số Lượng Tồn</th>
                                    <th class="font-weight-medium">Số Lượng Đã Bán</th>
                                </tr>
                                </thead>
                                <tbody class="font-14 show_in_stock_search" >
                                    @foreach ($all_product_in_stock_statistics as $key=>$product_in_stock)
                                    <tr>
                                        <td>
                                            <a href="javascript: void(0);">
                                                <img src="{{asset('public/uploads/admin/product/'.$product_in_stock->Product->sanpham_anh)}}" alt="contact-img" title="contact-img" class="avatar-lg rounded-circle img-thumbnail">
                                            </a>
                                        </td>
                                        <td>
                                            {{ $product_in_stock->Product->sanpham_ten }}
                                        </td>
                                        
                                        <td>
                                            {{number_format( $product_in_stock->sanphamtonkho_so_luong_ton ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{number_format( $product_in_stock->sanphamtonkho_so_luong_da_ban ,0,',','.' ) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination pagination-rounded mb-3">
                                    {{ $all_product_in_stock_statistics->links('layout.paginationlinks') }}
                                </ul>
                            </nav>
                        </div>
                    </div><!-- end col -->
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
