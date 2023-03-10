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
                        <h3>Transactions</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <div class="section general-info">
                        <div class="info">
                            <div class="table-responsive">
                                <table class="table table-bordered"  id="transaction">
                                    <thead>
                                        <tr>
                                        <th class="text-center">#</th>
                                        <th style="min-width: 190px;">Product Name</th>
                                        <th style="min-width: 190px;">Shipping Name</th>
                                        <th style="min-width: 100px;" >USD Price</th>
                                        <th style="min-width: 150px;" >Matic Price</th>
                                        <th>Quantity</th>
                                        <th style="min-width: 150px;" >Order Total</th>
                                        <th>Status</th>
                                        <!-- <th colspan="1">Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($transaction) > 0)
                                            @foreach($transaction as $key => $value)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>{{$value->name}}</td>
                                                    <td>{{$value->Shipping_FirstName}}</td>
                                                    <td>{{round($value->UsdPrice, 7)}}</td>
                                                    <td>{{$value->T_Amount}}</td>
                                                    <td>{{$value->Quantity}}</td>
                                                    <td>{{$value->Order_total}}</td>
                                                    <td><span class="{{$value->Status == 'Pending' ? 'badge badge-warning' :'badge badge-success'}}">{{$value->Status}}</span></td>
                                                    <!-- <td><a href="{{ route('order-management.edit', $value->Order_ID) }}" class="btn btn-success btn-sm">View</a></td> -->
                                                </tr>
                                            @endforeach
                                        @else
                                            <td style="text-align:center;" colspan="8">No Record Found</td>
                                        @endif

                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination_dv">

                                <div class="showing_records"><p>Showing {{($transaction->currentPage()-1)* $transaction->perPage()+($transaction->total() ? 1:0)}} to {{($transaction->currentPage()-1)*$transaction->perPage()+count($transaction)}}  of  {{$transaction->total()}}  Results</p></div> 
                                {{ $transaction->links() }}
                                
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

        // $('.dlt_btn').click(function(e){
        //     var id = $(this).data('id');
        //     if(confirm('Are you sure')){
        //         $('#deleteForm_'+id).submit();
        //     }else{
        //         e.preventDefault();
        //     }
        // });
    });
</script>