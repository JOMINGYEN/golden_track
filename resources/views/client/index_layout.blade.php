<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>GOLDEN TRACK</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="{{asset('public/frontend/img/vcl.png')}}">

		<!-- all css here -->
        <link rel="stylesheet" href="{{asset('public/frontend/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('public/frontend/css/bootstrap.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/plugin.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/bundle.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/lightgallery.min.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/responsive.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/lightslider.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/prettify.css')}}">
        <link rel="stylesheet" href="{{asset('public/frontend/css/rate.css')}}">
        <script src="{{asset('public/frontend/js/vendor/modernizr-2.8.3.min.js')}}"></script>
        <link href="{{URL::asset('public/frontend/css/sweetalert.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    </head>
    <body>
            <!-- Add your site or application content here -->

            <!--pos page start-->
            <div class="pos_page">
                <div class="container">
                   <!--pos page inner-->
                    <div class="pos_page_inner">
                       <!--header area -->
                        <div class="header_area">
                               <!--header top-->

                               @include('client.blocks.header')

                                <!--header middel end-->
                           <!-- menu -->

                           @include('client.blocks.menu')

                           <!-- end menu -->
                        </div>
                        <!--header end -->
                        <!-- content -->
                        <!--pos home section-->

                        @yield('content')

                        <!--pos home section end-->
                        <!-- end content -->
                    </div>
                    <!--pos page inner end-->
                </div>
            </div>
            <!--pos page end-->

            <!--footer area start-->
            @include('client.blocks.footer')
            <!--footer area end-->
		<!-- all js here -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="{{URL::asset('public/frontend/js/jquery3.js')}}"></script>
        <script src="{{URL::asset('public/frontend/js/sweetalert.min.js')}}"></script>
        <script src="{{URL::asset('public/frontend/js/jquery.js')}}"></script>
    <script src="{{URL::asset('public/frontend/js/jquery2.js')}}"></script>
        <script src="{{asset('public/frontend/js/vendor/jquery-1.12.0.min.js')}}"></script>
        <script src="{{asset('public/frontend/js/popper.js')}}"></script>
        <script src="{{asset('public/frontend/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('public/frontend/js/bootstrap.js')}}"></script>
        <script src="{{asset('public/frontend/js/ajax-mail.js')}}"></script>
        <script src="{{asset('public/frontend/js/plugins.js')}}"></script>
        <script src="{{asset('public/frontend/js/main.js')}}"></script>
         <script src="{{asset('public/frontend/js/lightgallery-all.min.js')}}"></script>
         <script src="{{asset('public/frontend/js/prettify.js')}}"></script>
         <script src="{{asset('public/frontend/js/lightslider.js')}}"></script>
         <script src="{{asset('public/frontend/js/rate.js')}}"></script>
         {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script> --}}
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </body>
</html>

<script>
    $(document).ready(function() {
        if(document.getElementById('count_rate')){
            var count_rate =document.getElementById('count_rate').value;//=100%
        }else{
            var count_rate =1;
        }
        if(document.getElementById('rating-1-star')){
            var rating_star_1=((document.getElementById('rating-1-star').value)*100)/count_rate;
        }else{
            var rating_star_1=0;
        }
         if(document.getElementById('rating-2-star')){
           var rating_star_2=((document.getElementById('rating-2-star').value)*100)/count_rate;
        }else{
            var rating_star_2=0;
        }
         if(document.getElementById('rating-3-star')){
            var rating_star_3=((document.getElementById('rating-3-star').value)*100)/count_rate;
        }else{
            var rating_star_3=0;
        }
         if(document.getElementById('rating-4-star')){
           var rating_star_4=((document.getElementById('rating-4-star').value)*100)/count_rate;
        }else{
            var rating_star_4=0;
        }
         if(document.getElementById('rating-5-star')){
             var rating_star_5=((document.getElementById('rating-5-star').value)*100)/count_rate;
        }else{
            var rating_star_5=0;
        }

        $('.bar span').hide();
        $('#bar-five').animate({
           width: rating_star_5+'%'}, 1000);
        $('#bar-four').animate({
           width: rating_star_4+'%'}, 1000);
        $('#bar-three').animate({
           width: rating_star_3+'%'}, 1000);
        $('#bar-two').animate({
           width: rating_star_2+'%'}, 1000);
        $('#bar-one').animate({
           width: rating_star_1+'%'}, 1000);
        setTimeout(function() {
          $('.bar span').fadeIn('slow');
        }, 1000);
      });
</script>
<script>
    $(function () {
         if(document.getElementById('average_rating')!=null){
                var average_rating = document.getElementById('average_rating').value;
            }else{
                var average_rating =0;
            }
        $("#ratetotal").rateYo({
          rating    : average_rating ,
          spacing   : "5px",
          readOnly: true,
          multiColor: {
            "endColor"  : "#f7bf17"
          }
        });
      });
      $(function () {
          if(document.getElementById('average_rating')!=null){
                var average_rating = document.getElementById('average_rating').value;
            }else{
                var average_rating =0;
            }
        $("#ratetotal1").rateYo({

          rating    : average_rating ,
          spacing   : "5px",
          readOnly: true,
          multiColor: {
            "endColor"  : "#f7bf17"
          }
        });
      });
      $(function () {
           if(document.getElementById('average_rating')!=null){
                var average_rating = document.getElementById('average_rating').value;
            }else{
                var average_rating =0;
            }
        $("#rateYo").rateYo({
          rating    : average_rating ,
          spacing   : "5px",
          readOnly: true,
          multiColor: {
            "endColor"  : "#f7bf17"
          }
        });
      });
</script>
<script type="text/javascript">
    {{--  $(document).ready(function(){
        var data_id = JSON.parse(localStorage.getItem('data_wishlist'));
        if(data_id.length>0){
            data_id.reverse();
            var id=new Array();
            for(i=0;i<data_id.length;i++){
                id.push(data_id[i].id);
            }
            $.ajax({
                url:"{{url('/show-wishlist')}}",
                method:"POST",
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{id:id},
                success:function(data){
                    $('.show_wishlish_pro').html(data);
                }
            });
        }
     });  --}}
    {{--  $(document).ready(function(){
        $('.views-product-detail').click(function(){
            var id = $(this).data('views_product_id');
            var name = document.getElementById('wishlist_viewed_product_name'+id).value;
            var price = document.getElementById('wishlist_viewed_product_price'+id).value;
            var image = document.getElementById('wishlist_viewed_product_image'+id).src;
            var get_url = document.getElementById('wishlist_viewed_product_url'+id).href;
            if(get_url!=null){
                var url = document.getElementById('wishlist_viewed_product_url'+id).href;
            }else{
                var url = document.getElementById('wishlist_viewed_product_url'+id).value;
            }
            var newItem = {
                'url':url,
                'id' :id,
                'name': name,
                'price': price,
                'image': image
            }
            if(localStorage.getItem('data_products_viewed')==null){
               localStorage.setItem('data_products_viewed', '[]');
            }
            var old_data = JSON.parse(localStorage.getItem('data_products_viewed'));
            var matches = $.grep(old_data, function(obj){
                return obj.id == id;
            })
            if(!matches.length){
                old_data.push(newItem);
            }
            localStorage.setItem('data_products_viewed', JSON.stringify(old_data));
         });
    });  --}}
</script>
<script type="text/javascript">
    show_product_wishlist();
    count_product_wishlist();
    view_wishlist();
    function show_product_wishlist(){
        if(localStorage.getItem('data_wishlist')!=null){
            var data = JSON.parse(localStorage.getItem('data_wishlist'));
            data.reverse();
            for(i=0;i<data.length;i++){
                var id =data[i].id;
                var name = data[i].name;
                var price = data[i].price;
                var image = data[i].image;
                var url = data[i].url;
                $('#show_product_wishlist').append('<tr><td class="product_remove"><a type="button" onclick="delete_row_wishlist('+id+');">X</a></td><td class="product_thumb"><a href="'+url+'"><img src="'+image+'" alt="" width="70px" height="75px"></a></td><td class="product_name"><a href="'+url+'">'+name+'</a></td><td class="product-price">'+price+'</td><td class="product_total"><a href="'+url+'">Detail</a></td></tr>');
            }
        }
    }
    function delete_row_wishlist(pro_id_wishlist){
        if(localStorage.getItem('data_wishlist')!=null){
            var data = JSON.parse(localStorage.getItem('data_wishlist'));
            data.reverse();
            var count=1;
            for(i=0;i<data.length;i++){
                var id =data[i].id;
                if(id==pro_id_wishlist){
                    data.splice(i, 1);//thêm 0 phần tử vào data tại i => xóa i i= vị trí 1=sl xóa
                    localStorage.data_wishlist=JSON.stringify(data);
                    break;
                }
            }
            alert('Delete success');
            window.location.reload();
        }
    }
    function view_wishlist(){
        if(localStorage.getItem('data_wishlist')!=null){
            var data = JSON.parse(localStorage.getItem('data_wishlist'));
            data.reverse();
            var count=1;
            for(i=0;i<data.length;i++){
                if(count<=3){
                    var id =data[i].id;
                    var name = data[i].name;
                    var price = data[i].price;
                    var image = data[i].image;
                    var url = data[i].url;
                    $('#list_row_wishlist').append('<div class="cart_item"><div class="cart_img"><a href="'+url+'"><img src="'+image+'" alt=""></a></div><div class="cart_info"><a href="'+url+'">'+name+'</a></div><div class="cart_remove"><a type="button" title="Remove this item" onclick="delete_row_wishlist('+id+');"><i class="fa fa-times-circle"></i></a></div></div>');
                    count++;
                }else{
                    break;
                }
            }
       }
   }
   function count_product_wishlist(){
        if(localStorage.getItem('data_wishlist')!=null){
            var data = JSON.parse(localStorage.getItem('data_wishlist'));
            data.reverse();
        $('#count_product_wishlist').append(data.length +' products');
    }
   }


    function add_wistlist(clicked_id){
        var id = clicked_id;
        var name = document.getElementById('wishlist_viewed_product_name'+id).value;
        var price = document.getElementById('wishlist_viewed_product_price'+id).value;
        var image = document.getElementById('wishlist_viewed_product_image'+id).src;
        var get_url = document.getElementById('wishlist_viewed_product_url'+id).href;
        if(get_url!=null){
            var url = document.getElementById('wishlist_viewed_product_url'+id).href;
        }else{
            var url = document.getElementById('wishlist_viewed_product_url'+id).value;
        }
        var newItem = {
            'url':url,
            'id' :id,
            'name': name,
            'price': price,
            'image': image
        }
        if(localStorage.getItem('data_wishlist')==null){
           localStorage.setItem('data_wishlist', '[]');
        }
        var old_data = JSON.parse(localStorage.getItem('data_wishlist'));
        var matches = $.grep(old_data, function(obj){
            return obj.id == id;
        })
        if(matches.length){
            alert('The product is already in the wishlist');
        }else{
            old_data.push(newItem);
            $('#list_row_wishlist').append('<div class="cart_item"><div class="cart_img"><a href="'+newItem.url+'"><img src="'+newItem.image+'" alt=""></a></div><div class="cart_info"><a href="'+newItem.url+'">'+newItem.name+'</a></div><div class="cart_remove"><a type="button" title="Remove this item" onclick="delete_row_wishlist('+newItem.id+');"><i class="fa fa-times-circle"></i></a></div></div>');
            alert('Add Success');
        }
        localStorage.setItem('data_wishlist', JSON.stringify(old_data));
   }

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#imageGallery').lightSlider({
            gallery:true,
            item:1,
            loop:true,
            thumbItem:3,
            slideMargin:0,
            enableDrag: false,
            currentPagerPosition:'left',
            onSliderLoad: function(el) {
                el.lightGallery({
                    selector: '#imageGallery .lslide'
                });
            }
        });
      });

</script>
<script type="text/javascript">
    var ratedIndex=-1;
    function resetColors(){
        $(".rps i").css('color','#e2e2e2')
    }
    function setStars(max){
        for(var i=0;i<=max;i++){
            $(".rps i:eq(" + i +")").css('color','#f7bf17')
        }
    }
    $(document).ready( function () {
        resetColors();
        localStorage.removeItem("rating");
        $('.rps i').mouseover(function(){
            resetColors();
            var currentIndex = parseInt($(this).data("index"));
            setStars(currentIndex);
        })
        $('.rps i').on('click',function(){
            ratedIndex = parseInt($(this).data("index"));
            localStorage.setItem("rating",ratedIndex);
            $(".starRateV").val(parseInt(localStorage.getItem("rating")));
        })
        $('.rps i').mouseleave(function(){
            resetColors();
            if(ratedIndex != -1){
                setStars(ratedIndex);
            }
        })
        if(localStorage.getItem("rating")!=null){
            setStars(parseInt(localStorage.getItem("rating")));
            $("starRateV").val(parseInt(localStorage.getItem("rating")));
        }

        {{--  $(".review_coment_customer").click(function(){
            if($("#review-comment").val() == '' && $("#review-name").val() == ''){
                $(".rate-error").html("Please Fill In The Text Box!");
            }else{
                $(".rate-error").html("");
                var $form =$(this).closest(".rmp");
                var starRateV =$form.find(".starRateV").val();
                var review_comment =$form.find(".review_comment").val();
                var review_name =$form.find(".review_name").val();
                var product_id =$form.find(".product_id").val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: '{{url('/post-comment-customer')}}',
                    method: 'POST',
                    data:{
                        starRateV:starRateV,
                        review_comment:review_comment,
                        review_name:review_name,
                        product_id:product_id,
                        _token:_token
                       },
                    success:function(data){
                        load_comment();
                   }
                });
            }
        });
        function load_comment(){
            var comment_product_id = $('.comment_product_id').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
              url:"{{url('/load-comment')}}",
              method:"POST",
              data:{comment_product_id:comment_product_id, _token:_token},
              success:function(data){
                $('#comment_show').html(data);
              }
            });
        }  --}}
    } );
</script>

<script>
    $(document).ready(function(){
        $('.logout-customer').click(function(){
            swal({
                title: "Bạn có muốn đăng xuất",
                showCancelButton: true,
                cancelButtonText: "Cancel",
                confirmButtonClass: "btn btn-danger",
                cancelButtonClass: "btn btn-success",
                confirmButtonText: "Logout",
                closeOnConfirm: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url:"{{url('/logout-customer')}}",
                        method:"GET",
                        success:function(){
                            window.location.href = "{{url('/login-customer')}}";
                        }
                    });
                }
            });
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.add-to-cart').click(function(){
            var id = $(this).data('id_product');
            var product_id =$('.product_id_' + id).val();
            var product_name =$('.product_name_' + id).val();
            var product_size_id =$('.product_size_id_' + id).val();
            var product_img =$('.product_img_' + id).val();
            var product_quantity =$('.product_quantity_' + id).val();
            var product_quantity_in_stock =$('.product_quantity_in_stock_' + id).val();
            var product_price =$('.product_price_' + id).val();
            var _token = $('input[name="_token"]').val();
            if(parseInt(product_quantity)>parseInt(product_quantity_in_stock)){
                alert('please order smaller ' + product_quantity_in_stock);
            }else{
                $.ajax({
                    url: '{{url('/add-cart')}}',
                    method: 'POST',
                    data:{product_id:product_id,product_size_id:product_size_id,product_price:product_price,
                        product_name:product_name ,product_img:product_img,product_quantity:product_quantity,_token:_token},
                        success:function(data){
                        swal({
                            title: "Đã thêm sản phẩm vào giỏ hàng",
                            showCancelButton: true,
                            cancelButtonText: "Xem tiếp",
                            confirmButtonClass: "btn btn-danger",
                            cancelButtonClass: "btn btn-success",
                            confirmButtonText: "Đến giỏ hàng",
                            closeOnConfirm: false
                        },
                        function() {
                            window.location.href = "{{url('/cart')}}";
                        });
                }
                });
            }
         });
    });
</script>
<script type="text/javascript">
    $(document).on('mouseup','.delete-cart',function(){
        function delete_row_order_admin(){
            $('.delete-cart').click(function(){
                var id = $(this).data('id_product');
                var product_session_id =$('.product_session_id_' + id).val();
                var _token = $('input[name="_token"]').val();
                 $.ajax({
                     url: '{{url('/delete-cart')}}',
                     method: 'GET',
                     data:{product_session_id:product_session_id,_token:_token},
                     success:function(data){
                        window.location.href = "{{url('/cart')}}";
                    }
                 });
             });
        }
        delete_row_order_admin();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.choose').on('change',function(){
            var action = $(this).attr('id');
            var ma_id = $(this).val();
            var _token = $('input[name="_token"]').val();
            var result = '';
            if(action=='city'){
                result = 'province';
            }else{
                result = 'wards';
            }
            $.ajax({
                url : '{{url('/select-transport-fee-home')}}',
                method: 'POST',
                data:{action:action,ma_id:ma_id,_token:_token},
                success:function(data){
                   $('#'+ result).html(data);

                }
            });
        });
        $('.choose-address').on('change',function(){
            var action = $(this).attr('id');
            var ma_id = $(this).val();
            var _token = $('input[name="_token"]').val();
            var result = '';
            {{-- alert(ma_id); --}}
            if(action=='order_city'){
                result = 'order_province';
            }else{
                result = 'order_wards';
            }
            $.ajax({
                url : '{{url('/select-address')}}',
                method: 'POST',
                data:{action:action,ma_id:ma_id,_token:_token},
                success:function(data){
                   $('#'+ result).html(data);
                }
            });
        });
    });
</script>
{{-- <script type="text/javascript">
    $(document).ready(function(){
        $('.check-transport-fee-home').on('click',function(){
            var city = $('.city').val();
            var province = $('.province').val();
            var wards = $('.wards').val();
            var _token = $('input[name="_token"]').val();
            if(city==''&& province==''&&wards==''){
                alert('error, choose to calculate');
            }else{
                $.ajax({
                    url : '{{url('/check-transport-feeship')}}',
                    method: 'POST',
                    data:{city:city, province:province, wards:wards, _token:_token},
                    success:function(data){
                        window.location.href = "{{url('/checkout')}}";
                    }
                });
            }
        });
   });
</script> --}}
