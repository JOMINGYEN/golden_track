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
                            <li class="breadcrumb-item active">Thống Kê Đơn Hàng</li>
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
                                <form action="{{url('export-order-xlsx')}}" method="POST" class="form-inline mt-2">
                                    @csrf
                                    <div class="form-group mr-3">
                                        <label for="status-select" class="mr-2">Từ Ngày</label>
                                        <input type="date" name="search_from_day_statistical_order" id="search_from_day_statistical_order" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="status-select" class="mr-2">Đến Ngày</label>
                                        <input type="date" name="search_to_day_statistical_order" id="search_to_day_statistical_order" class="form-control">
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <a type="button" class="btn btn-success waves-effect waves-light clear-search-statistical-order">Đặt Lại</a>
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <label class="mr-2">Ngày</label>
                                        <select class="custom-select search-type-statistical-order">
                                            <option selected="" value="0"> Tất Cả</option>
                                            <option value="1">Ngày</option>
                                            <option value="2">Tuần-7-Ngày</option>
                                            <option value="3">Tháng-30-Ngày</option>
                                            <option value="4">Quý - 120 - Ngày</option>
                                            <option value="5">Năm - 365 - Ngày</option>
                                        </select>
                                    </div>
                                    <input type="submit" value="Xuất File Excel" onclick="return confirm('Xuất File Excel?')" name="export_order_xlsx" class="btn btn-success mt-2">
                                </form>
                            </div>
                           <!-- end col-->
                        </div> <!-- end row -->
                    </div> <!-- end card-box -->
                </div><!-- end col-->
            </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box show_search_order_statistics">
                            <label class="col-form-label"> <h4>Thống Kê Doanh Thu</h4></label>
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Doanh Thu + Phí Vận Chuyển</th>
                                    <th class="font-weight-medium">Doanh Thu</th>
                                    <th class="font-weight-medium">Tổng Đã Bán</th>
                                    <th class="font-weight-medium">Tổng Đơn Hàng</th>
                                    <th class="font-weight-medium">Số Sản Phẩm</th>
                                    <th class="font-weight-medium">Số Lượng Đã Bán</th>
                                </tr>
                                </thead>
                                <tbody class="font-14 " >

                                    <tr>
                                        <td>
                                            {{number_format($sum_total_order_success - $sum_total_import,0,',','.' ).' VNĐ' }}
                                        </td>
                                        <td>
                                            {{number_format($sum_total_order_success - $sum_total_import - $sum_total_fee_success,0,',','.' ).' VNĐ' }}
                                        </td>
                                        <td>
                                            {{number_format( $sum_total_order_success ,0,',','.' ).' VNĐ' }}
                                        </td>
                                        <td>
                                            {{number_format( $sum_total_order ,0,',','.' ).' VNĐ' }}
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
                            <label class="col-form-label"> <h4>Thống Kê Đơn Hàng</h4></label>
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Số Đơn Hàng</th>
                                    <th class="font-weight-medium">Đơn Hàng Chưa Xác Nhận</th>
                                    <th class="font-weight-medium">Đơn Hàng Đã Xác Nhận</th>
                                    <th class="font-weight-medium">Đơn Hàng Đang Vận Chuyển</th>
                                    <th class="font-weight-medium">Đơn Hàng Đã Giao</th>
                                    <th class="font-weight-medium">Đơn Hàng Đã Hủy</th>
                                </tr>
                                </thead>
                                <tbody class="font-14 " >

                                    <tr>
                                        <td>
                                            {{ number_format( $count_order ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{ number_format( $count_order_unconfirmed ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{ number_format( $count_order_confirmed ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{ number_format( $count_order_in_transit ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{ number_format( $count_order_delivered ,0,',','.' ) }}
                                        </td>
                                        <td>
                                            {{ number_format( $count_order_cancel ,0,',','.' ) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <label class="col-form-label"> <h4>Đơn Hàng</h4></label>
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 " cellspacing="0" id="tickets-table">
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Mã Đơn Hàng</th>
                                    <th class="font-weight-medium">Ngày Đặt Hàng</th>
                                    <th class="font-weight-medium">Khách Hàng</th>
                                    <th class="font-weight-medium">Tổng Cộng</th>
                                    <th class="font-weight-medium">Trạng Thái</th>
                                </tr>
                                </thead>
                                <tbody class="font-14" >
                                    @foreach ($all_order_statistics as $key=>$order)
                                    <tr>
                                        <td>
                                            {{ $order->dondathang_ma_don_dat_hang }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime( $order->dondathang_ngay_dat_hang)) }}
                                        </td>
                                        <td>
                                           {{ $order->Customer->khachhang_ho }} {{ $order->Customer->khachhang_ten }}
                                        </td>
                                        <td>
                                            {{number_format( $order->dondathang_tong_tien ,0,',','.' ).' VNĐ' }}
                                        </td>
                                        <td>
                                            @if($order->dondathang_trang_thai==0)
                                            Chưa Xác Nhận
                                            @elseif($order->dondathang_trang_thai==1)
                                            Đã Xác Nhận
                                            @elseif($order->dondathang_trang_thai==2)
                                            Đang Giao Hàng
                                            @elseif($order->dondathang_trang_thai==3)
                                            Đã Giao Hàng
                                            @elseif($order->dondathang_trang_thai==4)
                                           Đơn Hàng Đã Bị Hủy
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination pagination-rounded mb-3">
                                    {{ $all_order_statistics->links('layout.paginationlinks') }}
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
                                    @foreach ($all_import_detail as $key => $product_import_detail)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $product_import_detail->ProductImport?->donnhaphang_ngay_nhap ? date('d-m-Y', strtotime($product_import_detail->ProductImport->donnhaphang_ngay_nhap)) : 'N/A' }}</td>
                                            <td>{{ $product_import_detail->Product?->sanpham_ten ?? 'N/A' }}</td>
                                            <td>{{ $product_import_detail?->Product?->Size?->size ?? 'N/A' }}</td>
                                            <td>{{ isset($product_import_detail->chitietnhap_gia_nhap) ? number_format($product_import_detail->chitietnhap_gia_nhap,0,',','.') . ' VNĐ' : 'N/A' }}</td>
                                            <td>{{ isset($product_import_detail->chitietnhap_so_luong_nhap) ? $product_import_detail->chitietnhap_so_luong_nhap : '0' }}</td>
                                            <td>
                                                @php
                                                    $total = ($product_import_detail->chitietnhap_gia_nhap ?? 0) * ($product_import_detail->chitietnhap_so_luong_nhap ?? 0);
                                                @endphp
                                                {{ number_format($total,0,',','.') . ' VNĐ' }}
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