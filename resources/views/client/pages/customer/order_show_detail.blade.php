@extends('client.index_layout')
@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="row">
        <div class="col-12">
            <div class="breadcrumb_content">
                <ul>
                    <li><a href="index.html">Trang Chủ</a></li>
                    <li><i class="fa fa-angle-right"></i></li>
                    <li>Chi Tiết Đơn Hàng</li>
                </ul>

            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
@if(session()->has('message'))
<div class="alert alert-success">
    {!! session()->get('message') !!}
</div>
@elseif(session()->has('error'))
<div class="alert alert-danger">
    {!! session()->get('error') !!}
</div>
@endif
<!--Checkout page section-->
<div class="Checkout_section">
    <div class="row">
    <div class="checkout_form">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="order_table table-responsive mb-30">
                        <div class="payment_method">
                            <div class="order_button">
                                <a type="button" class="btn btn-warning" href="{{ URL::to('/my-account')}}">Quay Lại Đơn Hàng</a>
                                <a type="button" class="btn btn-success" href="{{ URL::to('/shop-now')}}" >Mua Hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="col-lg-12 col-md-12">
                        <h3>Thông tin đơn hàng</h3>
                        <div class="order_table table-responsive mb-30">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt hàng</th>
                                        <th>Trạng thái thanh toán</th>
                                        <th>Trạng thái đơn hàng</th>
                                        <th>Khuyến mãi</th>
                                        <th>Phí vận chuyển</th>
                                        <th>Tổng</th>
                                        <th>Tổng cộng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $feeship=$customer_order->dondathang_phi_van_chuyen;
                                    $discount=$customer_order->dondathang_giam_gia;
                                    $total=$customer_order->dondathang_tong_tien;
                                    @endphp
                                    <tr>
                                        <td>{{ $customer_order->dondathang_ma_don_dat_hang }}</td>
                                        <td>{{ $customer_order->dondathang_ngay_dat_hang }}</td>
                                        <td>
                                            @if($customer_order->dondathang_tinh_trang_thanh_toan==0)
                                            Chưa thanh toán
                                            @elseif ($customer_order->dondathang_tinh_trang_thanh_toan==1)
                                                Đã thanh toán
                                            @elseif ($customer_order->dondathang_tinh_trang_thanh_toan==2)
                                                Đơn hàng đã bị hủy - đã thanh toán
                                            @elseif ($customer_order->dondathang_tinh_trang_thanh_toan==3)
                                                Đơn hàng đã bị hủy
                                            @endif
                                        </td>
                                        <td>
                                            @if($customer_order->dondathang_tinh_trang_thanh_toan==0 && $customer_order->dondathang_trang_thai==0)
                                               Chưa xác nhận
                                            @elseif($customer_order->dondathang_tinh_trang_thanh_toan==0 && $customer_order->dondathang_trang_thai==1)
                                                Đã xác nhận - chưa thanh toán
                                            @elseif($customer_order->dondathang_tinh_trang_thanh_toan==1 && $customer_order->dondathang_trang_thai==1)
                                                Đã xác nhận - đã thanh toán
                                            @elseif($customer_order->dondathang_trang_thai==2)
                                                Đang vận chuyển
                                            @elseif($customer_order->dondathang_trang_thai==3)
                                                Đã giao hàng
                                            @elseif($customer_order->dondathang_trang_thai==4)
                                               Đơn hàng đã bị hủy
                                            @endif
                                        </td>
                                        <td>
                                            @if($customer_order->dondathang_giam_gia )
                                            {{number_format($customer_order->dondathang_giam_gia ,0,',','.').' VNĐ' }}
                                            @else
                                                {{number_format(0 ,0,',','.').' VNĐ' }}
                                            @endif
                                        </td>
                                        <td> {{number_format($customer_order->dondathang_phi_van_chuyen,0,',','.').' VNĐ' }}</td>
                                        <td>
                                            @if($feeship==true)
                                            {{number_format($total-$feeship+$discount,0,',','.').' VNĐ' }}
                                            @else
                                            {{number_format($total-$feeship,0,',','.').' VNĐ' }}
                                            @endif
                                        </td>
                                        <td>{{number_format($total,0,',','.').' VNĐ' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <h3>Thông tin giao hàng</h3>
                        <div class="order_table table-responsive mb-30">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Người nhận</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Phương thức thanh toán</th>
                                        <th>Trạng thái giao hàng</th>
                                        <th>Tổng tiền phải trả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $customer_delivery->giaohang_nguoi_nhan}}</td>
                                        <td >{{ $customer_delivery->giaohang_nguoi_nhan_email }} </td>
                                        <td>{{$customer_delivery->giaohang_nguoi_nhan_so_dien_thoai }}</td>
                                        <td>{{ $customer_delivery->giaohang_nguoi_nhan_dia_chi }}</td>
                                        <td>{{$customer_delivery->giaohang_phuong_thuc_thanh_toan?'Chuyển khoản':'Thanh toán khi nhận hàng' }}</td>
                                        <td>
                                            @if($customer_delivery->giaohang_trang_thai==0)
                                           Chưa giao hàng
                                            @elseif ($customer_delivery->giaohang_trang_thai==1)
                                           Đang giao hàng
                                            @elseif ($customer_delivery->giaohang_trang_thai==2)
                                            Đã giao hàng
                                            @else
                                            Đơn Hàng đã bị hủy
                                            @endif
                                        </td>
                                        <td>
                                            {{number_format($customer_delivery->giaohang_tong_tien_thanh_toan,0,',','.').' VNĐ' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <h3>Chi tiết đơn hàng</h3>
                        <div class="order_table table-responsive mb-30">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Sản phẩm</th>
                                    
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng cộng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($all_order_detail==true)
                                        @foreach ($all_order_detail as $key =>$product)
                                            <tr>
                                                <td><img src="{{asset('public/uploads/admin/product/'.$product->Product->sanpham_anh)}}" width="70px" height="75px" alt=""></td>
                                                <td> {{ $product->Product->sanpham_ten }}</td>
                                                
                                                <td>{{ $product->Product->sanpham_gia_ban }}</td>
                                                <td>{{ $product->chitietdondathang_so_luong }}</td>
                                                <td>{{number_format( $product->chitietdondathang_don_gia* $product->chitietdondathang_so_luong ,0,',','.').' VNĐ' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Tổng</th>

                                        <td>
                                            @if($discount==true)
                                                {{number_format($total-$feeship+$discount,0,',','.').' VNĐ' }}
                                            @else
                                            {{number_format($total-$feeship,0,',','.').' VNĐ' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phí vận chuyển</th>
                                        <td>
                                            <strong>
                                                {{number_format($feeship,0,',','.').' VNĐ' }}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Mã khuyến mãi</th>
                                        <td>
                                            <strong>
                                                @if($discount)
                                                   - {{number_format($discount,0,',','.').' VNĐ' }}
                                                @else
                                                - {{number_format(0,0,',','.').' VNĐ' }}
                                                @endif
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr class="order_total">
                                        <th>Tổng cộng</th>
                                        <td><strong>
                                        {{number_format($total,0,',','.').' VNĐ' }}
                                        </strong></td>
                                    </tr>
                                    <tr class="order_total">
                                        <th>Tổng tiền phải trả</th>
                                        <td><strong>
                                            {{number_format($customer_delivery->giaohang_tong_tien_thanh_toan,0,',','.').' VNĐ' }}
                                        </strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @if ($customer_order->dondathang_trang_thai==0 ||$customer_order->dondathang_trang_thai==1)
                    <div class="col-lg-12 col-md-12">
                        <h3>Cập nhật thông tin vận chuyển</h3>
                        <form action="{{ URL::to('/customer-order-delivery-update-save/'.$customer_order->id)}}" method="POST">
                            @csrf
                                <div class="col-lg-12 mb-30">
                                    <label>Tên người nhận <span>*</span></label>
                                    <input name="delivery_update_name" value="{{ $customer_delivery->giaohang_nguoi_nhan }}" required="" type="text">
                                </div>
                                <div class="col-lg-12 mb-30">
                                    <label> Email   <span>*</span></label>
                                      <input name="delivery_update_email" value="{{ $customer_delivery->giaohang_nguoi_nhan_email }}" required="" type="text">
                                </div>
                                <div class="col-lg-12 mb-30">
                                    <label>Số điện thoại<span>*</span></label>
                                    <input name="delivery_update_phone_number" value="{{ $customer_delivery->giaohang_nguoi_nhan_so_dien_thoai }}" required="" type="number">
                                </div>
                                <div class="col-12 mb-30">
                                    <label>Địa chỉ  <span>*</span></label>
                                    <input name="delivery_update_address" value="{{ $customer_delivery->giaohang_nguoi_nhan_dia_chi }}" required="" type="text">
                                </div>
                                <div class="user-actions mb-30">
                                    <div class="col-12 mb-30">
                                        <button type="button" class="Returning btn btn-danger" href="#" data-toggle="collapse" data-target="#checkout_login" aria-expanded="true">Chọn địa chỉ</button>
                                    </div>
                                     <div id="checkout_login" class="collapse" data-parent="#accordion">
                                        <div class="col-12 mb-30">
                                            <label for="country">Tỉnh Thành Phố <span>*</span></label>
                                            <select name="order_city" id="order_city" required="" class="choose-address order_city form-control ">
                                                <option value="-1">---Chọn Tỉnh Thành Phố ---</option>
                                                @foreach ($city as $key=>$cty)
                                                    <option value="{{$cty->id}}">{{ $cty->tinhthanhpho_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 mb-30">
                                            <label for="country">Quận Huyện <span>*</span></label>
                                            <select name="order_province" required="" id="order_province" class="choose-address select-province form-control">
                                                <option value="-1">---Chọn Quận Huyện---</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-30">
                                            <label for="country">Xã Phường <span>*</span></label>
                                            <select name="order_wards" required="" id="order_wards" class="select-wards form-control">
                                                <option value="-1">---Chọn Xã Phường Thị Trấn---</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-30">
                                    <div class="order-notes">
                                        <label for="order_note">Ghi Chú Đơn Hàng</label>
                                       <textarea id="order_note" name="delivery_update_note" placeholder="Ghi Chú">{{ $customer_order->dondathang_ghi_chu }}</textarea>
                                   </div>
                                </div>
                                <div class="col-12 mb-30">
                                    <div class="order_button">
                                        <button type="submit">Lưu</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
    </div>
    <!--Checkout page section end-->
@endsection
