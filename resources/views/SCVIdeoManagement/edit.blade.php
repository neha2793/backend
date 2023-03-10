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
                        <h3>SC Video</h3>
                    </div>
                </div>
                <div class="account-settings-container layout-top-spacing">
                    <form id="general-info" class="section general-info SCFOrm" method="POST" action="{{route('SC-video-management.update',$SCVideos->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="account-content">
                            <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                                        <div class="info">
                                            <h6 class="">SC Video Edit</h6>
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
                                                                    <input type="file" name="video"  id="input-file-max-fs" class="dropify video" data-default-file="public/assets/img/200x200.jpg" data-max-file-size="2M">
                                                                    <button type="button" class="dropify-clear">
                                                                        <i class="flaticon-close-fill"></i>
                                                                    </button>
                                                                    <div class="dropify-preview" style="display: block;">
                                                                    <span class="dropify-render">
                                                                        @if($SCVideos->type == 'Video')
                                                                            <video width="340" height="340" id="vd">
                                                                                <source src="{{$SCVideos->video ? env('API_BASE_URL').$SCVideos->video:''}}" type="video/mp4" />
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                        @else
                                                                            <img src="{{$SCVideos->video ? env('API_BASE_URL').$SCVideos->video:''}}" id="category-img-tag">
                                                                        @endif
                                                                        <video src="" id="video_preview" style="display:none"></video>
                                                                        <img src="" id="image_preview" style="display:none">
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
                                                                            <label for="name">Name</label>
                                                                            <input type="text" class="form-control mb-4"  id="name" placeholder="Name" name="name" value="{{$SCVideos->name}}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="Description">Description</label>
                                                                            <textarea name="description" class="form-control mb-4" id="" >{{$SCVideos->description}}</textarea>
                                                                        </div>
                                                                        @if($SCVideos->type == "Image")
                                                                            <div class="form-group" id="hideRedirection">
                                                                                <label for="Redirection_Link">Redirection Link</label>
                                                                                <input type="text" class="form-control mb-4"  id="Redirection_Link" placeholder="Redirection Link" name="redirection_link" value="{{$SCVideos->redirection_link}}">
                                                                            </div>
                                                                        @endif
                                                                        <div class="form-group" id="redirect_lnk" style="display:none">
                                                                            <label for="Redirection_Link">Redirection Link</label>
                                                                            <input type="text" class="form-control mb-4"  id="Redirection_Link" placeholder="Redirection Link" name="redirection_link" value="{{$SCVideos->redirection_link}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        
                                                                        <!-- <div class="form-group">
                                                                            <label for="phone_number">Phone Number</label>
                                                                            <input type="text" class="form-control mb-4"  id="phone_number" name="phone_number" placeholder="Phone Number" value="{{$SCVideos->phone_number}}">
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
                                <!-- <div class="blockui-growl-message" style="cursor: default;">
                                    <i class="flaticon-double-check"></i>&nbsp; Settings Saved Successfully
                                </div> -->
                            </div>
                        </div>
                        <input type="hidden" name="type" id="SCType" value="{{$SCVideos->type}}">
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
        document.querySelector(".video")
        .onchange = function(e) {
            if(e.target.files[0]){
                if(e.target.files[0].type.split('/')[0] == 'video'){
                    $('#video_preview').show();
                    $('#image_preview').attr('src', '');
                    $('#redirect_lnk').hide();
                    $('#category-img-tag').hide();
                    $('#vd').hide();
                    $('#hideRedirection').hide();
                    $('#hideRedirection').val('');
                    $('#redirect_lnk').val('');
                    $('#SCType').val('Video')

                    let file = e.target.files[0];
                    let blobURL =URL.createObjectURL(e.target.files[0]);
                    document.querySelector("#video_preview").src = blobURL;
                }else{
                    $('#redirect_lnk').show();
                    $('#image_preview').show();
                    $('#video_preview').attr('src', '');
                    $('#category-img-tag').hide();
                    $('#hideRedirection').hide();
                    $('#hideRedirection').val('');
                    $('#vd').hide();
                    $('#SCType').val('Image')

                    let file = e.target.files[0];
                    let blobURL =URL.createObjectURL(e.target.files[0]);
                        document.querySelector("#image_preview").src = blobURL;
                }
                
            }  
        

            // let file = event.target.files[0];
            // let blobURL =URL.createObjectURL(event.target.files[0]);
            //     document.querySelector("#category-img-tag").src = blobURL;
        }
           
    });   
</script>