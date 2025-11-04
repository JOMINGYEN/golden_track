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
                                <a href="{{URL::to('/headershow')}}" class="btn btn-success waves-effect waves-light"><i class="ti-arrow-left mr-1"></i>Quay Lại Header</a>
                                <a href="{{URL::to('/headershow-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">SHOES</a></li>
                            <li class="breadcrumb-item active">Cập Nhật Header</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title">Thông Tin Header</h4>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">
                                        <form action="{{ URL::to('/headershow-save-edit/'.$headershow->id) }}" class="form-horizontal" role="form"  method="post">
                                           {{ csrf_field() }}
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label" >Thông Tin Header</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Nội Dung</label>
                                                    <input type="text" value="{{$headershow->headerquangcao_noi_dung}}" name="header_content" required="" class="form-control" placeholder="Nội dung">
                                                    @error('header_content')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                    <label class="col-form-label">Liên Kết</label>
                                                    <input type="text" value="{{$headershow->headerquangcao_lien_ket}}"  name="header_link" required="" class="form-control" placeholder="Liên kết">
                                                    @error('header_link')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                    <label class="col-form-label">Thứ Tự Hiển Thị</label>
                                                    <input type="number" value="{{$headershow->headerquangcao_thu_tu}}"  min="1" name="header_no" required="" class="form-control" placeholder="Thứ tự hiển thị">
                                                    @error('header_no')
                                                    <p class="alert alert-danger"> {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Trạng Thái</label>
                                                <div class="col-sm-10">
                                                    <label class="col-form-label">Trạng Thái</label>
                                                    <select name="header_status" class="form-control">
                                                        @if( $headershow->headerquangcao_trang_thai ==1)
                                                            <option selected value="1">Hiển Thị</option>
                                                            <option value="0">Ẩn</option>
                                                        @else
                                                            <option value="1">Hiển Thị</option>
                                                            <option selected value="0">Ẩn</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <div class="text-lg-right mt-3 mt-lg-0">
                                                        <button type="submit" class="btn btn-success waves-effect waves-light mt-3"><i class="mdi mdi-content-save mr-1"></i>Lưu</button>
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
