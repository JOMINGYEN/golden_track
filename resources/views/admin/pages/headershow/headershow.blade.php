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
                                <a href="{{URL::to('/headershow-add')}}" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i>Thêm Mới</a>
                            </div>
                        </div>
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Header</li>
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
                                    <th class="font-weight-medium">Nội Dung</th>
                                    <th class="font-weight-medium">Liên Kết</th>
                                    <th class="font-weight-medium">Thứ Tự</th>
                                    <th class="font-weight-medium">Trạng Thái</th>
                                    <th class="font-weight-medium">Thao Tác</th>
                                </tr>
                                </thead>
                                <tbody class="font-14">
                                    @foreach ($all_headershow as $key=>$headershow)
                                    <tr>
                                        <td>
                                            {{ $headershow->headerquangcao_noi_dung }}
                                        </td>
                                        <td>
                                            {{ $headershow->headerquangcao_lien_ket }}
                                        </td>
                                        <td>
                                            {{ $headershow->headerquangcao_thu_tu }}
                                        </td>
                                        <td>
                                            <span class="badge">
                                                <?php
                                                if($headershow->headerquangcao_trang_thai==1)
                                                { ?>
                                                <a href="{{URL::to ('/unactive-headershow/'.$headershow->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-up"></i></a>
                                                <?php
                                                }else
                                                { ?>
                                                    <a href="{{URL::to ('/active-headershow/'.$headershow->id)}}"> <i class="fa fa-thumbs-styling fa-thumbs-down"></i></a>
                                                <?php
                                                }
                                                ?>
                                               </span>
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{URL::to('/headershow-edit/'.$headershow->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Cập Nhật</a>
                                                    <a class="dropdown-item" href="{{URL::to('/headershow-delete/'.$headershow->id)}}" onclick="return confirm('Xóa header?')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Xóa</a>
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
                        {{ $all_headershow->links('layout.paginationlinks') }}
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
