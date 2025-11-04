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
                            <li class="breadcrumb-item active">Product Import Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Product Import</h4>
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
                                            <form action="{{URL::to('/product-import-add-save')}}" method="POST" class="form-horizontal">
                                                {{ csrf_field() }}
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                    <div class="card-body">
                                                        <label class="col-form-label">Import No.</label>
                                                        <input type="text" name="product_import_no" required="" class="form-control product_import_no">
                                                        <label class=" col-form-label">Day</label>
                                                        <input type="date" name="product_import_day" required="" class="form-control product_import_day" >
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label  class="col-form-label">Staff</label>
                                                                @foreach ($get_admin as $key => $admin )
                                                                    <input type="text" value="{{ $admin->admin_ten  }}" readonly class="form-control product_import_staff">
                                                                    <input type="hidden" name="product_import_staff" value="{{ $admin->id }}"  class="form-control product_import_staff">
                                                                @endforeach
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="col-form-label">Supplier</label>
                                                                <select name="product_import_supplier" required="" class="form-control product_import_supplier">
                                                                    @foreach ($all_supplier as $key => $supplier)
                                                                        <option value="{{ $supplier->id }}">{{ $supplier->nhacungcap_ten}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <label class="col-form-label">Status</label>
                                                                <select name="product_import_status" required="" class="form-control product_import_status">
                                                                    <option value="0">Unfinished</option>
                                                                    <option value="1">Finish</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <div class="text-lg-right mt-3 mt-lg-0">
                                                                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-content-save mr-1"></i>Save</button>
                                                                </div>
                                                            </div>
                                                        </div
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                                                <th class="font-weight-medium">ID</th>
                                                <th class="font-weight-medium">Date</th>
                                                <th class="font-weight-medium">Total</th>
                                                <th class="font-weight-medium">Status</th>
                                                <th class="font-weight-medium">Action</th>
                                            </tr>
                                            </thead>

                                            <tbody class="font-14">
                                                @foreach ($all_product_import as $key=>$product_import)
                                                <tr>
                                                    <td>
                                                        {{ $product_import->donnhaphang_ma_don_nhap_hang}}
                                                    </td>
                                                    <td>
                                                        {{ $product_import->donnhaphang_ngay_nhap }}
                                                    </td>
                                                    <td>
                                                        {{number_format( $product_import->donnhaphang_tong_tien).' VNĐ' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge">
                                                            <?php
                                                            if($product_import->donnhaphang_trang_thai==1)
                                                            { ?>
                                                            <a href="{{URL::to ('/unactive-product-import/'.$product_import->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-up"></i></a>
                                                            <?php
                                                            }else
                                                            { ?>
                                                                <a href="{{URL::to ('/active-product-import/'.$product_import->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-down"></i></a>
                                                            <?php
                                                            }
                                                            ?>
                                                           </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group dropdown">
                                                            <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="{{URL::to('/product-import-show-detail/'.$product_import->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Detail</a>
                                                                <a class="dropdown-item" href="{{URL::to('/product-import-edit/'.$product_import->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>
                                                                <a class="dropdown-item" href="index_order_detail.php"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- end col -->
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
