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
                        <ol class="breadcrumb page-title">
                            <li class="breadcrumb-item"><a href="index.php">SHOES</a></li>
                            <li class="breadcrumb-item active">Bình Luận Đánh Giá</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- content -->
            <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form class="form-inline" action="{{URL::to('/admin-search-comment')}}" method="GET">
                                        <div class="form-group mr-3">
                                            <label for="status-select" class="mr-2">Từ Ngày</label>
                                            <input type="date" name="search_admin_from_day_comment"  class="form-control">
                                        </div>
                                        <div class="form-group mr-3">
                                            <label for="status-select" class="mr-2">Đến Ngày</label>
                                            <input type="date" name="search_admin_to_day_comment"  class="form-control">
                                        </div>
                                        <div class="form-group mr-3">
                                            <label for="status-select" class="mr-2">Trạng Thái</label>
                                            <select class="custom-select" name="search_select_status_comment" id="status-select">
                                                @if(isset($search_filter))
                                                    @if($search_filter['search_select_status_comment']==-1)
                                                        <option selected="" value="-1">Tất Cả</option>
                                                        <option value="1">Duyệt</option>
                                                        <option value="0">Chưa Duyệt</option>
                                                    @elseif($search_filter['search_select_status_comment']==1)
                                                        <option value="-1">Tất Cả</option>
                                                        <option selected="" value="1">Duyệt</option>
                                                        <option value="0">Chưa Duyệt</option>
                                                    @elseif($search_filter['search_select_status_comment']==0)
                                                        <option value="-1">Tất Cả</option>
                                                        <option value="1">Duyệt</option>
                                                        <option selected="" value="0">Chưa Duyệt</option>
                                                    @endif
                                                @else
                                                    <option selected="" value="-1">Tất Cả</option>
                                                    <option value="1">Duyệt</option>
                                                    <option value="0">Chưa Duyệt</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group mr-3">
                                            <button type="submit" href="index_save_add.php" class="btn btn-success waves-effect waves-light">Tìm</button>
                                        </div>
                                        <div class="form-group mr-3">
                                            <a href="{{URL::to('/comment')}}" class="btn btn-success waves-effect waves-light">Tất Cả</a>
                                        </div>
                                    </form>
                                </div>
                               <!-- end col-->
                            </div> <!-- end row -->
                        </div> <!-- end card-box -->
                    </div><!-- end col-->
                </div>
                <!-- end row -->
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
                                <div id="notify_comment"></div>
                                <thead class="bg-light">
                                <tr>
                                    <th class="font-weight-medium">Duyệt</th>
                                    <th class="font-weight-medium">Tên</th>
                                    <th class="font-weight-medium">Bình Luận</th>
                                    <th class="font-weight-medium">Điểm</th>
                                    <th class="font-weight-medium">Ngày</th>
                                    <th class="font-weight-medium">Sản Phẩm</th>
                                    <th class="font-weight-medium">Thao Tác</th>
                                </tr>
                                </thead>
                                <tbody class="font-14">
                                    @foreach ($comment_customer as $key=>$comment)
                                    <tr>
                                        <td>
                                            @csrf
                                            @if($comment->binhluan_trang_thai==1)
                                                <input type="button" data-comment_status="0" data-comment_id="{{$comment->id}}" id="{{$comment->sanpham_id}}" class="btn btn-primary btn-xs comment_approval" value="Approval" >
                                            @else
                                                <input type="button" data-comment_status="1" data-comment_id="{{$comment->id}}" id="{{$comment->sanpham_id}}" class="btn btn-danger btn-xs comment_approval" value="Unapproval" >
                                            @endif
                                        </td>
                                        <td>
                                            {{ $comment->binhluan_ten_nguoi_danh_gia }}
                                        </td>
                                        <td>
                                            {{ $comment->binhluan_noi_dung }}
                                            {{--  <style type="text/css">
                                                ul.list_rep li {
                                                  list-style-type: decimal;
                                                  color: blue;
                                                  margin: 5px 40px;
                                              }
                                              </style>
                                              <ul class="list_rep">
                                                Trả lời :
                                                @foreach($comment_admin as $key => $ad_comment)
                                                  @if($ad_comment->binhluan_id_phan_hoi==$comment->id)
                                                    <li> {{$ad_comment->binhluan_noi_dung}}</li>
                                                  @endif
                                                @endforeach
                                              </ul>
                                              @if($comment->binhluan_trang_thai==0)
                                              <br/><textarea class="form-control reply_comment_{{$comment->id}}" rows="5"></textarea>
                                              <br/><button class="btn btn-success btn-xs btn-reply-comment" data-product_id="{{$comment->sanpham_id}}"  data-comment_id="{{$comment->id}}">Reply To Comment</button>
                                              @endif  --}}
                                        </td>
                                        <td>
                                            @for($count = 1; $count <=5; $count++)
                                                @if($count <= $comment->binhluan_diem_danh_gia)
                                                    <i class="fa fa-star ratting_review"></i>
                                                @else
                                                    <i class="fa fa-star ratting_no_review"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime( $comment->binhluan_ngay_danh_gia)) }}
                                        </td>
                                        <td>
                                            {{ $comment->Product->sanpham_ten }}
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{URL::to('/show-comment-detail/'.$comment->id)}}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Chi Tiết</a>
                                                    <a class="dropdown-item" href="{{URL::to('/delete-comment/'.$comment->id)}}" onclick="return confirm('Xóa bình luận?')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Xóa</a>
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
                    {{ $comment_customer->links('layout.paginationlinks') }}
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
