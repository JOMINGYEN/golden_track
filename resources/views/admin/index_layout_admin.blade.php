<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SHOES ADMIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{URL::asset('public/backend/images/favicon.png')}}">
    <!-- App css -->
    <link href="{{URL::asset('public/backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/css/icons.min.css')}}"rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/css/app.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/css/datatable.css')}}" rel="stylesheet" type="text/css">

    <link href="{{URL::asset('public/backend/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{URL::asset('public/backend/libs/multiselect/multi-select.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{URL::asset('public/backend/libs/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{URL::asset('public/backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/libs/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <link href="{{URL::asset('public/backend/libs/footable/footable.core.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{URL::asset('public/backend/libs/custombox/custombox.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('public/backend/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{URL::asset('public/backend/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/libs/switchery/switchery.min.css')}}"  rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/css/sweetalert.css')}}" rel="stylesheet">
</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        @include('admin.blocks.header_admin')
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.blocks.menu_admin')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        @yield('content')
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
      <script src="{{URL::asset('public/backend/js/sweetalert.min.js')}}"></script>
    {{--  <script src="{{URL::asset('public/backend/libs/datatables/dataTables.buttons.min.js')}}"></script>  --}}
    {{--  <script src="{{URL::asset('public/backend/js/pages/datatables.init.js')}}"></script>  --}}
    {{--  <script src="{{URL::asset('public/backend/libs/datatables/dataTables.bootstrap4.js')}}"></script>  --}}
	{{--  <script src="{{URL::asset('public/backend/libs/datatables/buttons.html5.min.js')}}"></script>  --}}
    <script src="{{URL::asset('public/backend/js/vendor.min.js')}}"></script>
	<script src="{{URL::asset('public/backend/js/app.min.js')}}"></script>
    <script src="{{URL::asset('public/backend/js/jquery.js')}}"></script>
    {{--  <script src="{{URL::asset('public/backend/js/jquery2.js')}}"></script>  --}}
    {{--  <script src="{{URL::asset('public/backend/js/pages/form-fileuploads.init.js')}}"></script>  --}}
	{{--  <script src="{{URL::asset('public/backend/js/pages/form-advanced.init.js')}}"></script>  --}}
	{{--  <script src="{{URL::asset('public/backend/js/pages/form-pickers.init.js')}}"></script>  --}}

	<script src="{{URL::asset('public/backend/libs/jquery-quicksearch/jquery.quicksearch.min.js')}}"></script>

	<script src="{{URL::asset('public/backend/libs/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
    <script src="{{URL::asset('public/backend/libs/moment/moment.min.js')}}"></script>

	<script src="{{URL::asset('public/backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{URL::asset('public/backend/libs/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <script src="{{URL::asset('public/backend/libs/dropzone/dropzone.min.js')}}"></script>

	<script src="{{URL::asset('public/backend/libs/multiselect/jquery.multi-select.js')}}"></script>
    <script src="{{URL::asset('public/backend/libs/select2/select2.min.js')}}"></script>

	<script src="{{URL::asset('public/backend/libs/custombox/custombox.min.js')}}"></script>

	<script src="{{URL::asset('public/backend/libs/footable/footable.all.min.js')}}"></script>
	{{--  <script src="{{URL::asset('public/backend/js/pages/foo-tables.init.js')}}"></script>  --}}
    <script src="{{URL::asset('public/backend/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{URL::asset('public/backend/js/pages/sweet-alerts.init.js')}}"></script>

    <script src="{{URL::asset('public/backend/libs/datatables/jquery-3.5.1.js')}}"></script>
    {{--  <script src="{{URL::asset('public/backend/libs/datatables/my-datatable.js')}}"></script>  --}}

    <script src="{{URL::asset('public/backend/js/jquery.scrollUp.min.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="{{URL::asset('public/backend/js/jquery3.js')}}"></script>
    <script src="{{URL::asset('public/backend/js/sweetalert.min.js')}}"></script>
    {{--  <script src="{{URL::asset('public/libs/switchery/switchery.min.js')}}" ></script>  --}}
    {{--  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>  --}}
    <script src="{{URL::asset('public/backend/ckeditor/ckeditor.js')}}"></script>

</body>

</html>
{{--  <script>
    {{--  Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });  --}}
    {{--  $(document).ready( function() {
        var from_day = document.getElementById('get_search_admin_from_day_order').value;
        var to_day = document.getElementById('get_search_admin_to_day_order').value;
        if(from_day!=null){
            $('.search_admin_from_day_order').val(from_day);
        }else{
            $('.search_admin_from_day_order').val(new Date().toDateInputValue());
        }
        if(to_day!=null){
            $('.search_admin_to_day_order').val(to_day);
        }else{
            $('.search_admin_to_day_order').val(new Date().toDateInputValue());
        }
    });  --}}
{{--  </script>  --}}
<script>
    $(document).ready(function(){
        $('.search-type-statistical-order').on('change',function(){
            var search=$('.search-type-statistical-order option:selected').val();
            $.ajax({
                url:"{{url('/search-select-order-statistics')}}",
                method:"GET",
                data:{search_type:search},
                success:function(data){
                   $('.show_search_order_statistics').html(data);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.clear-search-statistical-order').click(function() {
            $(this).closest('form').find("input[type=date]").val("");
            $(this).closest('form').find("select").val("0");
            var from_day = document.getElementById('search_from_day_statistical_order').value;
            var to_day =document.getElementById('search_to_day_statistical_order').value;
            $.ajax({
                url:"{{url('/search-order-statistics')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_search_order_statistics').html(data);
                }
            });
        });
        $('#search_from_day_statistical_order').on('change',function(){
            var from_day = document.getElementById('search_from_day_statistical_order').value;
            var to_day =document.getElementById('search_to_day_statistical_order').value;
            $.ajax({
                url:"{{url('/search-order-statistics')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_search_order_statistics').html(data);
                }
            });
        });
        $('#search_to_day_statistical_order').on('change',function(){
            var from_day = document.getElementById('search_from_day_statistical_order').value;
            var to_day =document.getElementById('search_to_day_statistical_order').value;
            $.ajax({
                url:"{{url('/search-order-statistics')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_search_order_statistics').html(data);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.search-type-statistical-product-import').on('change',function(){
            var search=$('.search-type-statistical-product-import option:selected').val();
            $.ajax({
                url:"{{url('/search-select-product-import')}}",
                method:"GET",
                data:{search_type:search},
                success:function(data){
                   $('.show_search_import_product_statistics').html(data);
                }
            });
        })
    });
</script>
<script>
    $(document).ready(function(){
        $('.clear-search-statistical-product-import').click(function() {
            $(this).closest('form').find("input[type=date]").val("");
            $(this).closest('form').find("select").val("0");
            var from_day = document.getElementById('search_from_day_statistical_product_import').value;
            var to_day =document.getElementById('search_to_day_statistical_product_import').value;
            $.ajax({
                url:"{{url('/search-import-statistics')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_search_import_product_statistics').html(data);
                }
            });
        });
        $('#search_from_day_statistical_product_import').on('change',function(){
            var from_day = document.getElementById('search_from_day_statistical_product_import').value;
            var to_day =document.getElementById('search_to_day_statistical_product_import').value;
            $.ajax({
                url:"{{url('/search-import-statistics')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_search_import_product_statistics').html(data);
                }
            });
        });
        $('#search_to_day_statistical_product_import').on('change',function(){
            var from_day = document.getElementById('search_from_day_statistical_product_import').value;
            var to_day =document.getElementById('search_to_day_statistical_product_import').value;
            $.ajax({
                url:"{{url('/search-import-statistics')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_search_import_product_statistics').html(data);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.clear-search-statistics-product-in-stock').click(function() {
            $(this).closest('form').find("input[type=search]").val("");
            $(this).closest('form').find("select").val("0");
            var product_name = document.getElementById('search_name_statistics_product_in_stock').value;
            var product_size =document.getElementById('search_size_statistics_product_in_stock').value;
            $.ajax({
                url:"{{url('/search-product-in-stock-statistics')}}",
                method:"GET",
                data:{product_name:product_name,product_size:product_size},
                success:function(data){
                    {{--  alert(data);  --}}
                   $('.show_in_stock_search').html(data);
                }
            });
        });

        $('.search_size_statistics_product_in_stock').on('change',function(){
            var product_name = document.getElementById('search_name_statistics_product_in_stock').value;
            var product_size =document.getElementById('search_size_statistics_product_in_stock').value;
            $.ajax({
                url:"{{url('/search-product-in-stock-statistics')}}",
                method:"GET",
                data:{product_name:product_name,product_size:product_size},
                success:function(data){
                    {{--  alert(data);  --}}
                   $('.show_in_stock_search').html(data);
                }
            });
        });
        $('.search_name_statistics_product_in_stock').keyup(function(){
            var product_name = document.getElementById('search_name_statistics_product_in_stock').value;
            var product_size =document.getElementById('search_size_statistics_product_in_stock').value;
            $.ajax({
                url:"{{url('/search-product-in-stock-statistics')}}",
                method:"GET",
                data:{product_name:product_name,product_size:product_size},
                success:function(data){
                    {{--  alert(data);  --}}
                   $('.show_in_stock_search').html(data);
                }
            });
        });

    });
</script>
<script>
    $(document).ready(function(){
        {{--  $('.search-from-to-day-views').on('click',function(){
            var search=$('.search-view-select option:selected').val();
            if(search==0){
                var from_day = document.getElementById('search_from_day_views').value;
                var to_day =document.getElementById('search_to_day_views').value;
                if(!from_day && !to_day){
                    alert("Please select a date to search");
                }else{
                    $.ajax({
                        url:"{{url('/search-from-to-day-views')}}",
                        method:"GET",
                        data:{from_day:from_day,to_day:to_day},
                        success:function(data){
                           $('.show_views_type_search').html(data);
                        }
                    });
                }
            }else{
                alert('You are searching by selection')
            }
        });  --}}
        $('.clear-search-views').click(function() {
            $(this).closest('form').find("input[type=date]").val("");
            var from_day = document.getElementById('search_from_day_views').value;
            var to_day =document.getElementById('search_to_day_views').value;
            $.ajax({
                url:"{{url('/search-from-to-day-views')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_views_type_search').html(data);
                }
            });
        });
        $('.search_from_day_views').on('change',function(){
            var from_day = document.getElementById('search_from_day_views').value;
            var to_day =document.getElementById('search_to_day_views').value;
            $.ajax({
                url:"{{url('/search-from-to-day-views')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_views_type_search').html(data);
                }
            });
        });
        $('.search_to_day_views').on('change',function(){
            var from_day = document.getElementById('search_from_day_views').value;
            var to_day =document.getElementById('search_to_day_views').value;
            $.ajax({
                url:"{{url('/search-from-to-day-views')}}",
                method:"GET",
                data:{from_day:from_day,to_day:to_day},
                success:function(data){
                   $('.show_views_type_search').html(data);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('.search-view-select').on('change',function(){
            var search=$('.search-view-select option:selected').val();
            $.ajax({
                url:"{{url('/search-view-select')}}",
                method:"GET",
                data:{search_type:search},
                success:function(data){
                   $('.show_views_type_search').html(data);
                }
            });
        })
    });
</script>
<script>
    $(function() {
    // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder) {
            if (input.files) {
                var filesAmount = input.files.length;
                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $($.parseHTML('<img width="100px" height="100px">')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };
        $('#images').on('change', function() {
            multiImgPreview(this, 'div.imgPreview');
        });
        $('.btnRemoveImage').click(function() {
            alert("asf");
        });
    });
</script>
<script type="text/javascript">

$("#ProductMoreImagesInput").change(function () {
    var input = document.getElementById("ProductMoreImagesInput");
    var files = input.files;

    $("#NonImageProduct").hide();
    for (var i = 0; i != files.length; i++) {
        var x = (window.URL || window.webkitURL).createObjectURL(files[i]);
        $("#ProductImages").append(' <div class="col-lg-3 col-3 col-sm-3 mb-3 delete-img"><img src="' + x + '" class="ProductMoreImage" width="100px" height="100px"/><button type="button" class="middle"><i class="fas fa-trash fa-2x" id="btnRemoveImage"></i></button></div>');
        formData.append(x, files[i]);
    }
    $(".middle").click(function() {
        e.preventDefault();
        for (var key of formData.keys()) {
            if ($(this).parent().children()[0].getAttribute("src") == key) {
                formData.delete(key);
                flag = true;
                $(this).parent().remove();
                if (!$("#ProductImages").children(".col-lg-3").length) {
                    $("#NonImageProduct").show();
                }
                break;
            }
        }
    });
});
</script>
<script type="text/javascript">
    $('.comment_approval').click(function(){
        var comment_status = $(this).data('comment_status');
        var comment_id = $(this).data('comment_id');
        var comment_product_id = $(this).attr('id');
        var _token = $('input[name="_token"]').val();
        if(comment_status==0){
            var alert = 'UnApproval Success';
        }else{
            var alert = 'Approval Success';
        }
        $.ajax({
            url:"{{url('/approval-comment')}}",
            method:"POST",
            data:{comment_status:comment_status,comment_id:comment_id,comment_product_id:comment_product_id,_token:_token},
            success:function(data){
                location.reload();
               $('#notify_comment').html('<span class="text text-alert">'+alert+'</span>');
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.add-order-admin-coupon').click(function(){
            var product_order_coupon =$('input[name="product_order_coupon"]').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '{{url('/check-coupon')}}',
                method: 'POST',
                data:{product_order_coupon:product_order_coupon,_token:_token},
                success:function(data){
                    $('#show-coupon').html(data);
               }
            });
         });

         $('.order-transport-fee').on('click',function(){
            var city = $('.city').val();
            var province = $('.province').val();
            var wards = $('.wards').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url : '{{url('/check-transport-fee')}}',
                method: 'POST',
                data:{city:city, province:province, wards:wards, _token:_token},
                success:function(data){
                    $('#show-transport_fee').html(data);
                }
            });
     });
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.add-order-admin').click(function(){
            var id = $(this).data('id_product');
            var product_id =$('.product_id_' + id).val();
            var product_name =$('.product_name_' + id).val();
            var product_size_id =$('.product_size_id_' + id).val();
            var product_size_name =$('.product_size_name_' + id).val();
            var product_in_stock =$('.product_in_stock_' + id).val();
            var product_price =$('.product_price_' + id).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '{{url('/order-admin-add-row')}}',
                method: 'POST',
                data:{product_id:product_id,product_size_id:product_size_id,product_price:product_price,
                    product_name:product_name ,product_size_name:product_size_name,product_in_stock:product_in_stock,_token:_token},
                success:function(data){
                    Swal.fire({
                        title: "Add Success",
                        type: "success",
                        showConfirmButton: !1,
                        timer: 1000
                    })
                    location.reload();
               }
            });
         });
    });
</script>
<script type="text/javascript">
    $(document).on('mouseup','.delete-row-order-admin',function(){
        function delete_row_order_admin(){
            $('.delete-row-order-admin').click(function(){
                var id = $(this).data('id_product');
                var product_session_id =$('.product_session_id_' + id).val();
                var _token = $('input[name="_token"]').val();
                 $.ajax({
                     url: '{{url('/order-admin-delete-row')}}',
                     method: 'GET',
                     data:{product_session_id:product_session_id,_token:_token},
                     success:function(data){
                        location.reload();
                    }
                 });
             });
        }
        delete_row_order_admin();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.add-queue').click(function(){
            var id = $(this).data('id_product');
            var product_id =$('.product_id_' + id).val();
            var product_name =$('.product_name_' + id).val();
            var product_image =$('.product_image_' + id).val();
            var _token = $('input[name="_token"]').val();
             $.ajax({
                 url: '{{url('/product-import-add-queue')}}',
                 method: 'POST',
                 data:{product_id:product_id,product_name:product_name,product_image:product_image,_token:_token},
                 success:function(data){
                    Swal.fire({
                        title: "Add Success",
                        type: "success",
                        showConfirmButton: !1,
                       timer: 500
                    })
                    location.reload();
                 }
             });
         });
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.delete-row-queue').click(function(){
            var id = $(this).data('id_product');
            var product_id =$('.product_id_' + id).val();
            var product_session_id =$('.product_session_id_' + id).val();
            var _token = $('input[name="_token"]').val();
             $.ajax({
                 url: '{{url('/product-import-delete-row-queue')}}',
                 method: 'GET',
                 data:{product_id:product_id,product_session_id:product_session_id,_token:_token},
                 success:function(data){
                    Swal.fire({
                        title: "Delete Success",
                        type: "success",
                        showConfirmButton: !1,
                       timer: 500
                        });
                        location.reload();
                }
             });
         });
    });
</script>
<script type="text/javascript">
    $('tbody').delegate('.product_quantity,.product_price','keyup',function(){
        var tr=$(this).parent().parent();
        var quantity=tr.find('.product_quantity').val();
        var price=tr.find('.product_price').val();
        var amount=(quantity*price);
        tr.find('.product_total').val(amount);
        total();
    });
    
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.delete-product-import-detail').click(function(){
            swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonClass: "btn-danger",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Close",
                closeOnConfirm: false,
                closeOnCancel: false
            },function(isConfirm) {
                if(isConfirm){
                    var id = $(this).data('id_product_import');
                    var product_import_id = $('.product_import_id_' + id).val();
                    $.ajax({
                        url: '{{url('/product-import-delete-detail')}}',
                        method: 'GET',
                        data:{product_import_id:product_import_id,_token:_token},
                        success:function(data){
                            swal("success");
                       }
                    });
                }

            })
        });
    });
</script>
<script type="text/javascript">
$(document).ready(function(){
    {{--  fetch_transport_fee();  --}}
    {{--  function fetch_transport_fee(){
        $.ajax({
            url : '{{url('/select-fee')}}',
            method: 'GET',
            success:function(data){
               $('.load-transport-fee').html(data);
            }
        });
    }  --}}
    {{--  $('.transport-fee-add').on('click',function(){
        var city = $('.city').val();
        var province = $('.province').val();
        var wards = $('.wards').val();
        var transport_fee = $('.transport_fee').val();
        var transport_fee_day = $('.transport_fee_day').val();
        var _token = $('input[name="_token"]').val();
         $.ajax({
             url : '{{url('/transport-fee-add')}}',
             method: 'POST',
             data:{city:city, province:province, _token:_token, wards:wards, transport_fee:transport_fee,transport_fee_day:transport_fee_day},
             success:function(data){
                Swal.fire({
                    title: "Add Success",
                    type: "success",
                    showConfirmButton: !1,
                   timer: 500
                    });
                fetch_transport_fee();
             }
         });
     });  --}}
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
             url : '{{url('/select-transport-fee')}}',
             method: 'POST',
             data:{action:action,ma_id:ma_id,_token:_token},
             success:function(data){
                $('#'+ result).html(data);
             }
         });
     });
     $(document).on('blur','.fee-edit',function(){
        var feeship_id = $(this).data('feeship_id');
        var fee_value = $(this).text();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url : '{{url('/update-fee')}}',
            method: 'POST',
            data:{feeship_id:feeship_id, fee_value:fee_value, _token:_token},
            success:function(data){
                fetch_transport_fee();
            }
        });
    });
    $(document).on('blur','.fee-edit-day',function(){
        var feeship_id = $(this).data('feeship_id');
        var fee_value = $(this).text();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url : '{{url('/update-fee-day')}}',
            method: 'POST',
            data:{feeship_id:feeship_id, fee_value:fee_value, _token:_token},
        });

    });
});
</script>
<script>
    $('.select-product-import-add').on('change',function(){
        var product_id = $(this).val();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url : '{{url('/product-import-select-image')}}',
            method: 'GET',
            data:{product_id:product_id,_token:_token},
            success:function(data){
               $('.img-product-import-select').html(data);
            }
        });
    });
</script>