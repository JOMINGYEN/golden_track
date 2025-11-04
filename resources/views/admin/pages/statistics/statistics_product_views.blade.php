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
                            <li class="breadcrumb-item active">Thống Kê Lượt Xem Sản Phẩm</li>
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
                                <form action="{{url('export-views-xlsx')}}" method="POST" class="form-inline mt-2">
                                    @csrf
                                    <div class="form-group mr-3">
                                        <label for="status-select" class="mr-2">Từ Ngày</label>
                                        <input type="date" name="search_from_day_views" id="search_from_day_views" class="form-control search_from_day_views">
                                    </div>
                                    <div class="form-group">
                                        <label for="status-select" class="mr-2">Đến Ngày</label>
                                        <input type="date" name="search_to_day_views" id="search_to_day_views" class="form-control search_to_day_views">
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <a type="button" class="btn btn-success waves-effect waves-light clear-search-views">Đặt Lại</a>
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <label class="mr-2">Ngày</label>
                                        <select class="custom-select search-view-select">
                                            <option selected="" value="0"> Tất Cả</option>
                                            <option value="1">Ngày</option>
                                            <option value="2">Tuần-7-Ngày</option>
                                            <option value="3">Tháng-30-Ngày</option>
                                            <option value="4">Quý - 120 - Ngày</option>
                                            <option value="5">Năm - 365 - Ngày</option>
                                        </select>
                                    </div>
                                    <input type="submit" value="Xuất File Excel" onclick="return confirm('Xuất File Excel?')" name="export_views_xlsx" class="btn btn-success mt-2">
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
                                    <th class="font-weight-medium">Lượt Xem Trong Ngày</th>
                                    <th class="font-weight-medium">Ngày</th>
                                    <th class="font-weight-medium">Tổng Lượt Xem</th>
                                </tr>
                                </thead>
                                <tbody class="font-14 show_views_type_search" >
                                    @foreach ($all_product_views as $key=>$views)
                                    <tr>
                                        <td>
                                            <a href="javascript: void(0);">
                                                <img src="{{asset('public/uploads/admin/product/'.$views->Product->sanpham_anh)}}" alt="contact-img" title="contact-img" class="avatar-lg rounded-circle img-thumbnail">
                                            </a>
                                        </td>
                                        <td>
                                            {{ $views->Product->sanpham_ten }}
                                        </td>
                                        <td>
                                            {{ $views->viewssanpham_views }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($views->viewssanpham_ngay_xem )) }}
                                        </td>
                                        @foreach ($total_view_array as $k=>$sum_views)
                                            @if($sum_views['product_id']==$views->sanpham_id)
                                                <td>{{ $sum_views['sum_view'] }}</td>
                                                @break
                                            @endif
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination pagination-rounded mb-3">
                                    {{ $all_product_views->links('layout.paginationlinks') }}
                                </ul>
                            </nav>
                        </div>
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