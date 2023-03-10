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
                        <h3>User Varification Request</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <div class="section general-info">
                        <div class="info">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="account_varification_listTBL">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th style="min-width: 150px;">Phone Number</th>
                                            <th style="min-width: 190px;">Wallet Address</th>
                                            <th style="min-width: 130px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($account_varification_list) > 0)
                                            @foreach($account_varification_list as $key => $value)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="usr-img-frame mr-2 rounded-circle">
                                                                <img alt="Profile" class="img-fluid rounded-circle" src="{{$value->profile_image ? env('API_BASE_URL').$value->profile_image:''}}">
                                                            </div>
                                                            <p class="align-self-center mb-0">{{$value->name}}</p>
                                                        </div>
                                                    </td>
                                                    <td>{{$value->email}}</td>
                                                    <td>{{$value->phone_number}}</td>
                                                    <td>{{$value->wallet_address}}</td>
                                                    <td>
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a class="edit_btn" href="{{ route('user-varification-request.edit', $value->id) }}"  data-toggle="tooltip" data-placement="top" title="View">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <td colspan="6" style="text-align:center">No Record Found</td>
                                        @endif
                                    
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination_dv">
                                <div class="showing_records"><p>Showing {{($account_varification_list->currentPage()-1)* $account_varification_list->perPage()+($account_varification_list->total() ? 1:0)}} to {{($account_varification_list->currentPage()-1)*$account_varification_list->perPage()+count($account_varification_list)}}  of  {{$account_varification_list->total()}}  Results</p></div> 
                                {{ $account_varification_list->links() }}
                                
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