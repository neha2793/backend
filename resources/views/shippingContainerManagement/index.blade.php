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
                        <h3>Shipping Container Management</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <div class="section general-info">
                        <div class="info">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="shippingContainerTBL">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Image</th>
                                            <th style="min-width: 190px;">Name</th>
                                            <th>Description</th>
                                            <th style="min-width: 150px;" >Visit Count</th>
                                            <th style="min-width: 130px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($shippingContainer) > 0)
                                            @foreach($shippingContainer as $key => $value)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>
                                                        <div class="tbl_product_img">
                                                            <img src="{{$value->Featured_Image? env('API_BASE_URL').$value->Featured_Image:''}}" height="50px" alt="" class="img-fluid">
                                                        <div>
                                                    </td>
                                                    <td>{{$value->Name}}</td>
                                                    <td><div class="txt_limit">{{$value->Description}}</div></td>
                                                    <td>{{$value->Visit_count}}</td>
                                                    <td class="">
                                                        <ul class="table-controls">
                                                            <li><a class="edit_btn" href="{{ route('shipping-container-management.edit', $value->Sc_ID) }}"  data-toggle="tooltip" data-placement="top" title="View">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                            </a></li>
                                                            <li>
                                                                <form method="post" action="{{route('shipping-container-management.destroy',$value->Sc_ID)}}" id="deleteForm_{{$value->Sc_ID}}">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button type="submit"  class="dlt_btn" data-id="{{$value->Sc_ID}}" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
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
                                <div class="showing_records"><p>Showing {{($shippingContainer->currentPage()-1)* $shippingContainer->perPage()+($shippingContainer->total() ? 1:0)}} to {{($shippingContainer->currentPage()-1)*$shippingContainer->perPage()+count($shippingContainer)}}  of  {{$shippingContainer->total()}}  Results</p></div> 
                                    {{ $shippingContainer->links() }}

                                </div>
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
    });
</script>