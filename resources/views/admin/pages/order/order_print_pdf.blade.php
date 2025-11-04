@extends('admin.index_layout_admin')
@section('content')

<style>
    body {
        color: #000 !important;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    table th, table td {
        border: 1px solid #000 !important;
        padding: 8px !important;
        color: #000 !important;
    }

    h4, h5, p, span, b {
        color: #000 !important;
        font-weight: 600;
    }

    .total {
        font-size: 22px !important;
        font-weight: 700 !important;
        color: #000 !important;
    }

    @media print {
        .d-print-none {
            display: none !important;
        }
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <div class="text-lg-right mt-3 mt-lg-0 d-print-none">
                                <a href="{{URL::to('/order-show-detail/'.$order->id)}}" class="btn btn-success"><i class="ti-arrow-left mr-1"></i>Quay Lại</a>
                                <a href="{{URL::to('/order-add')}}" class="btn btn-success"><i class="mdi mdi-plus-circle mr-1"></i>Tạo Mới Đơn Hàng</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="#">SHOES</a></li>
                            <li class="breadcrumb-item active">In Đơn Hàng</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="clearfix mb-3">
                            <div class="float-left">
                                <img src="{{ asset('public/frontend/img/logo/vcl.png') }}" alt="LOGO" height="100">
                            </div>
                            <div class="float-right">
                                <h4 class="m-0">HÓA ĐƠN</h4>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><b>Xin chào, {{ $order_customer ? $order_customer->Customer->khachhang_ten : $order_delivery->giaohang_nguoi_nhan }}</b></p>
                                <p>Cảm ơn bạn đã mua hàng. Chúng tôi cam kết cung cấp sản phẩm chất lượng.</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p><b>Ngày đặt hàng:</b> {{ $order->dondathang_ngay_dat_hang }}</p>
                                <p><b>Trạng thái:</b>
                                    @switch($order->dondathang_trang_thai)
                                        @case(0) Chưa xác nhận @break
                                        @case(1) Đã xác nhận @break
                                        @case(2) Đang vận chuyển @break
                                        @case(3) Đã giao hàng @break
                                        @case(4) Đơn hàng đã bị hủy @break
                                    @endswitch
                                </p>
                                <p><b>Mã đơn hàng:</b> {{ $order->dondathang_ma_don_dat_hang }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <h5>Địa Chỉ Mua Hàng</h5>
                                <address>
                                    {{ $order_customer ? $order_customer->Customer->khachhang_ten : $order_delivery->giaohang_nguoi_nhan }}<br>
                                    {{ $order_customer ? $order_customer->Customer->khachhang_dia_chi : $order_delivery->giaohang_nguoi_nhan_dia_chi }}<br>
                                    <b>Phone:</b> {{ $order_customer ? $order_customer->Customer->khachhang_so_dien_thoai : $order_delivery->giaohang_nguoi_nhan_so_dien_thoai }}
                                </address>
                            </div>
                            <div class="col-sm-6">
                                <h5>Địa Chỉ Nhận Hàng</h5>
                                <address>
                                    {{ $order_delivery ? $order_delivery->giaohang_nguoi_nhan : $order_customer->Customer->khachhang_ten }}<br>
                                    {{ $order_delivery ? $order_delivery->giaohang_nguoi_nhan_dia_chi : $order_customer->Customer->khachhang_dia_chi }}<br>
                                    <b>Phone:</b> {{ $order_delivery ? $order_delivery->giaohang_nguoi_nhan_so_dien_thoai : $order_customer->Customer->khachhang_so_dien_thoai }}
                                </address>
                            </div>
                        </div>

                        {{-- DANH SÁCH SẢN PHẨM --}}
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order_detail as $value)
                                    <tr>
                                        <td>
                                            {{ $value->Product->sanpham_ten }}
                                            @if($value->Size)
                                                - Size: {{ $value->Size->size }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($value->chitietdondathang_don_gia) }} VNĐ</td>
                                        <td>{{ $value->chitietdondathang_so_luong }}</td>
                                        <td>{{ number_format($value->chitietdondathang_don_gia * $value->chitietdondathang_so_luong) }} VNĐ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- TỔNG TIỀN & GHI CHÚ --}}
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6>Ghi chú:</h6>
                                <p>{{ $order->dondathang_ghi_chu ?? 'Không có' }}</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p><b>Tạm tính:</b>
                                    @php
                                        $tam_tinh = $order->dondathang_tong_tien - $order->dondathang_phi_van_chuyen + $order->dondathang_giam_gia;
                                    @endphp
                                    {{ number_format($tam_tinh) }} VNĐ
                                </p>
                                <p><b>Phí vận chuyển:</b> {{ number_format($order->dondathang_phi_van_chuyen) }} VNĐ</p>
                                <p><b>Giảm giá:</b>
                                    @if ($order_coupon)
                                        {{ $order_coupon->makhuyenmai_loai_ma == 1 
                                            ? number_format($order->Coupon->makhuyenmai_gia_tri).' VNĐ' 
                                            : number_format($order->Coupon->makhuyenmai_gia_tri).' %' }}
                                    @else
                                        {{ number_format($order->dondathang_giam_gia) }} VNĐ
                                    @endif
                                </p>
                                <p><b>Tổng cộng:</b> {{ number_format($order->dondathang_tong_tien) }} VNĐ</p>
                                <h4 class="total">Phải thanh toán: {{ number_format($order_delivery->giaohang_tong_tien_thanh_toan) }} VNĐ</h4>
                            </div>
                        </div>

                        <div class="mt-4 mb-1 d-print-none">
                            <div class="text-right">
                                <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer mr-1"></i> In Hóa Đơn </a>
                            </div>
                        </div>
                    </div> <!-- card-box -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.blocks.footer_admin')
@endsection
