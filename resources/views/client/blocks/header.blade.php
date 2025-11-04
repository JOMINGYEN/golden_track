
 <!--header top end-->
 <!--breadcrumbs area start-->
 <div class="breadcrumbs_area">
     <div class="row hr1">
         <div class="col-12">
             <div class="breadcrumb_content hr3">
                 <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                     <div class="carousel-inner">
                         @if($header_show)
                         @foreach ($header_show as $key => $header)
                         @if($header->headerquangcao_thu_tu==$header_min)
                         <div class="carousel-item hr2 active">
                             {{ $header->headerquangcao_noi_dung }}
                         </div>
                         @else
                         <div class="carousel-item hr2">
                             {{ $header->headerquangcao_noi_dung }}
                         </div>
                         @endif

                      @endforeach
                         @endif

                     </div>
                     <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                     </button>
                     <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!--breadcrumbs area end-->
 <!--header middel-->
 <div class="header_middel" style="background-color:rgb(74, 57, 57);">
     <div class="row align-items-center">
        <!--logo start-->
         <div class="col-lg-3 col-md-3">
             <div class="logo">
               <img src="{{ asset('public/frontend/img/logo/vcl.png') }}" width="auto" height="100px" alt="vcl">
             </div>
         </div>
         <!--logo end-->
         <div class="col-lg-9 col-md-9">
             <div class="header_right_info">
                 <div class="search_bar">
                    <form action="{{URL::to ('/search-product-customer')}}" method="GET">
                        <input placeholder="Tìm Kiếm" required=""
                        @if(isset($search_keyword))
                        value="{{ $search_keyword }}"
                        @endif
                        name="search_product_customer" type="search">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                 </div>
                

             </div>
         </div>
     </div>
 </div>
