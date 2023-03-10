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
                        <h3>NFT Management</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <div class="section general-info">
                        <div class="info">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="usersNFTTBL">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Image</th>
                                            <th style="min-width: 190px;">Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th style="min-width: 100px;">USD Price</th>
                                            <th>Status</th>
                                            <th class="" style="min-width: 130px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($usersNFT) > 0)
                                            @foreach($usersNFT as $key => $value)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>
                                                        <div class="tbl_product_img"><img alt="Product" height="50px" class="img-fluid" src="{{$value->image ? $value->image:''}}"></div>
                                                    </td>
                                                    <td>{{$value->name}}</td>
                                                    <td><div class="txt_limit">{{$value->description}}</div></td>
                                                    <td>{{$value->price}}</td>
                                                    <td>{{round($value->UsdPrice, 7)}}</td>
                                                    <td class=""><span class="{{$value->status == 'sold' ? 'badge badge-warning' :'badge badge-success'}}">{{$value->status}}</span></td>
                                                    <td class="">
                                                        <ul class="table-controls">
                                                            <li><a  href="{{ route('nft-management.edit', $value->id) }}"  data-toggle="tooltip" data-placement="top" title="View" class="edit_btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                </a></li>
                                                            <li>
                                                                <form method="post" action="{{route('nft-management.destroy',$value->id)}}" id="deleteForm_{{$value->id}}">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button type="submit" class="dlt_btn" data-id="{{$value->id}}" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
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

                                <div class="showing_records"><p>Showing {{($usersNFT->currentPage()-1)* $usersNFT->perPage()+($usersNFT->total() ? 1:0)}} to {{($usersNFT->currentPage()-1)*$usersNFT->perPage()+count($usersNFT)}}  of  {{$usersNFT->total()}}  Results</p></div> 
                                {{ $usersNFT->links() }}
                                
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