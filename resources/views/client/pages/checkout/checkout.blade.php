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
                    <li>Thanh Toán</li>
                </ul>

            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
<!--Checkout page section-->
<div class="Checkout_section">
    <div class="row">
           <div class="col-12">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {!! session()->get('message') !!}
                </div>
            @elseif(session()->has('error'))
                <div class="alert alert-danger">
                    {!! session()->get('error') !!}
                </div>
            @endif
                <div class="user-actions mb-20">
                    <h3>
                        <a class="Returning" href="#" data-toggle="collapse" data-target="#checkout_login" aria-expanded="true">Tính Phí Vận Chuyến</a>
                    </h3>
                     <div id="checkout_login" class="collapse" data-parent="#accordion">
                        <div class="checkout_info">
                            <p>Tính phí vận chuyển</p>
                            <form action="{{ URL::to('/check-transport-feeship')}}" method="POST">
                                @csrf
                                <div class="col-12 mb-30">
                                    <label for="country">Tỉnh, Thành Phố <span>*</span></label>
                                    <select name="city" id="city" required="" class="choose city form-control ">
                                        <option>---Tỉnh, Thành Phố---</option>
                                        @foreach ($city as $key=>$cty)
                                            <option value="{{$cty->id}}">{{ $cty->tinhthanhpho_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-30">
                                    <label for="country">Quận Huyện <span>*</span></label>
                                    <select name="province" required="" id="province" class="choose province form-control">
                                        <option>---Chọn Quận Huyện---</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-30">
                                    <label for="country">Xã, Phường <span>*</span></label>
                                    <select name="wards" required="" id="wards" class="wards form-control">
                                        <option >---Chọn Xã Phường Thị Trấn---</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-30">
                                    <div class="order_button">
                                        <button type="submit">Tính Phí Vận Chuyển</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="user-actions mb-20">
                    <h3>
                        <a class="Returning" href="#" data-toggle="collapse" data-target="#checkout_coupon" aria-expanded="true">Sử Dụng Mã Giảm Giá</a>
                    </h3>
                     <div id="checkout_coupon" class="collapse" data-parent="#accordion">
                        <div class="checkout_info">
                            @if(Session::get('cart'))
                                <form action="{{ URL::to('/check-coupon')}}" method="POST">
                                    @csrf
                                    <div class="coupon_inner">
                                        <input placeholder="Mã Giảm Giá" required="" name="cart_coupon" type="text">
                                        <input type="submit" class="check-coupon" name="check_coupon" value="Sử Dụng Mã">
                                    </div>
                                </form>
                            @else
                                <h4 style="text-align: center">Chưa có sản phẩm nào trong giỏ hàng!</h4>
                            @endif
                            {{-- <form action="#">
                                <input placeholder="Coupon code" type="text">
                                 <input value="Apply coupon" type="submit">
                            </form> --}}
                        </div>
                    </div>
                </div>
           </div>
        </div>
    <div class="checkout_form">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <h3>Chi tiết giao hàng</h3>
                    <form action="{{ URL::to('/order-checkout-save')}}" method="POST">
                        @csrf
                            <div class="col-lg-12 mb-30">
                                <label>Tên Người Nhận <span>*</span></label>
                                <input name="order_checkout_name" required="" type="text">
                                @error('order_checkout_name')
                                <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb-30">
                                <label> Email   <span>*</span></label>
                                  <input name="order_checkout_email" required="" type="text">
                                  @error('order_checkout_email')
                                  <p class="alert alert-danger"> {{ $message }}</p>
                                  @enderror
                            </div>
                            <div class="col-lg-12 mb-30">
                                <label>Số Điện Thoại<span>*</span></label>
                                <input name="order_checkout_phone_number" required="" type="number">
                                @error('order_checkout_phone_number')
                                <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-12 mb-30">
                                <label>Địa Chỉ  <span>*</span></label>
                                <input name="order_checkout_address" required="" placeholder="Vui lòng nhập địa chỉ" type="text">
                                @error('order_checkout_address')
                                <p class="alert alert-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-12 mb-30">
                                <label for="country">Tỉnh Thành Phố <span>*</span></label>
                                <select name="order_city" id="order_city" required="" class="choose-address order_city form-control">
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
                            <div class="col-lg-12 mb-30">
                                <div class="order-notes">
                                    <label for="order_note">Ghi Chú Đơn Hàng</label>
                                   <textarea id="order_note" name="order_checkout_note" placeholder="Ghi Chú"></textarea>
                                   @error('order_checkout_note')
                                   <p class="alert alert-danger"> {{ $message }}</p>
                                   @enderror
                               </div>
                            </div>
                            <div class="col-lg-12 mb-30">
                                <div class="payment_method">
                                    <div class="panel-default">
                                         <input id="payment" value="0" checked name="order_checkout_pay_method" type="radio">
                                         <label for="payment" data-toggle="collapse" data-target="#method" aria-controls="method">Thanh Toán Khi Nhận Hàng</label>
                                     </div>
                                    <div class="panel-default">
                                         <input id="payment_defult" value="1" name="order_checkout_pay_method" type="radio" >
                                         <label for="payment_defult" data-toggle="collapse" data-target="#collapsedefult" aria-controls="collapsedefult">Chuyển Khoản</label>
                                         <div id="collapsedefult" class="collapse one" data-parent="#accordion">
                                             <div class="card-body1">
                                                <p>Vui lòng chuyển tiền đến số tài khoản : 0123456789</p>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                            </div>
                            <div class="col-12 mb-30">
                                <label for="account" data-toggle="collapse" data-target="#collapseOne" aria-controls="collapseOne">
                                    <input id="account" name="order_checkout_create_account" value="1" type="checkbox" data-target="createp_account">
                                    Tạo Tài Khoản?
                                </label>
                                <div id="collapseOne" class="collapse one" data-parent="#accordion">
                                    <div class="card-body1">
                                       <label>Tên Tài Khoản<span>*</span></label>
                                        <input placeholder="Tên" name="checkout_order_user_name" type="text">
                                    </div>
                                    <br>
                                    <div class="card-body1">
                                        <label> Mật Khẩu<span>*</span></label>
                                         <input placeholder="Mật Khẩu" name="checkout_order_password" type="password">
                                     </div>
                                </div>
                            </div>
                            <div class="col-12 mb-30">
                                <div class="order_button">
                                    <button type="submit">Xác Nhận Đơn Hàng</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <form action="#">
                            <h3>Giỏ Hàng</h3>
                            <div class="order_table table-responsive mb-30">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(Session::get('cart')==true)
                                            @php
                                            $subtotal=0;
                                            @endphp
                                            @foreach (Session::get('cart') as $key =>$product)
                                                @php
                                                    $subtotal+=$product['product_price']*$product['product_quantity'];
                                                @endphp
                                                <tr>
                                                    <td> {{ $product['product_name'] }} <strong> × {{ $product['product_quantity'] }}</strong></td>
                                                    <td>{{number_format( $product['product_price'] * $product['product_quantity'] ,0,',','.').' VNĐ' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Tổng</th>
                                            <td>
                                                @if(Session::get('cart')==true)
                                                    {{number_format($subtotal,0,',','.').' VNĐ' }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Phí vận chuyển</th>
                                            <td>
                                                <strong>
                                                    @if(Session::get('feeship'))
                                                        @foreach (Session::get('feeship') as $k=>$fee)
                                                            @php
                                                                $fee_ship=$fee['fee'];
                                                            @endphp
                                                            {{number_format($fee_ship,0,',','.').' VNĐ' }}
                                                        @endforeach
                                                    @else
                                                    {{number_format(35000,0,',','.').' VNĐ' }}
                                                    @endif
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Mã khuyến mãi</th>
                                            <td>
                                                <strong>
                                                    @if(Session::get('coupon'))
                                                        @foreach (Session::get('coupon') as $key=>$cou)
                                                            @if($cou['coupon_type']==0)
                                                                @php
                                                                    $total_coupon =(($subtotal*$cou['coupon_number'])/100);
                                                                @endphp

                                                            @else
                                                                @php
                                                                    $total_coupon =$cou['coupon_number'];
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                       - {{number_format($total_coupon,0,',','.').' VNĐ' }}
                                                    @else
                                                       - {{number_format(0,0,',','.').' VNĐ' }}
                                                    @endif
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="order_total">
                                            <th>Tổng cộng</th>
                                            <td><strong>
                                            @if(Session::get('cart'))
                                                @if(Session::get('coupon'))
                                                   @if(Session::get('feeship'))
                                                        {{number_format($subtotal+$fee_ship-$total_coupon,0,',','.').' VNĐ' }}
                                                   @else
                                                    {{number_format($subtotal-$total_coupon,0,',','.').' VNĐ' }}
                                                   @endif
                                                @else
                                                    @if(Session::get('feeship'))
                                                            {{number_format($subtotal+$fee_ship,0,',','.').' VNĐ' }}
                                                    @else
                                                        {{number_format($subtotal+35000,0,',','.').' VNĐ' }}
                                                    @endif
                                                @endif
                                            @else
                                            {{number_format(0,0,',','.').' VNĐ' }}
                                            @endif
                                            </strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="payment_method">
                                 <div class="order_button">
                                     <a type="button" class="btn btn-warning mr-3" href="{{ URL::to('/cart')}}">Quay Lại Giỏ Hàng</a>
                                     <a type="button" class="btn btn-success mr-3" href="{{ URL::to('/shop-now')}}" >Tiếp Tục Mua Hàng</a>
                                     <a type="button" class="btn btn-danger mr-3" href="{{ URL::to('/delete-coupon-cart')}}" >Xóa Mã Khuyến Mãi</a>
                                     <a type="button" class="btn btn-danger mt-3" href="{{ URL::to('/delete-transport-fee-cart')}}" >Xóa Phí Vận Chuyển</a>
                                 </div>
                             </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    <!--Checkout page section end-->
@endsection
