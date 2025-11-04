@extends('client.index_layout')
@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="row">
        <div class="col-12">
            <div class="breadcrumb_content">
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
                    <li><i class="fa fa-angle-right"></i></li>
                    <li>Giỏ Hàng</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
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
 <!--shopping cart area start -->
<div class="shopping_cart_area">
    <form action="{{ URL::to('/update-cart')}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="table_cart">
                    <div class="cart_page table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="product_remove">Xóa</th>
                                    <th class="product_thumb">Ảnh</th>
                                    <th class="product_name">Tên Sản Phẩm</th>
                                    
                                    <th class="product_quantity">Số Lượng</th>
                                    <th class="product_total">Tổng Tiền</th>
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
                                            <input type="hidden" name="product_session_id[{{ $product['product_id'] }}]" class="product_session_id_{{ $product['session_id'] }}" value="{{ $product['session_id'] }}">
                                            <td class="product_remove"><button class="btn btn-outline-danger delete-cart" data-id_product="{{ $product['session_id']}}" type="button"><i class="fa fa-trash-o"></i></button></td>
                                            <td class="product_thumb"><a href="#"><img src="{{asset('public/uploads/admin/product/'.$product['product_img'])}}" width="70px" height="75px" alt=""></a></td>
                                            <td class="product_name"><a href="#">{{ $product['product_name'] }}</a> <br><label>Size: {{ $product['product_size_name'] }}</label> </td>
                                            <td class="product-price">{{number_format($product['product_price']  ,0,',','.').' VNĐ' }}</td>
                                            <td class="product_quantity"><input min="1" name="cart_quantity[{{$product['session_id']}}]"  value="{{ $product['product_quantity'] }}" type="number"></td>
                                            <td class="product_total">{{number_format( $product['product_price'] * $product['product_quantity'] ,0,',','.').' VNĐ' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr >
                                    <td colspan="6" ><h4 style="text-align: center">Không có sản phẩm nào trong giỏ hàng!</h4>
                                        <a type="button" class="btn btn-danger" href="{{ URL::to('/shop-now')}}" >Mua Hàng</a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="cart_submit">
                        @if(Session::get('cart')==true)
                        <a type="button" class="btn btn-danger mr-2" href="{{ URL::to('/shop-now')}}">Mua Hàng</a>
                        <button type="submit" >Cập Nhật Giỏ Hàng</button>
                        @endif
                    </div>
                </div>
             </div>
         </div>
        </form>
         <!--coupon code area start-->
        <div class="coupon_area">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="coupon_code">
                        <h3>Mã Giảm Giá</h3>
                        @if(Session::get('cart'))
                            @if(Session::get('customer_id'))
                                <form action="{{ URL::to('/check-coupon')}}" method="POST">
                                    @csrf
                                    <div class="coupon_inner">
                                        <p>Nhập mã giảm giá, nếu có.</p>
                                        @if(session()->has('message'))
                                            <div class="alert alert-success">
                                                {!! session()->get('message') !!}
                                            </div>
                                        @elseif(session()->has('error'))
                                            <div class="alert alert-danger">
                                                {!! session()->get('error') !!}
                                            </div>
                                        @endif
                                        <input placeholder="Coupon code" required="" name="cart_coupon" type="text">
                                        <button type="submit" class="check-coupon">Thêm Mã Khuyến Mãi</button>
                                    </div>
                                </form>
                            @else
                            <h4 style="text-align: center">Vui lòng đăng nhập để sử dụng mã giảm giá!</h4>
                            @endif
                        @else
                            <h4 style="text-align: center">Không có sản phẩm nào trong giỏ hàng!</h4>
                        @endif
                    </div>
                </div>
                {{-- <div class="col-lg-3 col-md-3">
                    <div class="coupon_code">
                        <h3>Calculate shipping</h3>
                        <form >
                            @csrf
                        <div class="coupon_inner">
                            <p>Enter your calculate shipping code if you have one.</p>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select name="city" id="city" class="choose city form-control">
                                        <option>Choose City</option>
                                        @foreach ($city as $key=>$cty)
                                            <option value="{{$cty->id}}">{{ $cty->tinhthanhpho_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select name="province" id="province" class="choose province form-control">
                                        <option>Province</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select name="wards" id="wards" class="wards form-control">
                                        <option >Wards</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="choose city form-control">Apply shipping</button>
                        </div>
                    </form>
                    </div>
                </div> --}}
                <div class="col-lg-6 col-md-6">
                    <div class="coupon_code">
                        <h3>Tổng Giỏ Hàng</h3>
                        <div class="coupon_inner">
                            <div class="cart_subtotal">
                                <p>Tổng</p>
                                <p class="cart_amount">
                                    @if(Session::get('cart')==true)
                                    {{number_format($subtotal,0,',','.').' VNĐ' }}
                                    @endif
                                </p>
                            </div>
                            <div class="cart_subtotal ">
                                 <p>Mã khuyến mãi</p>
                                 <p class="cart_amount"><span>Khuyến Mãi:
                                    @if(Session::get('cart')==true)
                                        @if(Session::get('coupon'))
                                            @foreach (Session::get('coupon') as $key=>$cou)
                                                @if($cou['coupon_type']==0)
                                                    {{ $cou['coupon_number'] }} %
                                                    @php
                                                        $total_coupon =$subtotal- (($subtotal*$cou['coupon_number'])/100);
                                                    @endphp
                                                @else
                                                {{number_format( $cou['coupon_number'],0,',','.').' VNĐ' }}
                                                    @php
                                                        $total_coupon =$subtotal-$cou['coupon_number'];
                                                    @endphp
                                                @endif
                                            @endforeach
                                            </span>
                                            {{number_format($total_coupon,0,',','.').' VNĐ' }}
                                        @endif
                                    @else
                                    Không có sản phẩm
                                    @endif
                                </p>
                             </div>
                            <div class="cart_subtotal">
                                <p>Tổng tiền</p>
                                <p class="cart_amount">
                                    @if(Session::get('cart')==true)
                                    @if(Session::get('coupon'))
                                        @foreach (Session::get('coupon') as $key=>$cou)
                                            @if($cou['coupon_type']==0)
                                                @php
                                                    $total_coupon =$subtotal- (($subtotal*$cou['coupon_number'])/100);
                                                @endphp

                                            @else
                                                @php
                                                    $total_coupon =$subtotal-$cou['coupon_number'];
                                                @endphp
                                            @endif
                                        @endforeach
                                        </span>
                                        {{number_format($total_coupon,0,',','.').' VNĐ' }}
                                    @else
                                    {{number_format($subtotal,0,',','.').' VNĐ' }}
                                    @endif
                                </p>
                            </div>
                            <div class="checkout_btn">
                               @if(Session::get('customer_id')!=NULL)
                                <a href="{{ URL::to('/checkout')}}">Thanh Toán</a>
                               @else
                               <a href="{{ URL::to('/login-customer')}}">Thanh Toán</a>
                               @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--coupon code area end-->

 </div>
 <!--shopping cart area end -->
@endsection
