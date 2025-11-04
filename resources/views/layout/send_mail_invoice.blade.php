<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ADMIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{URL::asset('public/backend/images/vcl.png')}}">
    <!-- App css -->
    <link href="{{URL::asset('public/backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/css/icons.min.css')}}"rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/backend/css/app.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" rel="stylesheet" />
</head>
<body>
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <!-- Logo & title -->
                            <div class="clearfix">
                                <div class="float-left">
                                    <img src="{{asset('public/backend/images/logo-dark.png')}}" alt="" height="20">
                                </div>
                                <div class="float-right">
                                    <h4 class="m-0 d-print-none">Invoice</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <p><b>Hello,
                                            {{$shipping_array['customer_name']}}
                                        </b></p>
                                        <p class="text-muted">Thanks a lot because you keep purchasing our products. Our company
                                            promises to provide high quality products for you as well as outstanding
                                            customer service for every transaction. </p>
                                    </div>
                                </div><!-- end col -->
                                <div class="col-md-4 offset-md-2">
                                    <div class="mt-3 float-right">
                                        <p class="m-b-10"><strong>Order Date : </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp;
                                            {{$shipping_array['shipping_day']}}
                                        </span></p>
                                        <p class="m-b-10"><strong>Order No. : </strong> <span class="float-right">
                                            {{$code['order_code']}}
                                        </span></p>
                                        <p class="m-b-10"><strong>Coupon : </strong> <span class="float-right">
                                            @if($code['coupon_code']!=null)
                                                {{$code['coupon_code']}}
                                            @else
                                            No Coupon
                                            @endif
                                        </span></p>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->

                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <h5>Billing Address</h5>
                                    <address>
                                            @if($shipping_array['customer_name'])
                                                {{$shipping_array['customer_name']}}
                                                <br>
                                                {{ $shipping_array['customer_address']}}
                                                <br>
                                                {{ $shipping_array['customer_email']}}
                                                <br>
                                                <abbr title="Phone">(84):</abbr> {{ $shipping_array['customer_phone']}}
                                            @else
                                                {{$shipping_array['shipping_name']}}
                                                <br>
                                                {{ $shipping_array['shipping_address']}}
                                                <br>
                                                {{ $shipping_array['shipping_email']}}
                                                <br>
                                                <abbr title="Phone">(84):</abbr> {{ $shipping_array['shipping_phone']}}
                                            @endif
                                    </address>
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <h5>Shipping Address</h5>
                                    <address>
                                        {{$shipping_array['shipping_name']}}
                                        <br>
                                        {{ $shipping_array['shipping_address']}}
                                        <br>
                                        {{ $shipping_array['shipping_email']}}
                                        <br>
                                        <abbr title="Phone">(84):</abbr> {{ $shipping_array['shipping_phone']}}
                                    </address>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table mt-4 table-centered">
                                            <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th style="width: 20%">Price</th>
                                                <th style="width: 20%">Quantity</th>
                                                <th style="width: 20%" class="text-right">Total</th>
                                            </tr></thead>
                                            <tbody>
                                                @php
                                                    $sub_total = 0;
                                                    $total = 0;
                                                @endphp
                                                @foreach ($cart_array as $cart )
                                                    @php
                                                        $sub_total = $cart['product_qty']*$cart['product_price'];
                                                        $total+=$sub_total;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <b>{{$cart['product_name']}}</b> <br>
                                                            Size: {{$cart['product_size']}}
                                                        </td>
                                                        <td>{{number_format($cart['product_price'] ).' VNĐ' }}</td>
                                                        <td>{{ $cart['product_qty']}}</td>
                                                        <td class="text-right">{{number_format($cart['product_price'] * $cart['product_qty']).' VNĐ' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div> <!-- end table-responsive -->
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="clearfix pt-5">
                                        <h6 class="text-muted">Notes:</h6>

                                        <small class="text-muted">
                                            {{ $shipping_array['shipping_notes']}}
                                        </small>
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="float-right">
                                        <p>
                                            <b>SubTotal :</b>&nbsp;&nbsp;&nbsp;
                                                {{number_format($total,0,',','.').' VNĐ' }}
                                        </p>
                                        <p><b>Transport:</b> <span class="float-right">&nbsp;&nbsp;&nbsp;
                                            {{number_format($shipping_array['feeship'],0,',','.').' VNĐ' }}
                                        </span></p>
                                        <p>
                                            <b>Discount:</b>
                                            <span class="float-right">
                                                @if($shipping_array['discount'])
                                                    {{number_format($shipping_array['discount'],0,',','.').' VNĐ' }}
                                                @else
                                                    {{number_format(0,0,',','.').' VNĐ' }}
                                                @endif
                                            </span>
                                        </p>
                                        <p>
                                            <b>Total: </b>&nbsp;&nbsp;&nbsp;
                                            <span class="float-right">
                                                @if($shipping_array['discount'])
                                                    {{number_format($total + $shipping_array['feeship'] - $shipping_array['discount'],0,',','.').' VNĐ' }}
                                                @else
                                                {{number_format($total + $shipping_array['feeship'],0,',','.').' VNĐ' }}
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div> <!-- end card-box -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            <!-- end content -->
        <!-- container -->
    </div>
    <!-- content -->
</div>
    <script src="{{URL::asset('public/backend/js/vendor.min.js')}}"></script>
	<script src="{{URL::asset('public/backend/js/app.min.js')}}"></script>
    <script src="{{URL::asset('public/backend/js/jquery.js')}}"></script>
</body>
</html>
