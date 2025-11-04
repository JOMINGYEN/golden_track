@extends('admin.index_layout_admin')
@section('content')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                <a href="{{URL::to('/product-discount-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> Add New</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Product Order</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" cellspacing="0" id="tickets-table">
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
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">#</th>
                                    <th class="font-weight-medium">Images</th>
                                    <th class="font-weight-medium">Name</th>
                                    <th class="font-weight-medium">Price</th>
                                    
                                </tr>
                                </thead>
                                <tbody class="font-14">
                                    <form >
                                        @csrf
                                            @foreach ($all_product_in_stock as $k=>$in_stock)
                                                <tr>
                                                    <input type="hidden" value="{{ $in_stock->sanpham_id }}" class="product_id_{{ $in_stock->sanpham_id }}">
                                                    <input type="hidden" value="{{ $in_stock->Product->sanpham_ten }}" class="product_name_{{ $in_stock->sanpham_id }}">
                                                    <input type="hidden" value="{{number_format( $in_stock->Product->sanpham_gia_ban ,0,',','') }}" class="product_price_{{ $in_stock->sanpham_id }}">
                                                    <input type="hidden" value="{{ $in_stock->size_id }}" class="product_size_id_{{ $in_stock->sanpham_id }}">
                                                    <input type="hidden" value="{{ $in_stock->Size->size }}" class="product_size_name_{{ $in_stock->sanpham_id }}">
                                                    <input type="hidden" value="{{ $in_stock->sanphamtonkho_so_luong_ton }}" class="product_in_stock_{{ $in_stock->sanpham_id }}">
                                                    <td>
                                                        <button type="button" data-id_product="{{ $in_stock->sanpham_id}}" name="add-order-admin" class="btn btn-success waves-effect waves-light btn-sm add-order-admin">
                                                        <i class="mdi mdi-plus-circle mr-1"></i>Add</button>
                                                    </td>
                                                    <td>
                                                        <a href="javascript: void(0);">
                                                            <img src="{{asset('public/uploads/admin/product/'.$in_stock->Product->sanpham_anh)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                                        </a>
                                                    </td>
                                                    <td>{{$in_stock->Product->sanpham_ten }}</td>
                                                   
                                                    <td>{{$in_stock->Size->size }}</td>
                                                </tr>
                                            @endforeach
                                    </form>

                                </tbody>
                            </table>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->
                <nav>
                <ul class="pagination pagination-rounded mb-3">
                   {{$all_product_in_stock->links('layout.paginationlinks') }}
                </ul>
            </nav>
            <!-- end content -->
            <!-- end page title -->
        </div>
        <!-- container -->
    </div>
    <!-- content -->
    <!-- Footer Start -->
    @include('admin.blocks.footer_admin')
    <!-- end Footer -->
</div>
@endsection
