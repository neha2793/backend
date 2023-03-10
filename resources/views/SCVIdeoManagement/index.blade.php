@include('admin_layout.header')
<!-- BEGIN LOADER -->
<div id="load_screen"> <div class="loader"> <div class="loader-content">
    <div class="spinner-grow align-self-center"></div>
</div></div></div>
<!--  END LOADER -->
<!--  BEGIN CONTENT AREA  -->
<div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('admin_layout.sidebar')          
        <!--  END SIDEBAR  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="page-header header_btn">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="page-title">
                                <h3>SC Video Management</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <!--<a class="btn btn-success" href="{{route('SC-video-management.create')}}">Create</a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <div class="section general-info">
                        <div class="info">
                            <div class="table-responsive">
                                <table class="table table-bordered"  id="SCVideo">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Video</th>
                                            <th style="min-width: 130px;">Name</th>
                                            <th>Description</th>
                                            <th style="min-width:200px">Redirection Link</th>
                                            <th class="" style="min-width: 130px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($SCVideo) > 0)
                                            @foreach($SCVideo as $key => $value)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    @if($value->type == 'Video')
                                                    
                                                        <td>
                                                            <div class="tbl_product_img">
                                                                <i class="fa fa-play-circle" aria-hidden="true"></i>
                                                                <input type="button" data-toggle="modal" data-target="#exampleModalLong" id="videoClick" value="" class=""  name="{{$value->video ? env('API_BASE_URL').$value->video:''}}" /> 
                                                                <video src="{{$value->video ? env('API_BASE_URL').$value->video:''}}" alt="" style="height: 50px;"  class="img-fluid"></video></td>
                                                            </div>
                                                    @else
                                                        <td>
                                                            <div class="tbl_product_img">
                                                                <img src="{{$value->video ? env('API_BASE_URL').$value->video:''}}" alt="" style="height: 50px;" class="img-fluid">
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>{{$value->name}}</td>
                                                    <td > <div class="txt_limit">{{$value->description}}</div></td>
                                                    
                                                    <td><a href="{{$value->redirection_link}}">{{$value->redirection_link}}</a></td>
                                                    <td  class="">
                                                        <ul class="table-controls">
                                                            <li><a href="{{ route('SC-video-management.edit', $value->id) }}"  data-toggle="tooltip" data-placement="top" title="Edit" class="edit_btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                            <li>
                                                                <form method="post" action="{{route('SC-video-management.destroy',$value->id)}}" id="deleteForm_{{$value->id}}">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button type="submit"  class="dlt_btn" data-id="{{$value->id}}" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <td rowspan="7">No Record Found</td>
                                        @endif
            
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination_dv">

                                <div class="showing_records"><p>Showing {{($SCVideo->currentPage()-1)* $SCVideo->perPage()+($SCVideo->total() ? 1:0)}} to {{($SCVideo->currentPage()-1)*$SCVideo->perPage()+count($SCVideo)}}  of  {{$SCVideo->total()}}  Results</p></div> 
                                {{ $SCVideo->links() }}

                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Video</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <video width="320" src="" height="240"   controls autoPlay >
                            
                        </video>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                    </div>
                </div>
            </div>

            @include('admin_layout.footer')
        </div>
    </div>
<!--  END CONTENT AREA  -->

<script>
    $(document).ready(function() {
        toastr.options.timeOut = 10000;
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
        @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @endif

        $('.dlt_btn').click(function(e){

          
            var id = $(this).data('id');
            if(confirm('Are you sure')){
                $('#deleteForm_'+id).submit();
            }else{
                e.preventDefault();
            }
        });
     
        $('body').delegate('#videoClick', 'click', function(e){
            e.preventDefault();

            $('.modal-body video').attr('src',$(this).attr('name'));
            
        })
    });
</script>