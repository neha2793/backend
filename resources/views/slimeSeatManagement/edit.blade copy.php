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
                                                <div class="col-lg-11 mx-auto">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-12 col-md-4">
                                                            <div class="upload mt-4 pr-md-4">
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
                                                                    <!-- <input type="file" name="featured_image"  id="input-file-max-fs" class="dropify featured_image" data-default-file="assets/img/200x200.jpg" data-max-file-size="2M"> -->
                                                                    <button type="button" class="dropify-clear">
                                                                        <i class="flaticon-close-fill"></i>
                                                                    </button>
                                                                    <div class="dropify-preview" style="display: block;">
                                                                    <span class="dropify-render">
                                                                        <img src="{{$TBL_slime_seats->featured_image ? env('API_BASE_URL').$TBL_slime_seats->featured_image:''}}" id="category-img-tag">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p>
                                                        </div>
                                                        </div>
                                                        <div class="col-xl-10 col-lg-12 col-md-8 mt-md-0 mt-4">
                                                            <div class="form">
                                                                <div class="row">
                                                                    <div class="col-sm-12">  
                                                                        <div class="form-group">
                                                                            <lable class="lable-design">Multiple images</lable>
                                                                            <input type="file" name="image_path[]" id="file-4" class="form-control FiveImage" multiple>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <div class="preview_images">
                                                                                @for($i = 0; $i < 5; $i++)
                                                                                    <div class="box fivePriImages">
                                                                                        <figure>
                                                                                            <div class="remove1 get_image_id">
                                                                                                X{{$i+1}}
                                                                                            </div>
                                                                                            <img id="profilepreview_{{$i}}" style="height:50px;" class="profile_images_preview" src="{{isset($TBL_Slimeseat_Images[$i]) ? env('SLIME_SEAT_IMAGE_PATH').$TBL_Slimeseat_Images[$i]->Image_path :''}}">
                                                                                        </figure>
                                                                                    </div>
                                                                                    <input type="hidden" name="image_{{$i}}" id="image_{{$i}}" value="{{isset($TBL_Slimeseat_Images[$i]) ? $TBL_Slimeseat_Images[$i]->Image_path :''}}">
                                                                                    <input type="hidden" name="image_id_{{$i}}" id="image_id_{{$i}}" value="{{isset($TBL_Slimeseat_Images[$i]) ? $TBL_Slimeseat_Images[$i]->id :''}}">
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                    </div> -->
                                                                   
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Name</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="name" placeholder="Name"  value="{{$TBL_slime_seats->name}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Description">Description</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="Description" placeholder="Description"  value="{{$TBL_slime_seats->Description}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Price">Price</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="Price" placeholder="Price"  value="{{$TBL_slime_seats->Price}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="UsdPrice">USD Price</label>
                                                                            <input type="text" readonly class="form-control mb-4"  id="UsdPrice" placeholder="USD Price"  value="{{$TBL_slime_seats->UsdPrice}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        
                                                                        <!-- <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                                                            <ol class="carousel-indicators">
                                                                                @foreach($TBL_Slimeseat_Images as $key3 => $value3)
                                                                                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$key3}}" class="{{$key3 == 0 ? 'active' : ''}}"></li>
                                                                                @endforeach
                                                                            </ol>
                                                                            <div class="carousel-inner">
                                                                                @foreach($TBL_Slimeseat_Images as $key2 => $value2)
                                                                                    <div class="carousel-item {{$key2 == 0 ? 'active' :''}}">
                                                                                        <img class="d-block w-100" src="{{$value2->Image_path ? env('SLIME_SEAT_IMAGE_PATH').$value2->Image_path:''}}" alt="First slide">
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                                <span class="sr-only">Previous</span>
                                                                            </a>
                                                                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                                <span class="sr-only">Next</span>
                                                                            </a>
                                                                        </div> -->
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
                            </div>
                        </div>
                        <div class="account-settings-footer">
                            <div class="as-footer-container">
                                <!-- <button id="multiple-reset" type="button" class="btn btn-warning">Reset All</button> -->
                                <button id="multiple-messages" type="submit" class="btn btn-primary">Save Changes</button>
                                <div class="blockui-growl-message" style="cursor: default;">
                                    <i class="flaticon-double-check"></i>&nbsp; Settings Saved Successfully
                                </div>
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
        // document.querySelector(".FiveImage")
        // .onchange = function(e) {
            

        //     if($('#image_0').val() == ''){
        //         $('#image_0').val(e.target.files[0])
        //         let file = e.target.files[0];
        //         let blobURL =URL.createObjectURL(e.target.files[0]);
        //         document.querySelector("#profilepreview_0").src = blobURL;
        //     }else if($('#image_1').val() == ''){
        //         $('#image_1').val(e.target.files[0])

        //         let file = e.target.files[0];
        //         let blobURL =URL.createObjectURL(e.target.files[0]);
        //         document.querySelector("#profilepreview_1").src = blobURL;
        //     }else if($('#image_2').val() == ''){
        //         $('#image_2').val(e.target.files[0])

        //         let file = e.target.files[0];
        //         let blobURL =URL.createObjectURL(e.target.files[0]);
        //         document.querySelector("#profilepreview_2").src = blobURL;
        //     }else if($('#image_3').val() == ''){
        //         $('#image_3').val(e.target.files[0])

        //         let file = e.target.files[0];
        //         let blobURL =URL.createObjectURL(e.target.files[0]);
        //         document.querySelector("#profilepreview_3").src = blobURL;
        //     }else if($('#image_4').val() == ''){
        //         $('#image_4').val(e.target.files[0])

        //         let file = e.target.files[0];
        //         let blobURL =URL.createObjectURL(e.target.files[0]);
        //         document.querySelector("#profilepreview_4").src = blobURL;
        //     }
        // }
           
    });  
   

    
</script>