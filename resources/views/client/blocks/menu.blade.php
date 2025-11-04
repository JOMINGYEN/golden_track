<div class="header_bottom">
    <div class="row">
            <div class="col-12">
                <div class="main_menu_inner">
                    <div class="main_menu d-none d-lg-block">
                        <nav>
                            <ul>
                                <li class="active"><a href="{{URL::to ('/')}}">Trang Chủ</a>
                                </li>
                                <li>
                                    <a href="{{URL::to ('/shop-now')}}">Sản Phẩm</a>
                                </li>
                                
                                 {{--  <li><a href="#">Collection</a>
                                    <div class="mega_menu">
                                        <div class="mega_top fix">
                                            <div class="mega_items">
                                                <ul>
                                                    @foreach ($product_collection as $key => $collection)
                                                        <li><a href="{{URL::to ('/product-collection/'.$collection->id)}}">{{ $collection->dongsanpham_ten }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>  --}}
                                <li>
                                    <a href="{{URL::to ('/promotion')}}">Khuyến Mãi</a>
                                </li>
                                <li>
                                    <a href="{{URL::to ('/about-us')}}">Cửa Hàng</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="mobile-menu d-lg-none">
                        <nav>
                            <ul>
                                <li>
                                    <a href="{{URL::to ('/')}}"> <h5> Home</h5></a>
                                </li>
                                <li>
                                    <a href="{{URL::to ('/shop-now')}}"><h5>shop now</h5></a>
                                </li>
                                <li><a href=""><h5>PAGES </h5></a></li>
                                    <div>
                                        <li>
                                            <a href="#">Thương Hiệu</a>
                                            <ul>
                                                @foreach ($product_brand as $key => $brand)
                                                    <li><a href="{{URL::to ('/product-brand/'.$brand->id)}}">{{ $brand->thuonghieu_ten }}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">Loại Sản Phẩm</a>
                                            <ul>
                                                @foreach ($product_type as $key => $pro_type)
                                                    <li><a href="{{URL::to ('/product-category/'.$pro_type->id)}}">{{ $pro_type->loaisanpham_ten }}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">Dòng sản phẩm</a>
                                            <ul>
                                                @foreach ($product_collection as $key => $collection)
                                                    <li><a href="{{URL::to ('/product-collection/'.$collection->id)}}">{{ $collection->dongsanpham_ten }}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </div>
                                <li>
                                    <a href="{{URL::to ('/promotion')}}"><h5>Khuyến Mãi</h5></a>
                                </li>
                                <li>
                                    <a href="{{URL::to ('/about-us')}}">Cửa Hàng</a>
                                </li>
                                 <li>
                                    <a href="{{URL::to ('/countdown')}}">Năm Mới 2022</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>