@extends('client.index_layout')
@section('content')
<div class="pos_home_section">
    <div class="row">
       <!--banner slider start-->
       @include('client.blocks.slideshow')
         <!--banner slider end -->
    </div>
     <!--new product area start-->
    <div class="new_product_area product_two">
        <div class="row">
            <div class="col-12">
                <div class="block_title">
                <h3>Sản phẩm mới</h3>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="single_p_active owl-carousel">
                @if($all_product)
                @foreach ($all_product as $key => $product)
                <div class="col-lg-3">
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
                @endif

            </div>
        </div>
    </div>
    <!--new product area start-->
    <!--featured product area start-->
    <div class="new_product_area product_two">
        <div class="row">
            <div class="col-12">
                <div class="block_title">
                <h3> </h3><h3>Sản phẩm nổi bật</h3>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="single_p_active owl-carousel">
                @if($all_product_featured)
                @foreach ($all_product_featured as $key => $product)
                <div class="col-lg-3">
                    <div class="single_product">
                        <div class="product_thumb">
                           <a href="{{URL::to('/product-detail/'.$product->id)}}"><img id="wishlist_viewed_product_image{{ $product->id }}" src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" alt=""></a>
                           <div class="hot_img">
                               <img src="{{asset('public/frontend/img/cart/span-hot.png')}}" alt="">
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
                @endif

            </div>
        </div>
    </div>
    <!--featured product area start-->
    <!--featured product area start-->
    
        
    </div>
    <!--featured product area start-->
    {{--  <!--blog area start-->
    <div class="blog_area blog_two">
        <div class="row">
            <div class="col-lg-12">
                <div class="single_blog">
                    <div class="blog_thumb">
                        <a href="blog-details.html"><img src="{{asset('public/frontend/img/blog/blog1.jpg')}}" alt=""></a>
                    </div>
                    <div class="blog_content">
                        <div class="blog_post">
                            <ul>
                                <li>
                                    cua hang
                                </li>
                            </ul>
                        </div>
                        <h3><a href="blog-details.html">When an unknown took a galley of type.</a></h3>
                        <p>Distinctively simplify dynamic resources whereas prospective core competencies. Objectively pursue multidisciplinary human capital for interoperable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--blog area end-->  --}}
    <!--brand logo strat-->
    
</div>
@endsection
