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
                                <a href="{{URL::to('/customer')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Khách Hàng</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Đơn Hàng Khách Hàng</li>
                        </ol>
                    </div>

                </div>
            </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
                                <h4 class="mt-3 mb-3"><span>Khách Hàng: </span></h4>
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Ảnh</th>
                                    <th class="font-weight-medium">Tên</th>
                                    <th class="font-weight-medium">Giới Tính</th>
                                    <th class="font-weight-medium">Email</th>
                                    <th class="font-weight-medium">Số Điện Thoại</th>
                                    <th class="font-weight-medium">Địa Chỉ</th>
                                    <th class="font-weight-medium">Trạng Thái</th>
                                </tr>
                                </thead>
                                <tbody class="font-14">
                                    <tr>
                                        <td>
                                            <a href="javascript: void(0);">
                                                <img src="{{asset('public/uploads/client/customer/'.$customer->khachhang_anh)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                            </a>
                                        </td>
                                        <td>
                                            {{ $customer->khachhang_ho}}  {{ $customer->khachhang_ten}}
                                        </td>
                                        <td>
                                            {{ $customer->khachhang_gioi_tinh?'Nam':'Nữ' }}
                                        </td>
                                        <td>
                                            {{ $customer->khachhang_email}}
                                        </td>
                                        <td>
                                            {{ $customer->khachhang_so_dien_thoai }}
                                        </td>
                                        <td>
                                            {{ $customer->khachhang_dia_chi }}
                                        </td>
                                        <td>
                                            {{ $customer->khachhang_trang_thai?'Hoạt động':'Không hoạt động' }}
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
                                <h4 class="mt-3 mb-3"><span>Đơn Hàng: </span></h4>
                                <thead class="bg-light">
                                    <tr>
                                        <th class="font-weight-medium">Mã Đơn Hàng</th>
                                        <th class="font-weight-medium">Tổng Cộng</th>
                                        <th class="font-weight-medium">Ghi Chú</th>
                                        <th class="font-weight-medium">Trạng Thái Giao Hàng</th>
                                        <th class="font-weight-medium">Trạng Thái Thanh Toán</th>
                                        <th class="font-weight-medium">Trạng Thái</th>
                                        <th class="font-weight-medium">Thao Tác</th>
                                    </tr>
                                    </thead>
                                    <tbody class="font-14">
                                        @foreach ($all_order_customer as $key=>$order)
                                        <tr>
                                            <td>
                                                {{ $order->dondathang_ma_don_dat_hang }}
                                            </td>
                                            
                                            <td>
                                                {{ $order->dondathang_ghi_chu }}
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
                                                {{ $order->dondathang_tinh_trang_thanh_toan?'Paid':'Unpaid' }}
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
                                                        <a class="dropdown-item" href="{{URL::to('/order-canceled/'.$order->id)}}"onclick="return confirm('Hủy đơn hàng?')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Hủy Đơn Hàng</a>
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
                    {{--  {{ $all_customer->links('layout.paginationlinks') }}  --}}
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
