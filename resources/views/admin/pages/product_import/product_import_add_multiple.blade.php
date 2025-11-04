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
                                <a href="{{URL::to('/product-import')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Nhập Hàng</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Thêm Đơn Nhập</li>
                        </ol>
                    </div>

                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Thông Tin Nhập Hàng</h4>
                            <hr>
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">
                                            <form action="{{URL::to('/product-import-add-multiple-save')}}" method="POST" class="form-horizontal">
                                                @csrf
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="header-title">Sản Phẩm</h4>
                                                            <table id="scroll-vertical-datatable"class="table dt-responsive nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Chọn</th>
                                                                        <th>Ảnh</th>
                                                                        <th>Sản Phẩm</th>
                                                                        
                                                                        <th>Thương Hiệu</th>
                                                                        {{--  <th>Category</th>
                                                                        <th>Collection</th>  --}}
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($all_product as $key=>$product)
                                                                        <tr>
                                                                            <td>
                                                                                <button type="button" data-id_product="{{ $product->id}}" name="add-queue" class="btn btn-success waves-effect waves-light btn-sm add-queue" >
                                                                                <i class="mdi mdi-plus-circle mr-1"></i>Add</button>
                                                                            </td>
                                                                            <input type="hidden" value="{{ $product->id }}" class="product_id_{{ $product->id }}">
                                                                            <input type="hidden" value="{{ $product->sanpham_ten }}" class="product_name_{{ $product->id }}">
                                                                            <input type="hidden" value="{{ $product->sanpham_anh }}" class="product_image_{{ $product->id }}">
                                                                            <td>
                                                                                <a href="javascript: void(0);">
                                                                                    <img src="{{asset('public/uploads/admin/product/'.$product->sanpham_anh)}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                                                                </a>
                                                                            </td>
                                                                            <td>{{$product->sanpham_ten }}</td>
                                                                            
                                                                            <td>{{$product->Brand->thuonghieu_ten }}</td>
                                                                            {{--  <td>{{$product->ProductType->loaisanpham_ten }}</td>
                                                                            <td>{{$product->Collection->dongsanpham_ten }}</td>  --}}
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            <nav>
                                                                <ul class="pagination pagination-rounded mb-3">
                                                                    {{ $all_product->links('layout.paginationlinks') }}
                                                                </ul>
                                                            </nav>
                                                        </div> <!-- end card body-->
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                    <div class="card-body">
                                                        <label class="col-form-label">Mã Đơn Nhập</label>
                                                        <input type="text" name="product_import_no" required="" class="form-control product_import_no" placeholder="Mã đơn nhập">
                                                        @error('product_import_no')
                                                        <p class="alert alert-danger"> {{ $message }}</p>
                                                        @enderror
                                                        <label class=" col-form-label"> Ngày Nhập</label>
                                                        <input type="date" name="product_import_day" required="" class="form-control product_import_day" >
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label  class="col-form-label">Nhân Viên</label>
                                                                @foreach ($get_admin as $key => $admin )
                                                                    <input type="text" value="{{ $admin->admin_ten  }}" readonly class="form-control product_import_staff">
                                                                    <input type="hidden" name="product_import_staff" value="{{ $admin->id }}"  class="form-control product_import_staff">
                                                                @endforeach
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="col-form-label">Trạng Thái</label>
                                                                <select name="product_import_status" required="" class="form-control product_import_status">
                                                                    <option value="1">Hoàn Thành</option>
                                                                    <option value="0">Chưa Hoàn Thành</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label class="col-form-label">Nhà Cung Cấp</label>
                                                                <select name="product_import_supplier" class="form-control product_import_supplier">
                                                                    @foreach ($all_supplier as $key => $supplier)
                                                                    <option value="{{ $supplier->id }}">{{ $supplier->nhacungcap_ten }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label class="col-form-label">Sản Phẩm Nhập</label>
                                                    <div class="table-responsive" id="ajax-queue">
                                                        <table class="table table-hover  mb-0">
                                                            <thead>
                                                            <tr>
                                                                <td>#</td>
                                                                <td>Ảnh</td>
                                                                <td>Sản Phẩm</td>
                                                                <td>Số Lượng</td>
                                                                <td>Giá Nhập</td>
                                                                <td>Size</td>
                                                                <td>Tổng</td>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="show-list-product">
                                                                @if(Session::get('queue'))
                                                                @foreach (Session::get('queue') as $key=> $product )
                                                                <tr>
                                                                    <td scope="row">
                                                                        <button type="button" data-id_product="{{ $product['session_id']}}" name="delete-row-queue" class="delete-row-queue btn btn-danger waves-effect waves-light btn-sm">
                                                                            <i class="mdi mdi-close mr-1"></i>
                                                                        </button>
                                                                    </td>
                                                                    <td>
                                                                        <a href="javascript: void(0);">
                                                                            <img src="{{asset('public/uploads/admin/product/'.$product['product_image'])}}" alt="contact-img" title="contact-img" class="rounded-circle avatar-lg img-thumbnail">
                                                                        </a>
                                                                    </td>
                                                                    <td>{{ $product['product_name'] }}</td>
                                                                    <input type="hidden" name="session_id[{{ $product['session_id'] }}]" class="product_session_id_{{ $product['session_id'] }}" value="{{ $product['session_id'] }}">
                                                                    <input type="hidden" name="product_id[{{ $product['session_id'] }}]" class="product_id_{{ $product['session_id']}}" value="{{ $product['product_id'] }}">
                                                                    <input type="hidden" name="product_name[{{ $product['session_id'] }}]" class="product_name_{{ $product['session_id']}} " value="{{ $product['product_name'] }}">
                                                                    <td><input type="number" min="1" value="{{ $product['product_quantity'] }}" name="product_quantity[{{ $product['session_id'] }}]" class="product_quantity_{{ $product['session_id']}} product_quantity form-control" ></td>
                                                                    <td><input type="number" min="1" value="{{ $product['product_price'] }}" name="product_price[{{ $product['session_id'] }}]" class="product_price_{{ $product['session_id'] }} product_price form-control"></td>
                                                                    <td>
                                                                         
                                                                    </td>
                                                                    <td><input type="text" readonly name="product_total[{{ $product['session_id'] }}]" class="form-control product_total"></td>
                                                                </tr>
                                                            @endforeach
                                                                @endif

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <button type="submit" value="submit" name="" class="product-import-add-save add-save btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Lưu</button>
                                                    </div>
                                                    <div class="text-lg-left mt-3 mt-lg-0">
                                                        <div class="float-left">
                                                            <p><b>Tổng Cộng :</b></p>
                                                            <h3 class="total"></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div> <!-- end card-box -->
                    </div><!-- end col -->
                </div>
                <!-- end row -->
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
