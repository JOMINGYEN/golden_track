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
                                <a href="{{URL::to('/coupon-code-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Mã Khuyến Mãi</li>
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
                                    <th class="font-weight-medium">Tên</th>
                                    <th class="font-weight-medium">Mã</th>
                                    <th class="font-weight-medium">Số Lượng</th>
                                    <th class="font-weight-medium">Loại Mã</th>
                                    <th class="font-weight-medium">Giá Trị</th>
                                    <th class="font-weight-medium">Từ Ngày</th>
                                    <th class="font-weight-medium">Đến Ngày</th>
                                    <th class="font-weight-medium">Tình Trạng</th>
                                    <th class="font-weight-medium">Trạng Thái</th>
                                    <th class="font-weight-medium">Thao Tác</th>
                                </tr>
                                </thead>
                                <tbody class="font-14">
                                    @foreach ($all_coupon_code as $key=>$coupon_code)
                                    <tr>
                                        <td>
                                            {{ $coupon_code->makhuyenmai_ten_ma }}
                                        </td>
                                        <td>
                                            {{ $coupon_code->makhuyenmai_ma }}
                                        </td>
                                        <td>
                                            {{ $coupon_code->makhuyenmai_so_luong }}
                                        </td>
                                        <td>
                                            {{ $coupon_code->makhuyenmai_loai_ma?'Theo $':'Theo %' }}
                                        </td>
                                        
                                        <td>
                                            {{ date('d-m-Y', strtotime( $coupon_code->makhuyenmai_ngay_bat_dau)) }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime( $coupon_code->makhuyenmai_ngay_ket_thuc)) }}
                                        </td>
                                        <td>
                                            @if ($coupon_code->makhuyenmai_so_luong<=0)
                                            Số Lượng Đã Hết
                                            @elseif ($coupon_code->makhuyenmai_trang_thai==-1)
                                            Hết Hạn Sử Dụng
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge">
                                                <?php
                                                if($coupon_code->makhuyenmai_trang_thai==1)
                                                { ?>
                                                <a href="{{URL::to ('/unactive-coupon-code/'.$coupon_code->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-up"></i></a>
                                                <?php
                                                }else
                                                { ?>
                                                    <a href="{{URL::to ('/active-coupon-code/'.$coupon_code->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-down"></i></a>
                                                <?php
                                                }
                                                ?>
                                               </span>
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{URL::to('/coupon-code-edit/'.$coupon_code->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Cập Nhật</a>
                                                    <a class="dropdown-item" href="{{URL::to('/delete-coupon-code/'.$coupon_code->id)}}" onclick="return confirm('Xóa mã khuyến mãi?')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Xóa</a>
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
                <nav>
                    <ul class="pagination pagination-rounded mb-3">
                        {{ $all_coupon_code->links('layout.paginationlinks') }}
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
