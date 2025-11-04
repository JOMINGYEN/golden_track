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
                                <a href="{{URL::to('/delivery')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Giao Hàng</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Chi Tiết Đơn Hàng</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title">Thông Tin Đơn Hàng</h4>
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
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label class="col-form-label"><h4>Trạng Thái Đơn Hàng</h4></label>
                                            <div class="card">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <label class="col-form-label">Trạng Thái Đơn Hàng</label>
                                                            @if($order->dondathang_tinh_trang_thanh_toan==0 && $order->dondathang_trang_thai==0)
                                                                <input type="text" readonly value="Chưa xác nhận" class="form-control"></input>
                                                            @elseif($order->dondathang_tinh_trang_thanh_toan==0 && $order->dondathang_trang_thai==1)
                                                                <input type="text" readonly value="Đã xác nhận, chưa thanh toán" class="form-control"></input>
                                                            @elseif($order->dondathang_tinh_trang_thanh_toan==1 && $order->dondathang_trang_thai==1)
                                                                <input type="text" readonly value="Đã xác nhận, đã thanh toán" class="form-control"></input>
                                                            @elseif($order->dondathang_trang_thai==2)
                                                                <input type="text" readonly value="Đang giao hàng" class="form-control"></input>
                                                            @elseif($order->dondathang_trang_thai==3)
                                                                <input type="text" readonly value="Đã giao hàng" class="form-control"></input>
                                                            @elseif($order->dondathang_trang_thai==4 && $order->dondathang_tinh_trang_thanh_toan==2)
                                                                <input type="text" readonly value="Đơn hàng đã bị hủy, đã thanh toán" class="form-control"></input>
                                                            @elseif($order->dondathang_trang_thai==4 && $order->dondathang_tinh_trang_thanh_toan==3)
                                                                <input type="text" readonly value="Đơn hàng đã bị hủy, chưa thanh toán" class="form-control"></input>
                                                            @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <div class="text-lg-left mt-3 mt-lg-0">
                                                            @if($order->dondathang_trang_thai!=3 && $order->dondathang_trang_thai!=4 && $order->dondathang_trang_thai!=0)
                                                                <a href="{{URL::to('/order-canceled/'.$order->id)}}"  class="btn btn-success waves-effect" onclick="return confirm('Hủy đơn hàng?')"><i class="mdi mdi-delete mr-2"></i>Hủy Đơn Hàng</a>
                                                            @endif
                                                            @if($order_delivery->giaohang_trang_thai!=2 && $order->dondathang_trang_thai==1)
                                                            <a href="{{URL::to('/order-in-transit/'.$order->id)}}"  class="btn btn-success waves-effect" onclick="return confirm('Xác nhận lấy hàng?')"><i class="mdi mdi-content-save mr-2"></i>Xác Nhận Đã Lấy Hàng</a>
                                                            @endif
                                                            @if($order_delivery->giaohang_trang_thai==1 && $order->dondathang_trang_thai==2 && $order->dondathang_trang_thai!=3 && $order->dondathang_trang_thai!=4 && $order->dondathang_trang_thai!=0)
                                                            <a href="{{URL::to('/order-confirm-delivery/'.$order->id)}}"  class="btn btn-success waves-effect" onclick="return confirm('Xác nhận giao hàng?')"><i class="mdi mdi-content-save mr-2"></i>Xác Nhận Giao Hàng</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="col-form-label"> <h4>Khách Hàng</h4></label>
                                                    <div class="table-responsive" id="ajax-queue">
                                                        <table class="table table-hover  mb-0">
                                                            <thead>
                                                            <tr>
                                                                <td>Ảnh</td>
                                                                <td>Tên User</td>
                                                                <td>Tên</td>
                                                                <td>Số Điện Thoại</td>
                                                                <td>Email</td>
                                                                <td>Địa Chỉ</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(isset($order_customer))
                                                                    <tr>
                                                                        <td>
                                                                            <a href="javascript: void(0);">
                                                                                <img src="{{asset('public/uploads/client/customer/'.$order_customer->khachhang_anh)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                                                            </a>
                                                                        </td>
                                                                        <td >{{ $order_customer->UserAccount->user_ten}} </td>
                                                                        <td>{{ $order_customer->khachhang_ho.''.$order_customer->khachhang_ten}}</td>
                                                                        <td>{{$order_customer->khachhang_so_dien_thoai }}</td>
                                                                        <td>{{ $order_customer->khachhang_email}}</td>
                                                                        <td>{{$order_customer->khachhang_dia_chi }}</td>
                                                                    </tr>
                                                                @else
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td><h4>Không Có Thông Tin</h4></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="col-form-label"> <h4>Thông Tin Đơn Hàng</h4></label>
                                                    <div class="table-responsive" id="ajax-queue">
                                                        <table class="table table-hover  mb-0">
                                                            <thead>
                                                            <tr>
                                                                <td>Mã Đơn Hàng</td>
                                                                <td>Ngày Đặt Hàng</td>
                                                                <td>Trạng Thái Thanh Toán</td>
                                                                <td>Giảm Giá</td>
                                                                <td>Phí Vận Chuyển</td>
                                                                <td>Tổng</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody >
                                                                <tr>
                                                                    <td>{{ $order->dondathang_ma_don_dat_hang}}</td>
                                                                    <td >{{ date('d-m-Y' ,strtotime( $order->dondathang_ngay_dat_hang)) }} </td>
                                                                    <td>
                                                                        @if($order->dondathang_tinh_trang_thanh_toan==0)
                                                                            Chưa thanh toán
                                                                        @elseif ($order->dondathang_tinh_trang_thanh_toan==1)
                                                                            Đã thanh toán
                                                                        @elseif ($order->dondathang_tinh_trang_thanh_toan==2)
                                                                            Đơn hàng đã bị hủy
                                                                        @endif
                                                                    </td>
                                                                    
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <label class="col-form-label"> <h4>Thông Tin Giao Hàng</h4></label>
                                                <div class="table-responsive" id="ajax-queue">
                                                    <table class="table table-hover  mb-0">
                                                        <thead>
                                                        <tr>
                                                            <td>Người Nhận</td>
                                                            <td>Email</td>
                                                            <td>Số Điện Thoại</td>
                                                            <td>Địa Chỉ</td>
                                                            <td>Phương Thức Thanh Toán</td>
                                                            <td>Tổng Phải Thanh Toán</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ $order_delivery->giaohang_nguoi_nhan}}</td>
                                                                <td >{{ $order_delivery->giaohang_nguoi_nhan_email }} </td>
                                                                <td>{{$order_delivery->giaohang_nguoi_nhan_so_dien_thoai }}</td>
                                                                <td>{{ $order_delivery->giaohang_nguoi_nhan_dia_chi }}</td>
                                                                <td>{{$order_delivery->giaohang_phuong_thuc_thanh_toan?'Chuyển khoản':'Thanh toán khi nhận hàng' }}</td>
                                                                <td>
                                                                    {{number_format($order_delivery->giaohang_tong_tien_thanh_toan,0,',','.').' VNĐ' }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <label class="col-form-label">Sản Phẩm</label>
                                                <div class="table-responsive" id="ajax-queue">
                                                    <table class="table table-hover  mb-0">
                                                        <thead>
                                                        <tr>
                                                            <td>Ảnh</td>
                                                            <td>Sản Phẩm</td>
                                                            <td>Số Lượng</td>
                                                            <td>Giá</td>
                                                            <td>Size</td>
                                                            <td>Tổng</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $sub_total=0;
                                                            @endphp
                                                            @foreach ($order_detail as $key=> $product )
                                                                <tr>
                                                                    <td><img src="{{asset('public/uploads/admin/product/'.$product->Product->sanpham_anh)}}" width="70px" height="75px" alt=""></td>
                                                                    <td>{{ $product->Product->sanpham_ten}}</td>
                                                                    <td >{{ $product->chitietdondathang_so_luong }} </td>
                                                                    <td>{{number_format($product->chitietdondathang_don_gia ).' VNĐ' }}</td>
                                                                    <td>{{ $product->Size->size }}</td>
                                                                    <td>{{number_format($product->chitietdondathang_so_luong * $product->chitietdondathang_don_gia ).' VNĐ' }}</td>
                                                                </tr>
                                                                @php
                                                                $sub_total+=($product->chitietdondathang_so_luong * $product->chitietdondathang_don_gia );
                                                                @endphp
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="text-lg-left mt-3 mt-lg-0">
                                            <div class="float-left">
                                                <p>
                                                    <b>Tổng :</b>&nbsp;&nbsp;&nbsp;
                                                        @if($order_coupon)
                                                            @if($order_coupon->makhuyenmai_loai_ma==1)
                                                            {{number_format($order->dondathang_tong_tien - $order->dondathang_phi_van_chuyen + $order->Coupon->makhuyenmai_gia_tri ).' VNĐ' }}
                                                            @else
                                                                @php
                                                                $old_total=$order->dondathang_tong_tien - $order->dondathang_phi_van_chuyen;
                                                                $percent=100-$order->Coupon->makhuyenmai_gia_tri ;
                                                                @endphp
                                                            {{number_format($old_total +(($old_total*$order->Coupon->makhuyenmai_gia_tri)/$percent)).' VNĐ' }}
                                                            @endif
                                                        @else
                                                        {{number_format($order->dondathang_tong_tien - $order->dondathang_phi_van_chuyen + $order->dondathang_giam_gia).' VNĐ' }}
                                                        @endif

                                                </p>
                                                <p><b>Phí Vận Chuyển:</b> <span class="float-right">&nbsp;&nbsp;&nbsp;{{number_format($order->dondathang_phi_van_chuyen ).' VNĐ' }}</span></p>
                                                <p>
                                                    <b>Giảm Giá:</b>
                                                    <span class="float-right">
                                                        @if($order_coupon)
                                                            @if($order_coupon->makhuyenmai_loai_ma==1)
                                                            {{number_format($order->Coupon->makhuyenmai_gia_tri).' VNĐ' }}
                                                            @else
                                                            {{number_format($order->Coupon->makhuyenmai_gia_tri).' %' }}
                                                            @endif
                                                        @else
                                                            {{number_format($order->dondathang_giam_gia).' VNĐ' }}
                                                        @endif
                                                    </span>
                                                </p>
                                                <p>
                                                    <b>Tổng Cộng: </b>&nbsp;&nbsp;&nbsp;
                                                    <span class="float-right">{{number_format($order->dondathang_tong_tien ).' VNĐ' }} </span>
                                                </p>
                                                <p>
                                                    <b>Tổng Phải Thanh Toán: </b>&nbsp;&nbsp;&nbsp;
                                                    <span class="float-right">
                                                        {{number_format($order_delivery->giaohang_tong_tien_thanh_toan ).' VNĐ' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div
                            </div>
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
@endsection
