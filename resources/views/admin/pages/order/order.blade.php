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
                                <a href="{{URL::to('/order-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Đơn Hàng</li>
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
                                <form class="form-inline" action="{{URL::to('/admin-search-order')}}" method="GET">
                                    <div class="form-group mr-3">
                                        <label for="status-select" class="mr-2">Từ</label>
                                        <input type="date" name="search_admin_from_day_order"  class="form-control ">
                                    </div>
                                    <div class="form-group mr-3">
                                        <label for="status-select" class="mr-2">Đến</label>
                                        <input type="date" name="search_admin_to_day_order"  class="form-control ">
                                    </div>
                                    <div class="form-group mr-3">
                                        <input type="number" min="1"
                                        @if(isset( $search_filter_admin[0]['search_admin_from_total_order']))
                                        value="{{ $search_filter_admin[0]['search_admin_from_total_order'] }}"
                                        @endif
                                        name="search_admin_from_total_order" class="form-control" placeholder="Giá từ">
                                    </div>
                                    <div class="form-group mr-3">
                                        <input type="number" min="1"
                                        @if(isset($search_filter_admin[0]['search_admin_to_total_order']))
                                        value="{{ $search_filter_admin[0]['search_admin_to_total_order'] }}"
                                        @endif
                                        name="search_admin_to_total_order" class="form-control" placeholder="Đến giá">
                                    </div>
                                    <div class="form-group mr-3 mt-3">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" class="form-control" name="search_order_keyword"
                                        @if(isset($search_filter_admin[0]['search_order_keyword']))
                                        value="{{ $search_filter_admin[0]['search_order_keyword'] }}"
                                        @endif
                                        placeholder="Từ khóa">
                                    </div>
                                    <div class="form-group mx-sm-3 mt-3">
                                        <label class="mr-2">Trạng Thái</label>
                                        <select class="custom-select" name="search_order_select_status">
                                            @if(isset($search_filter_admin[0]['search_order_select_status']))
                                                @if($search_filter_admin[0]['search_order_select_status']==-1)
                                                    <option selected="" value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Xác Nhận</option>
                                                    <option value="1">Đã Xác Nhận</option>
                                                    <option value="2">Đang Giao Hàng</option>
                                                    <option value="3">Đã Giao Hàng</option>
                                                    <option value="4">Đã Hủy</option>
                                                @elseif ($search_filter_admin[0]['search_order_select_status']==0)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option selected="" value="0">Chưa Xác Nhận</option>
                                                    <option value="1">Đã Xác Nhận</option>
                                                    <option value="2">Đang Giao Hàng</option>
                                                    <option value="3">Đã Giao Hàng</option>
                                                    <option value="4">Đã Hủy</option>
                                                @elseif ($search_filter_admin[0]['search_order_select_status']==1)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Xác Nhận</option>
                                                    <option selected="" value="1">Đã Xác Nhận</option>
                                                    <option value="2">Đang Giao Hàng</option>
                                                    <option value="3">Đã Giao Hàng</option>
                                                    <option value="4">Đã Hủy</option>
                                                @elseif ($search_filter_admin[0]['search_order_select_status']==2)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Xác Nhận</option>
                                                    <option value="1">Đã Xác Nhận</option>
                                                    <option selected="" value="2">Đang Giao Hàng</option>
                                                    <option value="3">Đã Giao Hàng</option>
                                                    <option value="4">Đã Hủy</option>
                                                @elseif ($search_filter_admin[0]['search_order_select_status']==3)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Xác Nhận</option>
                                                    <option value="1">Đã Xác Nhận</option>
                                                    <option value="2">Đang Giao Hàng</option>
                                                    <option selected="" value="3">Đã Giao Hàng</option>
                                                    <option value="4">Đã Hủy</option>
                                                @elseif ($search_filter_admin[0]['search_order_select_status']==4)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Xác Nhận</option>
                                                    <option value="1">Đã Xác Nhận</option>
                                                    <option value="2">Đang Giao Hàng</option>
                                                    <option value="3">Đã Giao Hàng</option>
                                                    <option selected="" value="4">Đã Hủy</option>
                                                @endif
                                            @else
                                                <option selected="" value="-1"> Tất Cả</option>
                                                <option value="0">Chưa Xác Nhận</option>
                                                <option value="1">Đã Xác Nhận</option>
                                                <option value="2">Đang Giao Hàng</option>
                                                <option value="3">Đã Giao Hàng</option>
                                                <option value="4">Đã Hủy</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group mx-sm-3 mt-3">
                                        <button type="submit" class="btn btn-success waves-effect waves-light search-admin-order">Tìm</button>
                                    </div>
                                    <div class="form-group mt-3">
                                        <a href="{{URL::to('/order')}}" class="btn btn-success waves-effect waves-light">Tất Cả</a>
                                    </div>
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
                                    <th class="font-weight-medium">Mã Đơn Hàng</th>
                                    <th class="font-weight-medium">Ngày Đặt </th>
                                    <th class="font-weight-medium">Tổng Cộng</th>
                                    <th class="font-weight-medium">Ghi Chú</th>
                                    <th class="font-weight-medium">Trạng Thái Giao Hàng</th>
                                    <th class="font-weight-medium">Trạng Thái Thanh Toán</th>
                                    <th class="font-weight-medium">Trạng Thái</th>
                                    <th class="font-weight-medium">Thao Tác</th>
                                </tr>
                                </thead>
                                <tbody class="font-14">
                                    @foreach ($all_order as $key=>$order)
                                    <tr>
                                        <td>
                                            {{ $order->dondathang_ma_don_dat_hang }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime( $order->dondathang_ngay_dat_hang)) }}
                                        </td>
                                       
                                        <td>
                                            {{ $order->dondathang_ghi_chu?$order->dondathang_ghi_chu:'Không có ghi chú' }}
                                        </td>
                                        <td>
                                            @if($order->dondathang_trang_thai==2)
                                                Đang giao hàng
                                            @elseif($order->dondathang_trang_thai==3)
                                                Đã giao hàng
                                            @elseif($order->dondathang_trang_thai==4)
                                                Đơn hàng đã bị hủy
                                            @else
                                                Chưa giao hàng
                                            @endif
                                        </td>
                                        <td>
                                            {{ $order->dondathang_tinh_trang_thanh_toan?'Đã thanh toán':'Chưa thanh toán' }}
                                        </td>

                                        <td>
                                            @if($order->dondathang_trang_thai == 0)
                                            Chưa xác nhận
                                            @elseif($order->dondathang_trang_thai == 1)
                                            Đã xác nhận
                                            @elseif($order->dondathang_trang_thai == 2)
                                            Đang vận chuyển
                                            @elseif($order->dondathang_trang_thai == 3)
                                            Đã giao hàng
                                            @elseif($order->dondathang_trang_thai == 4)
                                            Đơn hàng đã bị hủy
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{URL::to('/order-show-detail/'.$order->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Chi Tiết</a>
                                                    @if($order->dondathang_trang_thai!=3 && $order->dondathang_trang_thai!=2 && $order->dondathang_trang_thai!=4)
                                                    <a class="dropdown-item" href="{{URL::to('/order-canceled/'.$order->id)}}"onclick="return confirm('Hủy Đơn Hàng?')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Hủy Đơn Hàng</a>
                                                    @endif
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
                        {{ $all_order->links('layout.paginationlinks') }}
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