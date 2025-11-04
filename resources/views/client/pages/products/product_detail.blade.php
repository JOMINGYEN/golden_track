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
                    <li>Chi Tiết Sản Phẩm</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->

<!--product wrapper start-->
<div class="product_details video_details">
    <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="product_tab product_video fix">
                    <div class="tab-content produc_tab_c">
                        <div class="tab-pane fade show active" id="p_tab1" role="tabpanel">
                            <div class="modal_img">
                                <a href="#"><img id="wishlist_viewed_product_image{{ $product->id }}" src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" width="380px" height="490px" alt=""></a>
                                <div class="img_icone">
                                   <img src="{{asset('public/frontend/img/cart/span-new.png')}}" alt="">
                               </div>
                                <div class="view_img">
                                    <a class="large_view" href="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}"><i class="fa fa-search-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        @foreach ($all_product_image as $key =>$pro_img)
                            <div class="tab-pane fade" id="p_tab2" role="tabpanel">
                                <div class="modal_img">
                                    <a href="#"><img src="{{asset('public/uploads/admin/productimages/'.$pro_img->anhsanpham_ten)}}" alt=""></a>
                                    <div class="img_icone">
                                    <img src="{{asset('public/frontend/img/cart/span-new.png')}}" alt="">
                                </div>
                                    <div class="view_img">
                                        <a class="large_view" href="{{asset('public/uploads/admin/productimages/'.$pro_img->anhsanpham_ten)}}"><i class="fa fa-search-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="product_tab_button">
                        <ul class="nav" role="tablist">
                            @php
                                $count_img=1;
                            @endphp
                            @foreach ($all_product_image as $key =>$pro_img)
                                @if ($count_img<=3)
                                    <li>
                                        <a class="active" data-toggle="tab" href="#p_tab2" role="tab" aria-controls="p_tab1" aria-selected="false"><img src="{{asset('public/uploads/admin/productimages/'.$pro_img->anhsanpham_ten)}}" width="90px" height="120px" alt=""></a>
                                    </li>
                                    @php
                                        $count_img++;
                                    @endphp
                                @else
                                @break
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    {{--  <div class="product_tab_button ">
                        <ul id="imageGallery">
                            <li data-thumb="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" data-src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}">
                              <img class="product-img" src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" />
                            </li>
                            @foreach ($all_product_image as $key =>$pro_img)
                                <li data-thumb="{{asset('public/uploads/admin/productimages/'.$pro_img->anhsanpham_ten)}}" data-src="{{asset('public/uploads/admin/productimages/'.$pro_img->anhsanpham_ten)}}">
                                    <img class="product-img"  src="{{asset('public/uploads/admin/productimages/'.$pro_img->anhsanpham_ten)}}" />
                                </li>
                            @endforeach
                        </ul>
                    </div>  --}}
                </div>
            </div>

            <div class="col-lg-8 col-md-6">
                <div class="product_d_right ">
                    <h1>{{ $product->sanpham_ten }}</h1>
                     <div class="product_ratting mb-10">
                        <div id="rateYo"></div>
                        <ul>
                            @php
                            $sum=0;
                            $count_rate=0;
                            $count_rate_1=0;
                            $count_rate_2=0;
                            $count_rate_3=0;
                            $count_rate_4=0;
                            $count_rate_5=0;
                            @endphp
                            @foreach($comment_customer as $k=>$comment_cus)
                                @php
                                    $sum+=$comment_cus->binhluan_diem_danh_gia;
                                    $count_rate++;
                                    if($comment_cus->binhluan_diem_danh_gia==1){
                                        $count_rate_1++;
                                    }elseif($comment_cus->binhluan_diem_danh_gia==2){
                                        $count_rate_2++;
                                    }elseif($comment_cus->binhluan_diem_danh_gia==3){
                                        $count_rate_3++;
                                    }elseif($comment_cus->binhluan_diem_danh_gia==4){
                                        $count_rate_4++;
                                    }elseif($comment_cus->binhluan_diem_danh_gia==5){
                                        $count_rate_5++;
                                    }
                                @endphp
                            @endforeach
                            @php
                            if($count_rate!=0){
                                $average=$sum/$count_rate;
                            }else{
                                $average=0;
                            }
                            @endphp
                            {{-- @for($count = 1; $count <=5; $count++)
                                @if($count <= $average)
                                    <i class="fa fa-star ratting_review"></i>
                                @else
                                    <i class="fa fa-star ratting_no_review"></i>
                                @endif
                            @endfor --}}
                            <input type="hidden" value="{{ $average }}" id="average_rating">
                            <input type="hidden" value="{{ $count_rate }}" id="count_rate">
                            <li><a href="#"> số lượt đánh giá : <span>{{ $count_rate }}</span> <h4>{{ $average }} Điểm</h4></a></li>
                        </ul>
                    </div>

                    {{-- <div class="star-ratings-css">
                        <div class="star-ratings-css-top" style="width:{{($average*100)/5 }}%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                        <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
                      </div>
                      <div class="Stars" id="rating-score-star" style="--rarting: 4.5"></div> --}}
                    <div class="product_desc">
                        <p>{{ $product->sanpham_mo_ta }}</p>
                    </div>

                    <div class="content_price mb-15">
                        <span>
                            
                        </span>
                    </div>
                    <form action="{{ URL::to('/add-cart') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="box_quantity mb-20 ">
                                
                                <input name="product_quantity_in_stock" class="product_quantity_in_stock_{{ $product->id }}" min="1"
                                @php
                                    $qty_in_stock=0;
                                @endphp
                                @foreach ($get_in_stock as $key=>$in_stock )
                                @php
                                    $qty_in_stock += $in_stock->sanphamtonkho_so_luong_ton;
                                @endphp
                                @endforeach
                                 value="{{ $qty_in_stock }}" type="hidden">
                                 <input type="hidden" value=" {{number_format($product->sanpham_gia_ban ,0,',','.').' VNĐ' }}" id="wishlist_viewed_product_price{{ $product->id }}">
                                 <input id="wishlist_viewed_product_url{{ $product->id }}"  value="{{URL::to('/product-detail/'.$product->id)}}"  type="hidden">
                                <input name="product_id" value="{{ $product->id }}" class="product_id_{{ $product->id }}" type="hidden">
                                <input name="product_img" value="{{ $product->sanpham_anh }}" class="product_img_{{ $product->id }}" type="hidden">
                                <input name="product_name" id="wishlist_viewed_product_name{{ $product->id }}" value="{{ $product->sanpham_ten }}" class="product_name_{{ $product->id }}" type="hidden">
                                <input name="product_price" class="product_price_{{ $product->id }}" value="{{number_format($product->sanpham_gia_ban ,0,',','') }}" type="hidden">
                                
                                <a type="button" onclick="add_wistlist(this.id);" id="{{ $product->id }}" title="add to wishlist"><i class="fa fa-heart" aria-hidden="true"></i></a>
                        </div>
                        
                    </form>
                    @php
                    $qty_in_stock=0;
                    @endphp
                    @foreach ($get_in_stock as $key=>$in_stock )
                    @php
                        $qty_in_stock += $in_stock->sanphamtonkho_so_luong_ton;
                    @endphp
                    @endforeach
                    <div class="product_stock mb-20">
                       <p>{{$qty_in_stock }} items</p>
                        <span>

                            @if($qty_in_stock>0)
                            In Stock
                            @else
                            Sold Out
                            @endif
                        </span>
                    </div>
                    <div class="container rating-a">
                        <div class="inner">
                          <div class="rating">
                            <span class="rating-num">{{ $average }} </span>
                            {{-- <div class="rating-stars">
                              <span><i class="active fa fa-star"></i></span>
                              <span><i class="active fa-star"></i></span>
                              <span><i class="active fa-star"></i></span>
                              <span><i class="active fa-star"></i></span>
                              <span><i class="icon-star"></i></span>
                            </div> --}}
                            <div id="ratetotal"></div>
                            <div class="rating-users">
                              <i class="icon-user"></i> {{ $count_rate }} Lượt đánh giá
                            </div>
                          </div>
                          <div class="histo">
                            <div class="five histo-rate">
                              <span class="histo-star">
                                <i class="active fa fa-star ratting_review"></i>   5    </span>
                              <span class="bar-block">
                                <span id="bar-five" class="bar">
                                  <span>
                                    @if($count_rate_5!=0)
                                        {{number_format( $count_rate_5  ,0,',','.') }}
                                        <input type="hidden" id="rating-5-star" value="{{ $count_rate_5 }}">
                                    @else
                                       0
                                       <input type="hidden" id="rating-5-star" value="0">
                                    @endif
                                </span>&nbsp;
                                </span>
                              </span>
                            </div>

                            <div class="four histo-rate">
                              <span class="histo-star">
                                <i class="active fa fa-star ratting_review"></i>   4    </span>
                              <span class="bar-block">
                                <span id="bar-four" class="bar">
                                  <span>
                                    @if($count_rate_4!=0)
                                    {{number_format( $count_rate_4 ,0,',','.') }}
                                    <input type="hidden" id="rating-4-star" value="{{ $count_rate_4 }}">
                                    @else
                                    0
                                    <input type="hidden" id="rating-4-star" value="0">
                                    @endif
                                </span>&nbsp;
                                </span>
                              </span>
                            </div>

                            <div class="three histo-rate">
                              <span class="histo-star">
                                <i class="active fa fa-star ratting_review"></i>   3    </span>
                              <span class="bar-block">
                                <span id="bar-three" class="bar">
                                <span>
                                    @if($count_rate_3!=0)
                                    {{number_format( $count_rate_3  ,0,',','.') }}
                                    <input type="hidden" id="rating-3-star" value="{{ $count_rate_3 }}">
                                    @else
                                       0
                                       <input type="hidden" id="rating-3-star" value="0">
                                    @endif
                                </span>&nbsp;
                                </span>
                              </span>
                            </div>

                            <div class="two histo-rate">
                              <span class="histo-star">
                                <i class="active fa fa-star ratting_review"></i>   2    </span>
                              <span class="bar-block">
                                <span id="bar-two" class="bar">
                                  <span>
                                    @if($count_rate_2!=0)
                                    {{number_format( $count_rate_2  ,0,',','.') }}
                                    <input type="hidden" id="rating-2-star" value="{{ $count_rate_2 }}">
                                    @else
                                    0
                                    <input type="hidden" id="rating-2-star" value="0">
                                    @endif
                                </span>&nbsp;
                                </span>
                              </span>
                            </div>

                            <div class="one histo-rate">
                              <span class="histo-star">
                                <i class="active fa fa-star ratting_review"></i>   1    </span>
                              <span class="bar-block">
                                <span id="bar-one" class="bar">
                                  <span>
                                    @if($count_rate_1!=0)
                                    {{number_format( $count_rate_1 ,0,',','.') }}
                                    <input type="hidden" id="rating-1-star" value="{{ $count_rate_1 }}">
                                    @else
                                    0
                                    <input type="hidden" id="rating-1-star" value="0">
                                    @endif
                                </span>&nbsp;

                                </span>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    {{-- <div class="container">
                        <h3 class="heading">User Rating</h3>
                        <div class="star-rating">
                            <span class="fa fa-star-o" title="1"></span>
                            <span class="fa fa-star-o" title="2"></span>
                            <span class="fa fa-star-o" title="3"></span>
                            <span class="fa fa-star-o" title="4"></span>
                            <span class="fa fa-star-o" title="5"></span>
                        </div>
                        <h3 class="heading">Reviews</h3>
                        <div class="review-rating">
                            <div class="left-review">
                                <div class="review-title">3.5</div>
                                <div class="review-star">
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star-harf-o"></span>
                                    <span class="fa fa-star-o"></span>
                                </div>
                                <div class="review-people">
                                    <i class="fa fa-user"></i>
                                    8.000.000 total
                                </div>
                            </div>
                            <div class="right-reviews">
                                <div class="row-bar">
                                    <div class="left-bar">5</div>
                                    <div class="right-bar">
                                        <div class="bar-container">
                                            <div class="bar-5" style="width:80%"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row-bar">
                                    <div class="left-bar">4</div>
                                    <div class="right-bar">
                                        <div class="bar-container">
                                            <div class="bar-4" style="width:30%"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row-bar">
                                    <div class="left-bar">3</div>
                                    <div class="right-bar">
                                        <div class="bar-container">
                                            <div class="bar-3" style="width:70%"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row-bar">
                                    <div class="left-bar">2</div>
                                    <div class="right-bar">
                                        <div class="bar-container">
                                            <div class="bar-2" style="width:40%"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row-bar">
                                    <div class="left-bar">1</div>
                                    <div class="right-bar">
                                        <div class="bar-container">
                                            <div class="bar-1" style="width:10%"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
</div>
<!--product details end-->
<!--product info start-->
<div class="product_d_info">
    <div class="row">
        <div class="col-12">
            <div class="product_d_inner">
                <div class="product_info_button">
                    <ul class="nav" role="tablist">
                        <li>
                            <a class="active"  data-toggle="tab" href="#sheet" role="tab" aria-controls="sheet" aria-selected="false">Thông tin sản phẩm</a>
                       </li>
                        <li>
                            <a data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">Mô Tả</a>
                        </li>
                        <li>
                           <a data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Đánh giá</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade " id="info" role="tabpanel">
                        <div class="product_info_content">
                            <p> {{ $product->sanpham_mo_ta }}</p>
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="sheet" role="tabpanel">
                        <div class="product_d_table">
                           <form action="#">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="first_child">NHÃN HIỆU</td>
                                            <td>{{ $product->Brand->thuonghieu_ten }}</td>
                                        </tr>
                                        <tr>
                                            <td class="first_child">TÊN</td>
                                            <td>{{ $product->sanpham_ten }}</td>
                                        </tr>
                                        <tr>
                                            <td class="first_child">MÃ SẢN PHẨM</td>
                                            <td>{{ $product->sanpham_ma_san_pham }}</td>
                                        </tr>
                                        <tr>
                                            <td class="first_child">LOẠI SẢN PHẨM</td>
                                            <td>{{ $product->ProductType->loaisanpham_ten }}</td>
                                        </tr>
                                        <tr>
                                            <td class="first_child">DÒNG SẢN PHẨM</td>
                                            <td>{{ $product->Collection->dongsanpham_ten }}</td>
                                        </tr>
                                        <tr>
                                            <td class="first_child">NƠI SẢN XUẤT</td>
                                            <td>{{ $product->sanpham_noi_san_xuat}}</td>
                                        </tr>
                                        <tr>
                                            <td class="first_child">CHẾ ĐỘ BẢO HÀNH</td>
                                            <td>{{ $product->sanpham_bao_hanh}}</td>
                                        </tr>
                                        
                                       
                                        
                                        <tr>
                                            <td class="first_child">CHẤT LIỆU</td>
                                            <td>{{ $product->sanpham_chat_lieu}}</td>
                                        </tr>
                                        <tr>
                                            <td class="first_child">TÍNH NĂNG</td>
                                            <td>{{ $product->sanpham_tinh_nang}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="product_info_content">
                            <p> {{ $product->sanpham_mo_ta }}</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="product_info_content">
                            <p> {{ $product->sanpham_mo_ta }}</p>
                        </div>
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
                        <form action="{{URL::to('/post-comment-customer')}}" method="POST">
                            @csrf
                            <div class="product_review_form rmp">
                                <h2>Add your review</h2>
                                <p>Required fields are marked </p>
                                <div class="product_ratting mb-10 rps">
                                    <i class="fa fa-star" data-index="0" style="display:none" ></i>
                                    <i class="fa fa-star" data-index="1"></i>
                                    <i class="fa fa-star" data-index="2"></i>
                                    <i class="fa fa-star" data-index="3"></i>
                                    <i class="fa fa-star" data-index="4"></i>
                                    <i class="fa fa-star" data-index="5"></i>
                                </div>
                                <input type="hidden" value="" required="" class="starRateV" name="starRateV">
                                <input type="hidden" value="{{ $product->id }}" name="product_id" class="product_id" >
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <label for="author">Name</label>
                                        <input id="review-name" class="review_name" name="review_name" required="" type="text">
                                        @error('review_name')
                                        <p class="alert alert-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="review_comment">Your review </label>
                                        <textarea name="review_comment" class="review_comment" required="" id="review-comment"></textarea>
                                        @error('review_comment')
                                        <p class="alert alert-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="rate-error" align="center"></div>
                                </div>
                                <button type="submit">Submit</button>
                            </div>
                        </form>
                        <hr>
                        <form>
                            @csrf
                            <input type="hidden" name="comment_product_id" class="comment_product_id" value="{{$product->id}}">
                            <div id="comment_show">
                                @foreach ($comment_customer as $key=>$comment )
                                    <div class="product_info_inner ">
                                        <div class="product_ratting mb-10 col-md-6">
                                            <div class="col-md-2">
                                                <img width="100%" src="{{URL::asset('public/backend/images/users/batman-icon.png')}}" class="img img-responsive img-thumbnail comment-img">
                                            </div>
                                            <br>
                                            <div col-md-4>
                                                <ul>
                                                    @for($count = 1; $count <=5; $count++)
                                                        @if($count <= $comment->binhluan_diem_danh_gia)
                                                            <i class="fa fa-star ratting_review"></i>
                                                        @else
                                                            <i class="fa fa-star ratting_no_review"></i>
                                                        @endif
                                                    @endfor
                                                </ul>
                                                <strong>{{ $comment->binhluan_ten_nguoi_danh_gia }}</strong>
                                                <p>{{ $comment->binhluan_ngay_danh_gia }}</p>
                                                <p>{{ $comment->binhluan_noi_dung }}</p>
                                            </div>
                                        </div>
                                        &emsp;&emsp;
                                        @foreach ($comment_admin as $k=>$ad_comment)
                                            @if($ad_comment->binhluan_id_phan_hoi==$comment->id)
                                                <div class="col-md-6">
                                                    <div class="product_demo">
                                                        <div class="col-md-2">
                                                            <img width="70%" src="{{URL::asset('public/backend/images/users/SHOES.png')}}" class="img img-responsive img-thumbnail comment-img">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>
                                                                @if($ad_comment->admin_id)
                                                                Admin
                                                                @else
                                                                {{ $comment->binhluan_ten_nguoi_danh_gia }}
                                                                @endif
                                                            </strong>
                                                            <p>{{ $ad_comment->binhluan_noi_dung }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--product info end-->


<!--new product area start-->
<div class="new_product_area product_page">
    <div class="row">
        <div class="col-12">
            <div class="block_title">
            <h3>Sản Phẩm Khác:</h3>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="single_p_active owl-carousel">
            @foreach ($all_product as $key => $product )
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
        </div>
    </div>
</div>
<!--new product area start-->


<!--new product area start-->
<div class="new_product_area product_page">
    <div class="row">
        <div class="col-12">
            <div class="block_title">
            <h3>Sản Phẩm Liên Quan</h3>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="single_p_active owl-carousel">
            @foreach ($related_product as $key => $product )
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
        </div>
    </div>
</div>
<!--new product area start-->
<!--new product area start-->
<div class="new_product_area product_page">
    
    <div class="row">
        <div class="single_p_active owl-carousel">
            @foreach ($all_product_viewed as $key => $product )
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
        </div>
    </div>
</div>
<!--new product area start-->
@endsection
