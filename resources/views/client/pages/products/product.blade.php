@extends('client.index_layout')
@section('content')
<!--breadcrumbs area start-->
<div class="breadcrumbs_area">
    <div class="row">
        <div class="col-12">
            <div class="breadcrumb_content">
                <ul>
                    <li><a href="{{URL::to ('/')}}">Trang Chủ</a></li>
                    <li><i class="fa fa-angle-right"></i></li>
                    <?php
                    $pages_name=Session::get('pages_name');
                    $search_keywword=Session::get('search_keyword');
                    if($pages_name){
                        echo '<li>'.$pages_name.'</li>';
                        Session::put('pages_name',null);
                    }elseif($search_keywword){
                        echo '<li>'.$search_keywword.'</li>';
                    }else{
                        echo '<li>Mua Hàng</li>';
                    }
                    ?>
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
                {{-- <div class="sidebar_widget newsletter mb-30">
                    <div class="block_title">
                        <h3>newsletter</h3>
                    </div>
                    <form action="#">
                        <p>Sign up for your newsletter</p>
                        <input placeholder="Your email address" type="text">
                        <button type="submit">Subscribe</button>
                    </form>
                </div> --}}
                <!--newsletter block end-->

                <!--wishlist block start-->
                <div class="sidebar_widget wishlist mb-30">
                    <div class="block_title">
                        <h3><a href="#">Yêu Thích</a></h3>
                    </div>
                    <div id="list_row_wishlist">
                    </div>

                    <div class="block_content">
                        <p id="count_product_wishlist">
                        </p>
                        <a href="{{URL::to('/my-wishlists')}}">» Yêu Thích</a>
                    </div>
                </div>
                <!--wishlist block end-->
                <!--viewed block start-->
                <div class="sidebar_widget wishlist mb-30">
                    <div class="block_title">
                        <h3><a href="#">Sản phẩm đã xem</a></h3>
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
                         <p>{{ $count_re }}  Sản phẩm</p>
                     </div>
                </div>
                <!--viewed block end-->
                <!--special product start-->
                <div class="sidebar_widget special">
                    <div class="block_title">
                        <h3> </h3><h3>Sản phẩm nổi bật</h3>
                    </div>
                    @php
                        $count_rate_break=0;
                    @endphp
                    @foreach ($all_product_rate as $key => $pro_rating)
                        @if($count_rate_break<3)
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
                                            @for($i = 1; $i <=5; $i++)
                                                @if($i <= $average)
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
                                            <span class="new_price">{{number_format($pro_rating->sanpham_gia_ban,0,',','.').' VNĐ' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $count_rate_break++;
                                @endphp
                        @else
                            @break
                        @endif
                    @endforeach
                </div>
                <!--special product end-->
            </div>
            <div class="col-lg-9 col-md-12">
                <!--shop toolbar start-->
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
                <div class="col-md-12 mb-35">
                    <form class="form-inline" action="{{URL::to('/search-product-filter-customer')}}" method="GET">
                        <div class="form-group col-lg-3 mt-3">
                            <select name="search_customer_brand" class="custom-select" id="status-select">
                                <option value="" selected="">---Thương Hiệu---</option>
                                @foreach ($product_brand as $key => $brand)
                                    @if(isset($search_filter_customer))
                                        @foreach ($search_filter_customer as $key=>$brd)
                                            @if($brand->id==$brd['search_customer_brand'])
                                                <option selected value="{{ $brand->id }}">{{ $brand->thuonghieu_ten }}</option>
                                            @else
                                            <option value="{{ $brand->id }}">{{ $brand->thuonghieu_ten }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                    <option value="{{ $brand->id }}">{{ $brand->thuonghieu_ten }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3 mt-3">
                            <select name="search_customer_product_type" class="custom-select" id="status-select">
                                <option value="" selected="">---Loại Sản Phẩm---</option>
                                @foreach ($product_type as $key => $pro_type)
                                    @if(isset($search_filter_customer))
                                        @foreach ($search_filter_customer as $key=>$type_pro)
                                            @if($pro_type->id==$type_pro['search_customer_product_type'])
                                                <option selected value="{{ $pro_type->id }}">{{ $pro_type->loaisanpham_ten}}</option>
                                            @else
                                                <option value="{{ $pro_type->id }}">{{ $pro_type->loaisanpham_ten}}</option>
                                            @endif
                                        @endforeach
                                    @else
                                    <option value="{{ $pro_type->id }}">{{ $pro_type->loaisanpham_ten}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3 mt-3">
                            <select name="search_customer_collection" class="custom-select" id="status-select">
                                <option value="" selected="">---Dòng Sản Phẩm---</option>
                                @foreach ($product_collection as $key => $collection)
                                    @if(isset($search_filter_customer))
                                        @foreach ($search_filter_customer as $key=>$collec)
                                            @if($collection->id==$collec['search_customer_collection'])
                                                <option selected value="{{ $collection->id }}">{{ $collection->dongsanpham_ten }}</option>
                                            @else
                                                <option value="{{ $collection->id }}">{{ $collection->dongsanpham_ten }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                    <option value="{{ $collection->id }}">{{ $collection->dongsanpham_ten }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3 mt-3">
                            <select name="search_customer_price" class="custom-select" id="status-select">
                                <option value="" selected="">---Giá---</option>
                                @if(isset($search_filter_customer))
                                    @foreach ($search_filter_customer as $key=>$price)
                                        @if($price['search_customer_price']==1)
                                            <option selected value="1">< 500.000 VNĐ</option>
                                            <option value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                            <option value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                            <option value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                            <option value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                            <option value="6">Tất Cả</option>
                                        @elseif($price['search_customer_price']==2)
                                            <option value="1">< 500.000 VNĐ</option>
                                            <option selected value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                            <option value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                            <option value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                            <option value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                            <option value="6">Tất Cả</option>
                                        @elseif($price['search_customer_price']==3)
                                            <option value="1">< 500.000 VNĐ</option>
                                            <option value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                            <option selected value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                            <option value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                            <option value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                            <option value="6">Tất Cả</option>
                                        @elseif($price['search_customer_price']==4)
                                            <option value="1">< 500.000 VNĐ</option>
                                            <option value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                            <option value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                            <option selected value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                            <option value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                            <option value="6">Tất Cả</option>
                                        @elseif($price['search_customer_price']==5)
                                            <option value="1">< 500.000 VNĐ</option>
                                            <option value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                            <option value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                            <option value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                            <option selected value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                            <option value="6">Tất Cả</option>
                                        @elseif($price['search_customer_price']==6)
                                            <option value="1">< 500.000 VNĐ</option>
                                            <option value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                            <option value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                            <option value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                            <option value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                            <option selected value="6">Tất Cả</option>
                                        @else
                                            <option value="1">< 500.000 VNĐ</option>
                                            <option value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                            <option value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                            <option value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                            <option value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                            <option value="6">Tất Cả</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="1">< 500.000 VNĐ</option>
                                    <option value="2">500.000 VNĐ - 1.000.000 VNĐ</option>
                                    <option value="3">1.000.000 VNĐ - 2.000.000 VNĐ</option>
                                    <option value="4">2.000.000 VNĐ - 5.000.000 VNĐ</option>
                                    <option value="5">5.000.000 VNĐ - 10.000.000 VNĐ</option>
                                    <option value="6">Tất Cả</option>
                                @endif
                            </select>
                        </div>
                     
                            

                        <div class="form-group col-lg-3">
                            <button type="submit" class="btn btn-danger waves-effect waves-light mt-3 mr-3"><i class="mdi mdi-search-web mr-1"></i>Tìm</button>
                            <a href="{{URL::to('/shop-now')}}" class="btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-search-web mr-1"></i>Tất Cả</a>
                        </div>
                    </form>
                </div>
                <!--shop toolbar end-->
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
                                               <a href="{{URL::to('/product-detail/'.$product->id)}}"><img id="wishlist_viewed_product_image{{ $product->id }}" src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" alt=""></a>
                                               <div class="img_icone">
                                                   <img src="{{asset('public/frontend/img/cart/span-new.png')}}" alt="">
                                               </div>
                                            </div>
                                            
                                                <h3 class="product_title"><a href="{{URL::to('/product-detail/'.$product->id)}}">{{ $product->sanpham_ten }}</a></h3>
                                            </div>
                                            <div class="product_info">
                                                <ul>
                                                    <input type="hidden" value="{{ $product->sanpham_ten }}" id="wishlist_viewed_product_name{{ $product->id }}">
                                                    <input type="hidden" value="{{number_format($product->sanpham_gia_ban,0,',','.').' VNĐ' }}" id="wishlist_viewed_product_price{{ $product->id }}">
                                                    <li><a type="button" onclick="add_wistlist(this.id);" id="{{ $product->id }}" title=" Add to Wishlist ">Thêm Yêu Thích</a></li>
                                                    <li><a class="views-product-detail" data-views_product_id="{{$product->id}}" id="wishlist_viewed_product_url{{ $product->id }}"href="{{URL::to('/product-detail/'.$product->id)}}"title="Quick view">Chi Tiết</a></li>
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
