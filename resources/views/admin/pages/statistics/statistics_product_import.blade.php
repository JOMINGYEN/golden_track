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
                            <li class="breadcrumb-item active">Thống Kê Nhập</li>
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
                                <form action="{{url('export-import-xlsx')}}" method="POST" class="form-inline mt-2">
                                    @csrf
                                    <div class="form-group mr-3">
                                        <label for="status-select" class="mr-2">Từ Ngày</label>
                                        <input type="date" name="search_from_day_statistical_product_import" id="search_from_day_statistical_product_import" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="status-select" class="mr-2">Đến Ngày</label>
                                        <input type="date" name="search_to_day_statistical_product_import" id="search_to_day_statistical_product_import" class="form-control">
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <a type="button" class="btn btn-success waves-effect waves-light clear-search-statistical-product-import">Đặt Lại</a>
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <label class="mr-2">Ngày</label>
                                        <select class="custom-select search-type-statistical-product-import">
                                            <option selected="" value="0"> Tất Cả</option>
                                            <option value="1">Ngày</option>
                                            <option value="2">Tuần-7-Ngày</option>
                                            <option value="3">Tháng-30-Ngày</option>
                                            <option value="4">Quý - 120 - Ngày</option>
                                            <option value="5">Năm - 365 - Ngày</option>
                                        </select>
                                    </div>
                                    <input type="submit" value="Xuất File Excel" onclick="return confirm('Xuất File Excel?')" name="export_import_xlsx" class="btn btn-success mt-2">
                                </form>
                            </div>
                           <!-- end col-->
                        </div> <!-- end row -->
                    </div> <!-- end card-box -->
                </div><!-- end col-->
            </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box show_search_import_product_statistics">
                            <label class="col-form-label"> <h4>Thống Kê</h4></label>
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Tổng Cộng</th>
                                    <th class="font-weight-medium">Số Đơn Nhập</th>
                                    <th class="font-weight-medium">Số Sản Phẩm</th>
                                    <th class="font-weight-medium">Số Lượng Nhập</th>
                                </tr>
                                </thead>
                                <tbody class="font-14 " >

                                    <tr>
                                        <td>
                                            {{number_format( $sum_total_import ,0,',','.' ).' VNĐ' }}
                                        </td>
                                        <td>
                                            {{ number_format( $count_import ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{ number_format( $count_detail ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{ number_format( $sum_detail ,0,',','.' ) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <label class="col-form-label"> <h4>Đơn Nhập</h4></label>
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Mã Đơn Nhập</th>
                                    <th class="font-weight-medium">Ngày Nhập</th>
                                    <th class="font-weight-medium">Nhà Cung Cấp</th>
                                    <th class="font-weight-medium">Tổng Cộng</th>
                                </tr>
                                </thead>
                                <tbody class="font-14 " >
                                    @foreach ($all_product_import_statistics as $key=>$product_import)
                                    <tr>
                                        <td>
                                            {{ $product_import->donnhaphang_ma_don_nhap_hang }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime( $product_import->donnhaphang_ngay_nhap)) }}
                                        </td>
                                        <td>
                                           {{ $product_import->Supplier->nhacungcap_ten }}
                                        </td>
                                        <td>
                                            {{number_format( $product_import->donnhaphang_tong_tien ,0,',','.' ).' VNĐ' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination pagination-rounded mb-3">
                                    {{ $all_product_import_statistics->links('layout.paginationlinks') }}
                                </ul>
                            </nav>
                            {{--  <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                                <h4 class="mt-3 mb-3"><span>Product: </span></h4>
                                <thead class="bg-light">
                                    <tr>
                                        <th class="font-weight-medium">No.</th>
                                        <th class="font-weight-medium">Day</th>
                                        <th class="font-weight-medium">Product Name</th>
                                        
                                        <th class="font-weight-medium">Import Price</th>
                                        <th class="font-weight-medium">Quantity</th>
                                        <th class="font-weight-medium">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="font-14 show_views_type_search" >
                                    @foreach ($all_import_detail as $key=>$product_import_detail)
                                    <tr>
                                        <td>
                                            {{$product_import_detail->chitietnhap_ma_don_nhap_hang  }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime( $product_import_detail->ProductImport->donnhaphang_ngay_nhap))}}
                                        </td>
                                        <td>
                                            {{ $product_import_detail->Product->sanpham_ten }}
                                        </td>
                                        <td>
                                            {{$product_import_detail->Size->size}}
                                        </td>
                                        <td>
                                            {{ number_format( $product_import_detail->chitietnhap_gia_nhap ,0,',','.' ).' VNĐ' }}
                                        </td>
                                        <td>
                                            {{ $product_import_detail->chitietnhap_so_luong_nhap }}
                                        </td>
                                        <td>
                                            {{ number_format( $product_import_detail->chitietnhap_so_luong_nhap* $product_import_detail->chitietnhap_gia_nhap,0,',','.' ).' VNĐ' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>  --}}
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
