@extends('client.index_layout')
@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="row">
        <div class="col-12">
            <div class="breadcrumb_content">
                <ul>
                    <li><a href="{{URL::to ('/')}}">home</a></li>
                    <li><i class="fa fa-angle-right"></i></li>
                    <li>Promotion</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->
<!--pos home section-->
<div class=" pos_home_section">
    <div class="row pos_home">
        <div class="col-lg-3 col-md-12">
            <!--newsletter block start-->
            <div class="sidebar_widget newsletter mb-30">
                <div class="block_title">
                    <h3>newsletter</h3>
                </div>
                <form action="#">
                    <p>Sign up for your newsletter</p>
                    <input placeholder="Your email address" type="text">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
            <!--newsletter block end-->

            <!--wishlist block start-->
                <div class="sidebar_widget wishlist mb-30">
                    <div class="block_title">
                        <h3><a href="#">Wishlist</a></h3>
                    </div>
                    <div id="list_row_wishlist">
                    </div>

                    <div class="block_content">
                        <p id="count_product_wishlist">
                        </p>
                        <a href="{{URL::to('/my-wishlists')}}">» My wishlists</a>
                    </div>
                </div>
                <!--wishlist block end-->
                <!--viewed block start-->
                <div class="sidebar_widget wishlist mb-30">
                    <div class="block_title">
                        <h3><a href="#">Product Viewed</a></h3>
                    </div>
                    @php
                        $count_re=0;
                    @endphp
                    @foreach ($all_product_viewed as $key=>$product_viewed)
                        @if ($count_re<=3)
                            <div class="cart_item">
                                <div class="cart_img">
                                    <a href="{{URL::to('/product-detail/'.$product_viewed->id)}}"><img src="{{asset('public/uploads/admin/product/'.$product_viewed->sanpham_anh)}}" width="70px" height="75px" alt=""></a>
                                </div>
                                <div class="cart_info">
                                    <a href="{{URL::to('/product-detail/'.$product_viewed->id)}}">{{ $product_viewed->sanpham_ten }}</a>
                                    
                                </div>
                                <div class="cart_remove">
                                    <a title="Remove this item" href="{{ URL::to('/delete-mini-product-viewed/'.$product_viewed->id)}}"><i class="fa fa-times-circle"></i></a>
                                </div>
                            </div>
                            @php
                                $count_re++;
                            @endphp
                        @else
                        @break
                        @endif
                    @endforeach
                     <div class="block_content">
                         <p>{{ $count_re }}  products</p>
                         <a href="#">» My wishlists</a>
                     </div>
                </div>
                <!--viewed block end-->
            <!--special product start-->
            <div class="sidebar_widget special">
                <div class="block_title">
                    <h3>Special Products</h3>
                </div>
                @php
                    $count=0;
                @endphp
                @foreach ($all_product_rate as $key => $pro_rating)
                    @if($count==5)
                        @break
                    @else
                        <div class="special_product_inner mb-20">
                            <div class="special_p_thumb">
                                <a href="{{URL::to('/product-detail/'.$pro_rating->id)}}"><img src="{{asset('public/uploads/admin/product/'.$pro_rating->sanpham_anh)}}" class="pro-img-rating" width="60px" height="80px" alt=""></a>
                            </div>
                            <div class="small_p_desc">
                                <div class="product_ratting">
                                <ul>
                                    @php
                                    $sum=0;
                                    $count_rate=0;
                                    @endphp
                                    @foreach($comment_customer as $k=>$comment_cus)
                                        @if ($comment_cus->sanpham_id==$pro_rating->id)
                                        @php
                                            $sum+=$comment_cus->binhluan_diem_danh_gia;
                                            $count_rate++;
                                        @endphp
                                        @endif
                                    @endforeach
                                    @php
                                    if($count_rate!=0){
                                        $average=$sum/$count_rate;
                                    }else{
                                        $average=0;
                                    }
                                    @endphp
                                    @for($count = 1; $count <=5; $count++)
                                        @if($count <= $average)
                                            <i class="fa fa-star ratting_review"></i>
                                        @else
                                            <i class="fa fa-star ratting_no_review"></i>
                                        @endif
                                    @endfor
                                </ul>
                            </div>
                                <h3><a href="single-product.html">{{ $pro_rating->sanpham_ten }}</a></h3>
                                <div class="special_product_proce">
                                    {{--  <span class="old_price">$124.58</span>  --}}
                                    <span class="new_price">{{number_format($pro_rating->sanpham_gia_ban).' VNĐ' }}</span>
                                </div>
                            </div>
                        </div>
                        @php
                            $count++;
                        @endphp
                    @endif
                @endforeach
            </div>
            <!--special product end-->
        </div>
            <div class="col-lg-9 col-md-12">
                <div class="row col-md-12">
                    <!--shop tab product-->
                    <div class="shop_tab_product">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="large" role="tabpanel">
                                <div class="row">
                                    @foreach ($all_product as $key => $product)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="single_product">
                                            <div class="product_thumb">
                                               <a href="{{URL::to('/product-discount-detail/'.$product->id)}}"><img id="wishlist_viewed_product_image{{ $product->id }}" src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" alt=""></a>
                                               <div class="hot_img">
                                                   <img src="{{asset('public/frontend/img/cart/span-hot.png')}}" alt="">
                                               </div>
                                            </div>
                                            <div class="product_content content_price mb-15">
                                                   
                                                <h3 class="product_title"><a href="{{URL::to('/product-discount-detail/'.$product->id)}}">{{ $product->sanpham_ten }}</a></h3>
                                            </div>
                                            <div class="product_info">
                                                <ul>
                                                    <input type="hidden" value="{{ $product->sanpham_ten }}" id="wishlist_viewed_product_name{{ $product->id }}">
                                                    <input type="hidden" value="{{$price }}" id="wishlist_viewed_product_price{{ $product->id }}">
                                                    <li><a type="button" onclick="add_wistlist(this.id);" id="{{ $product->id }}" title=" Add to Wishlist ">Add to Wishlist</a></li>
                                                    <li><a class="views-product-detail" data-views_product_id="{{$product->id}}" id="wishlist_viewed_product_url{{ $product->id }}"  href="{{URL::to('/product-discount-detail/'.$product->id)}}"  title="Quick view">View Detail</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--shop tab product end-->
                    <!--pagination style start-->
                    <div class="pagination_style">
                        <div class="page_number">
                            {{--  <span>Pages: </span>  --}}
                            <ul>
                                <li> {{ $all_product->links('layout.paginationlinks') }}</li>
                                {{--  <li class="current_number">1</li>
                                <li><a href="#">2</a></li>
                                <li>»</li>  --}}
                            </ul>
                        </div>
                    </div>
                    <!--pagination style end-->
                </div>
            </div>
        </div>
</div>
<!--pos home section end-->
@endsection
