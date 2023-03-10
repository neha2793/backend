@inject('carbon', 'Carbon\Carbon')
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
                <div class="page-header">
                    <div class="page-title">
                        <h3>Slime Seat Management</h3>
                    </div>
                    <!-- <div class="page-title">
                        <a class="btn btn-success" href="{{route('slime-seat-management.create')}}">Create</a>
                    </div> -->
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <div class="section general-info">
                        <div class="info">
                            <div class="table-responsive">
                                <table class="table table-bordered"  id="TBL_slime_seats">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Image</th>
                                            <th style="min-width: 190px;">Name</th>
                                            <th>Description</th>
                                            <th style="min-width: 150px;">Matic Price</th>
                                            <th style="min-width: 150px;">USD Price</th>
                                            <th>Date</th>
                                            <th style="min-width: 130px;" >Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($TBL_slime_seats) > 0)
                                            @foreach($TBL_slime_seats as $key => $value)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>
                                                        <div class="tbl_product_img"><img style="height: 50px;" 
                                                        
                                                        src="{{$value->featured_image ?  env('API_BASE_URL').$value->featured_image: asset('public/assets/images/no-image.jpg') }}" 
                                                        
                                                        alt="" class="img-fluid"></div>
                                                    </td>
                                                    <td>{{$value->name}}</td>
                                                    <td><div class="txt_limit">{{$value->Description}}</div></td>
                                                    <td>{{$value->Price}}</td>
                                                    <td>{{round($value->USDPrice, 7)}}</td>
                                                    <td>{{$carbon::parse($value->Date_created)->format('m/d/Y') }}</td>
                                                    <td class="">
                                                        <ul class="table-controls">
                                                            <li><a class="edit_btn" href="{{ route('slime-seat-management.edit', $value->S_ID) }}"  data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                            <li>
                                                                <form method="post" action="{{route('slime-seat-management.destroy',$value->S_ID)}}" id="deleteForm_{{$value->S_ID}}">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button type="submit"  class="dlt_btn" data-id="{{$value->S_ID}}" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
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

                                <div class="showing_records"><p>Showing {{($TBL_slime_seats->currentPage()-1)* $TBL_slime_seats->perPage()+($TBL_slime_seats->total() ? 1:0)}} to {{($TBL_slime_seats->currentPage()-1)*$TBL_slime_seats->perPage()+count($TBL_slime_seats)}}  of  {{$TBL_slime_seats->total()}}  Results</p></div> 
                                {{ $TBL_slime_seats->links() }}
                                
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
                        <video width="320" src="" height="240"   controls autoPlay style=" margin: 0 0 0 70px" >
                            
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