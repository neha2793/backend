@include('admin_layout.header')
<link rel="stylesheet" type="text/css" href="{{asset('public/plugins/dropify/dropify.min.css')}}">
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
                        <h3>Slime Seat</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <form id="general-info" class="section general-info SCFOrm" method="POST" action="{{route('slime-seat-management.update',$TBL_slime_seats->S_ID)}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="account-content">
                            <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                        <div class="info">
                                            <h6 class="">Slime Seat Edit</h6>
                                            <div class="row">
                                              
                                                <div class="col-md-12">
                                                    <div class="upload_slime_seat">
                                                        <div class="form-group">
                                                            <label>Slime Seat Picture</label>
                                                            <div class="dropify-wrapper has-preview">
                                                                <div class="dropify-message">
                                                                    <span class="file-icon"></span> 
                                                                    <p>Click to Upload or Drag n Drop</p>
                                                                    <p class="dropify-error">Ooops, something wrong appended.</p>
                                                                </div>
                                                                <div class="dropify-loader" style="display: none;">
                                                                </div>
                                                                <div class="dropify-errors-container">
                                                                    <ul></ul>
                                                                </div>
                                                                <!-- <input type="file" name="featured_image"  id="input-file-max-fs" class="dropify featured_image" data-default-file="public/assets/img/200x200.jpg" data-max-file-size="2M"> -->
                                                                <button type="button" class="dropify-clear">
                                                                    <i class="flaticon-close-fill"></i>
                                                                </button>
                                                                <div class="dropify-preview" style="display: block;">
                                                                <span class="dropify-render">
                                                                    <img src="{{$TBL_slime_seats->featured_image ? env('API_BASE_URL').$TBL_slime_seats->featured_image:''}}" id="category-img-tag">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                               
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Upload Picture</label>
                                                        <div>
                                                            @for($i=0; $i < 5; $i++)
                                                                <div class="upload_sample_images">
                                                                    @if(isset($TBL_Slimeseat_Images[$i]))
                                                                        @if($TBL_Slimeseat_Images[$i]->Status == 1)
                                                                            <input type="hidden" name="img_{{$i+1}}" value="{{$TBL_Slimeseat_Images[$i]->Image_path}}">
                                                                        @endif
                                                                    @endif
                                                                    <div class="dropify-wrapper has-preview">
                                                                        
                                                                        <input type="file" value="{{isset($TBL_Slimeseat_Images[$i]) ?$TBL_Slimeseat_Images[$i]->Image_path : '' }}" name="img_{{$i+1}}" id="input-file-max-fs" class="dropify img_{{$i+1}}" data-default-file="public/assets/img/200x200.jpg" data-max-file-size="2M">
                                                                        <input type="hidden" name="img_id{{$i+1}}" id="" value="{{isset($TBL_Slimeseat_Images[$i]) ? $TBL_Slimeseat_Images[$i]->id : '' }}">
                                                                        @if(isset($TBL_Slimeseat_Images[$i]))
                                                                            @if($TBL_Slimeseat_Images[$i]->Status == 1)
                                                                                <button type="button" class="dropify-clear clear{{$i+1}}">
                                                                                    <i class="fa fa-times"></i>
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                        <div class="dropify-preview" style="display: block;">
                                                                            <span class="dropify-render">
                                                                                @if(isset($TBL_Slimeseat_Images[$i]))
                                                                                    @if($TBL_Slimeseat_Images[$i]->Status == 1)
                                                                                        <img src="{{env('API_BASE_URL').'/uploads/slimeseat/'.$TBL_Slimeseat_Images[$i]->Image_path}}" id="img_preview{{$i+1}}">
                                                                                    @else
                                                                                        <img src="{{asset('public/assets/img/200x200.jpg') }}" id="img_preview{{$i+1}}">
                                                                                    @endif
                                                                                @else
                                                                                    <img src="{{asset('public/assets/img/200x200.jpg') }}" id="img_preview{{$i+1}}">
                                                                                @endif

                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">

                                                    <div class="form-group">
                                                        <label for="name">Name</label>
                                                        <input type="text" readonly class="form-control mb-4"  id="name" placeholder="Name"  value="{{$TBL_slime_seats->name}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Description">Description</label>
                                                        <textarea  readonly class="form-control mb-4">{{$TBL_slime_seats->Description}}</textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="Price">Price</label>
                                                                <input type="text" readonly class="form-control mb-4"  id="Price" placeholder="Price"  value="{{$TBL_slime_seats->Price}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="UsdPrice">USD Price</label>
                                                                <input type="text" readonly class="form-control mb-4"  id="UsdPrice" placeholder="USD Price"  value="{{$TBL_slime_seats->UsdPrice}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="account-settings-footer">
                            <div class="as-footer-container">
                                <!-- <button id="multiple-reset" type="button" class="btn btn-warning">Reset All</button> -->
                                <button id="multiple-messages" type="submit" class="btn btn-primary">Save Changes</button>
                                <!-- <div class="blockui-growl-message" style="cursor: default;">
                                    <i class="flaticon-double-check"></i>&nbsp; Settings Saved Successfully
                                </div> -->
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>

            @include('admin_layout.footer')
        </div>
    </div>
<!--  END CONTENT AREA  -->

<script>
    $(document).ready(function(e) {
        toastr.options.timeOut = 10000;
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
        @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @endif

        document.querySelector(".img_1")
        .onchange = function(e) {
            if(e.target.files[0]){
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpg'){
                    let file = e.target.files[0];
                    let blobURL =URL.createObjectURL(e.target.files[0]);
                        document.querySelector("#img_preview1").src = blobURL;
                }else{
                    toastr.error('Only JPG file is allowed!');
                }
            }  
        }
        document.querySelector(".img_2")
        .onchange = function(e) {
            if(e.target.files[0]){
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();

                if(ext == 'jpg'){
                    let file = e.target.files[0];
                    let blobURL =URL.createObjectURL(e.target.files[0]);
                        document.querySelector("#img_preview2").src = blobURL;
                }else{
                    toastr.error('Only JPG file is allowed!');
                }

                    
            }  
            
        }
        document.querySelector(".img_3")
        .onchange = function(e) {
            if(e.target.files[0]){
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpg'){
                    let file = e.target.files[0];
                    let blobURL =URL.createObjectURL(e.target.files[0]);
                        document.querySelector("#img_preview3").src = blobURL;
                }else{
                    toastr.error('Only JPG file is allowed!');
                }

                    
            }  
            
        }
        document.querySelector(".img_4")
        .onchange = function(e) {
            if(e.target.files[0]){
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpg'){
                    let file = e.target.files[0];
                    let blobURL =URL.createObjectURL(e.target.files[0]);
                        document.querySelector("#img_preview4").src = blobURL;
                }else{
                    toastr.error('Only JPG file is allowed!');
                }
                    
            }  
            
        }
        document.querySelector(".img_5")
        .onchange = function(e) {
            if(e.target.files[0]){
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpg'){
                    let file = e.target.files[0];
                    let blobURL =URL.createObjectURL(e.target.files[0]);
                        document.querySelector("#img_preview5").src = blobURL;
                }else{
                    toastr.error('Only JPG file is allowed!');
                }

            }  
            
        }


        $('.clear1').click(function(){
            $(this).hide();
           $('#img_preview1').attr('src', '');
           $("input[name='img_1']").val("");
        });
        $('.clear2').click(function(){
            $(this).hide();
           $('#img_preview2').attr('src', '');
           $("input[name='img_2']").val("");
        });
        $('.clear3').click(function(){
            $(this).hide();
           $('#img_preview3').attr('src', '');
           $("input[name='img_3']").val("");
        });
        $('.clear4').click(function(){
            $(this).hide();
           $('#img_preview4').attr('src', '');
           $("input[name='img_4']").val("");
        });
        $('.clear5').click(function(){
            $(this).hide();
           $('#img_preview5').attr('src', '');
           $("input[name='img_5']").val("");
        });
       
           
    });  

</script>