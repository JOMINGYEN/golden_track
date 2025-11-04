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
                                <a href="{{URL::to('/transport-fee')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Đặt Lại</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Phí Vận Chuyển</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                <div class="col-12 ">
                    <div class="card-box">
                        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"  id="myTable">
                            <thead class="bg-light">
                            <tr>
                                <th class="font-weight-medium">Thành Phố</th>
                                <th class="font-weight-medium">Quận Huyện</th>
                                <th class="font-weight-medium">Xã Phường</th>
                                <th class="font-weight-medium">Phí Vận Chuyển</th>
                                <th class="font-weight-medium">Ngày Giao Hàng</th>
                            </tr>
                            </thead>
                            <tbody class="font-14 load-transport-fee">
                                @foreach ($all_fee as $key=>$value)
                                <tr>
                                    <td>{{ $value->City->tinhthanhpho_name  }} </td>
                                    <td>{{ $value->Province->quanhuyen_name  }} </td>
                                    <td>{{ $value->Wards->xaphuongthitran_name }}  </td>
                                    <td contenteditable data-feeship_id="{{ $value->id }}" class="fee-edit">{{ number_format( $value->phivanchuyen_phi_van_chuyen,0,',','.', ) }}</td>
                                    <td contenteditable data-feeship_id="{{ $value->id }}" class="fee-edit-day">{{ $value->phivanchuyen_ngay_giao_hang_du_kien }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination pagination-rounded mb-3">
                            {{$all_fee->links('layout.paginationlinks') }}
                            </ul>
                        </nav>
                    </div>
                    {{--  {!!$fee->links()!!}  --}}
                </div><!-- end col -->
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Thêm Phí Vận Chuyển</h4>
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
                                        <form class="form-horizontal" action="{{URL::to('/transport-fee-add')}}" method="POST">
                                            @csrf
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" for="example-email">City</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Thành Phố</label>
                                                    <select name="city" id="city" class="choose city form-control">
                                                        <option>---Chọn Thành Phố---</option>
                                                        @foreach ($city as $key=>$cty)
                                                            <option value="{{$cty->id}}">{{ $cty->tinhthanhpho_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" for="example-email">Province</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Quận Huyện</label>
                                                    <select name="province" id="province" class="choose province form-control">
                                                        <option>---Chọn Quận Huyện---</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" for="example-email">Wards</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Xã Phường</label>
                                                    <select name="wards" id="wards" class="wards form-control">
                                                        <option >---Chọn Xã Phường Thị Trấn---</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Phí Vận Chuyển</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Phí Vận Chuyển</label>
                                                    <input type="number" min="1" name="transport_fee" required="" class="form-control transport_fee">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Ngày Vận Chuyển</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Số Ngày Vận Chuyển</label>
                                                    <input type="number" min="1" name="transport_fee_day" required="" class="form-control transport_fee_day">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <button type="submit" name="transport_fee_add" class="transport-fee-add btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end col-->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card-box -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
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
