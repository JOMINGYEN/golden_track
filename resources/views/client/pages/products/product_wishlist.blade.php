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
                    <li>Yêu Thích</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
 <!--shopping cart area start -->
<div class="shopping_cart_area">
            <div class="row">
                <div class="col-md-12 row">
                    <div class="table_desc wishlist">
                        <div class="cart_page table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="product_remove">Xóa</th>
                                        <th class="product_thumb">Ảnh</th>
                                        <th class="product_name">Sản Phẩm</th>
                                        <th class="product-price">Giá</th>
                                        <th class="product_total">Chi Tiết</th>
                                    </tr>
                                </thead>
                                <tbody id="show_product_wishlist">
                                    {{--  <tr>
                                       <td class="product_remove"><a href="#">X</a></td>
                                        <td class="product_thumb"><a href="#"><img src="assets\img\cart\cart17.jpg" alt=""></a></td>
                                        <td class="product_name"><a href="#">Handbag fringilla</a></td>
                                        <td class="product-price">£65.00</td>
                                        <td class="product_quantity">In Stock</td>
                                        <td class="product_total"><a href="#">Add To Cart</a></td>
                                    </tr>  --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                 </div>
             </div>
        <div class="row">
            <div class="col-12">
                 <div class="wishlist-share">
                    <h4>Share on:</h4>
                    <ul>
                        <li><a href="#"><i class="fa fa-rss"></i></a></li>
                        <li><a href="#"><i class="fa fa-vimeo"></i></a></li>
                        <li><a href="#"><i class="fa fa-tumblr"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
 </div>
 <!--shopping cart area end -->
@endsection
