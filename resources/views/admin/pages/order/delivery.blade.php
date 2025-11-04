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
                                <a href="{{URL::to('/order-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Đơn Hàng</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Giao Hàng</li>
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
                                <form class="form-inline" action="{{URL::to('/admin-search-delivery')}}" method="GET">
                                    <div class="form-group">
                                        <input type="search" class="form-control" name="search_delivery_keyword"
                                        @if(isset($search_keyword))
                                        value="{{ $search_keyword }}"
                                        @endif
                                        placeholder="Từ khóa">
                                    </div>
                                     <div class="form-group mx-sm-3">
                                        <label class="mr-2">Trạng Thái</label>
                                        <select class="custom-select" name="search_delivery_select_status">
                                            @if(isset($search_delivery_select_status))
                                                @if($search_delivery_select_status==-1)
                                                    <option selected="" value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Giao Hàng</option>
                                                    <option value="1">Đã Lấy Hàng</option>
                                                    <option value="2">Đã Giao Hàng</option>
                                                    <option value="3">Đã Hủy</option>
                                                @elseif ($search_delivery_select_status==0)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option selected="" value="0">Chưa Giao Hàng</option>
                                                    <option value="1">Đã Lấy Hàng</option>
                                                    <option value="2">Đã Giao Hàng</option>
                                                    <option value="3">Đã Hủy</option>
                                                @elseif ($search_delivery_select_status==1)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Giao Hàng</option>
                                                    <option selected="" value="1">Đã Lấy Hàng</option>
                                                    <option value="2">Đã Giao Hàng</option>
                                                    <option value="3">Đã Hủy</option>
                                                @elseif ($search_delivery_select_status==2)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Giao Hàng</option>
                                                    <option value="1">Đã Lấy Hàng</option>
                                                    <option selected="" value="2">Đã Giao Hàng</option>
                                                    <option value="3">Đã Hủy</option>
                                                @elseif ($search_delivery_select_status==3)
                                                    <option value="-1"> Tất Cả</option>
                                                    <option value="0">Chưa Giao Hàng</option>
                                                    <option value="1">Đã Lấy Hàng</option>
                                                    <option value="2">Đã Giao Hàng</option>
                                                    <option selected="" value="3">Đã Hủy</option>
                                                @endif
                                            @else
                                                <option selected="" value="-1"> Tất Cả</option>
                                                <option value="0">Chưa Giao Hàng</option>
                                                <option value="1">Đã Lấy Hàng</option>
                                                <option value="2">Đã Giao Hàng</option>
                                                <option value="3">Đã Hủy</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group mx-sm-3">
                                        <button type="submit" class="btn btn-success waves-effect waves-light">Tìm</button>
                                    </div>
                                    <div class="form-group ">
                                        <a href="{{URL::to('/delivery')}}" class="btn btn-success waves-effect waves-light">Tất Cả</a>
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
                                    <th class="font-weight-medium">Tổng Thanh Toán</th>
                                    <th class="font-weight-medium">Khách Hàng</th>
                                    <th class="font-weight-medium">Email</th>
                                    <th class="font-weight-medium">Số Điện Thoại</th>
                                    <th class="font-weight-medium">Địa Chỉ</th>
                                    <th class="font-weight-medium">Phương Thức Thanh Toán</th>
                                    <th class="font-weight-medium">Trạng Thái</th>
                                    <th class="font-weight-medium">Thao Tác</th>
                                </tr>
                                </thead>
                                <tbody class="font-14">
                                    @foreach ($all_delivery as $key=>$delivery)
                                    <tr>
                                        <td>
                                            {{ $delivery->giaohang_ma_don_dat_hang }}
                                        </td>
                                        <td>
                                            {{number_format($delivery->giaohang_tong_tien_thanh_toan,0,',','.').' VNĐ' }}
                                        </td>
                                        <td>
                                            {{ $delivery->giaohang_nguoi_nhan }}
                                        </td>
                                        <td>
                                            {{ $delivery->giaohang_nguoi_nhan_email }}
                                        </td>
                                        <td>
                                            {{ $delivery->giaohang_nguoi_nhan_so_dien_thoai }}
                                        </td>
                                        <td>
                                            {{ $delivery->giaohang_nguoi_nhan_dia_chi}}
                                        </td>
                                        <td>
                                            {{ $delivery->giaohang_phuong_thuc_thanh_toan?'Chuyển khoản':'Thanh toán khi nhận hàng'}}
                                        </td>
                                        <td>
                                            @if($delivery->giaohang_trang_thai==0)
                                            Chưa giao hàng
                                            @elseif($delivery->giaohang_trang_thai==1)
                                            Đã lấy hàng
                                            @elseif($delivery->giaohang_trang_thai==2)
                                            Đã giao hàng
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{URL::to('/delivery-show-detail/'.$delivery->dondathang_id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Chi Tiết</a>
                                                     @if($delivery->giaohang_trang_thai!=2 && $delivery->Order->dondathang_trang_thai==1)
                                                    <a href="{{URL::to('/order-in-transit/'.$delivery->Order->id)}}" class="dropdown-item" onclick="return confirm('Xác nhận lấy hàng?')"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Xác Nhận Đã Lấy Hàng</a>
                                                    @endif
                                                    @if($delivery->giaohang_trang_thai==1 && $delivery->Order->dondathang_trang_thai==2 && $delivery->Order->dondathang_trang_thai!=3 && $delivery->Order->dondathang_trang_thai!=4 && $delivery->Order->dondathang_trang_thai!=0)
                                                    <a href="{{URL::to('/order-confirm-delivery/'.$delivery->Order->id)}}" class="dropdown-item" onclick="return confirm('Xác nhận giao hàng?')"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Xác Nhận Giao Hàng</a>
                                                    @endif
                                                    @if($delivery->Order->dondathang_trang_thai!=3 && $delivery->Order->dondathang_trang_thai!=4 && $delivery->Order->dondathang_trang_thai!=0)
                                                    <a href="{{URL::to('/order-canceled/'.$delivery->Order->id)}}"  class="dropdown-item" onclick="return confirm('Hủy đơn hàng?')"><i class="mdi mdi-delete mr-2"></i>Hủy Đơn Hàng</a>
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
                        {{$all_delivery->links('layout.paginationlinks') }}
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