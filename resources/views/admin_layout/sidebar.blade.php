<!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <nav id="smth">
            <div id="compact_submenuSidebar" class="submenu-sidebar ps show">
                @php 
                    $url =  Route::current()->getName(); 
                @endphp
                <div class="submenu show" id="dashboard">
                    <ul class="submenu-list" data-parent-element="#dashboard"> 
                        <li class="{{$url == 'index'? 'active':'' }}">
                            <a href="{{route('index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>    
                                Dashboard
                            </a>
                        </li>
                        <!-- <li>
                            <a href="index2.html"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>Sales</a>
                        </li> -->
                        <li class="{{$url == 'user-management.index'? 'active':'' }}">
                            <a href="{{route('user-management.index')}}" >
                            <svg xmlns="http://www.w3.org/2000/svg" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path fill="none" d="M14.023,12.154c1.514-1.192,2.488-3.038,2.488-5.114c0-3.597-2.914-6.512-6.512-6.512c-3.597,0-6.512,2.916-6.512,6.512c0,2.076,0.975,3.922,2.489,5.114c-2.714,1.385-4.625,4.117-4.836,7.318h1.186c0.229-2.998,2.177-5.512,4.86-6.566c0.853,0.41,1.804,0.646,2.813,0.646c1.01,0,1.961-0.236,2.812-0.646c2.684,1.055,4.633,3.568,4.859,6.566h1.188C18.648,16.271,16.736,13.539,14.023,12.154z M10,12.367c-2.943,0-5.328-2.385-5.328-5.327c0-2.943,2.385-5.328,5.328-5.328c2.943,0,5.328,2.385,5.328,5.328C15.328,9.982,12.943,12.367,10,12.367z"></path></svg>
                                User Management</a>
                        </li>
                        <li class="{{$url == 'user-varification-request.index'? 'active':'' }}">
                            <a href="{{route('user-varification-request.index')}}" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            User Varification</a>
                        </li>
                        <li class="{{$url == 'nft-management.index'? 'active':'' }}">
                            <a href="{{route('nft-management.index')}}" >
                                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#ffffff;stroke-linecap:round;stroke-linejoin:round;stroke-width:2px;}</style></defs><g id="server"><rect class="cls-1" height="41" rx="2.8338" width="43" x="9" y="15"/><line class="cls-1" x1="51.5858" x2="54.4142" y1="5.5858" y2="8.4142"/><line class="cls-1" x1="54.4142" x2="51.5858" y1="5.5858" y2="8.4142"/><circle cx="57.5532" cy="52.2188" r="1.0691"/><rect class="cls-1" height="29" width="31" x="15" y="21"/><rect class="cls-1" height="21" rx="2.0066" width="23" x="19" y="25"/><polyline class="cls-1" points="32 33 29 33 29 39"/><line class="cls-1" x1="32" x2="29" y1="36" y2="36"/><polyline class="cls-1" points="26 37 22 33 22 39"/><line class="cls-1" x1="26" x2="26" y1="33" y2="39"/><line class="cls-1" x1="35" x2="39" y1="33" y2="33"/><line class="cls-1" x1="37" x2="37" y1="39" y2="33"/><line class="cls-1" x1="20" x2="20" y1="13" y2="15"/><line class="cls-1" x1="23" x2="23" y1="13" y2="15"/><line class="cls-1" x1="26" x2="26" y1="13" y2="15"/><line class="cls-1" x1="29" x2="29" y1="13" y2="15"/><line class="cls-1" x1="32" x2="32" y1="13" y2="15"/><line class="cls-1" x1="35" x2="35" y1="13" y2="15"/><line class="cls-1" x1="38" x2="38" y1="13" y2="15"/><line class="cls-1" x1="41" x2="41" y1="13" y2="15"/><line class="cls-1" x1="20" x2="20" y1="56" y2="58"/><line class="cls-1" x1="23" x2="23" y1="56" y2="58"/><line class="cls-1" x1="26" x2="26" y1="56" y2="58"/><line class="cls-1" x1="29" x2="29" y1="56" y2="58"/><line class="cls-1" x1="32" x2="32" y1="56" y2="58"/><line class="cls-1" x1="35" x2="35" y1="56" y2="58"/><line class="cls-1" x1="38" x2="38" y1="56" y2="58"/><line class="cls-1" x1="41" x2="41" y1="56" y2="58"/><line class="cls-1" x1="54" x2="52" y1="25" y2="25"/><line class="cls-1" x1="54" x2="52" y1="28" y2="28"/><line class="cls-1" x1="54" x2="52" y1="31" y2="31"/><line class="cls-1" x1="54" x2="52" y1="34" y2="34"/><line class="cls-1" x1="54" x2="52" y1="37" y2="37"/><line class="cls-1" x1="54" x2="52" y1="40" y2="40"/><line class="cls-1" x1="54" x2="52" y1="43" y2="43"/><line class="cls-1" x1="54" x2="52" y1="46" y2="46"/><line class="cls-1" x1="9" x2="7" y1="25" y2="25"/><line class="cls-1" x1="9" x2="7" y1="28" y2="28"/><line class="cls-1" x1="9" x2="7" y1="31" y2="31"/><line class="cls-1" x1="9" x2="7" y1="34" y2="34"/><line class="cls-1" x1="9" x2="7" y1="37" y2="37"/><line class="cls-1" x1="9" x2="7" y1="40" y2="40"/><line class="cls-1" x1="9" x2="7" y1="43" y2="43"/><line class="cls-1" x1="9" x2="7" y1="46" y2="46"/><circle cx="49" cy="18" r="1"/><circle cx="12" cy="18" r="1"/><circle cx="49" cy="53" r="1"/><circle cx="12" cy="53" r="1"/></g></svg>
                                NFT
                            </a>
                        </li>
                        <!-- <li class="{{$url == 'order-management.index'? 'active':'' }}">
                            <a href="{{route('order-management.index')}}" >
                            <svg id="Layer_1" style="enable-background:new 0 0 30 30;" version="1.1" viewBox="0 0 30 30" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M7,22  V4h18v18c0,2.209-1.791,4-4,4" style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"/><path d="M17,22  L17,22H4l0,0c0,2.209,1.791,4,4,4h13C18.791,26,17,24.209,17,22z" style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"/><line style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linejoin:round;stroke-miterlimit:10;" x1="15" x2="21" y1="13" y2="13"/><line style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linejoin:round;stroke-miterlimit:10;" x1="11" x2="13" y1="13" y2="13"/><line style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linejoin:round;stroke-miterlimit:10;" x1="15" x2="21" y1="17" y2="17"/><line style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linejoin:round;stroke-miterlimit:10;" x1="11" x2="13" y1="17" y2="17"/><line style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linejoin:round;stroke-miterlimit:10;" x1="15" x2="21" y1="9" y2="9"/><line style="fill:none;stroke:#ffffff;stroke-width:0.8;stroke-linejoin:round;stroke-miterlimit:10;" x1="11" x2="13" y1="9" y2="9"/><path d="M17,22L17,22H4l0,0c0,2.209,1.791,4,4,4h13C18.791,26,17,24.209,17,22z"/></svg>
                                Order
                            </a>
                        </li>
                        <li class="{{$url == 'transactions-list'? 'active':'' }}">
                            <a href="{{route('transactions-list')}}" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>    
                                Transactions
                            </a>
                        </li> -->
                        <li class="{{$url == 'shipping-container-management.index'? 'active':'' }}">
                            <a href="{{route('shipping-container-management.index')}}" >
                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path style="stroke:#ffffff;stroke-width:0.8;stroke-linecap:round;" d="M 0 6 L 0 8 L 19 8 L 19 23 L 12.84375 23 C 12.398438 21.28125 10.851563 20 9 20 C 7.148438 20 5.601563 21.28125 5.15625 23 L 4 23 L 4 18 L 2 18 L 2 25 L 5.15625 25 C 5.601563 26.71875 7.148438 28 9 28 C 10.851563 28 12.398438 26.71875 12.84375 25 L 21.15625 25 C 21.601563 26.71875 23.148438 28 25 28 C 26.851563 28 28.398438 26.71875 28.84375 25 L 32 25 L 32 16.84375 L 31.9375 16.6875 L 29.9375 10.6875 L 29.71875 10 L 21 10 L 21 6 Z M 1 10 L 1 12 L 10 12 L 10 10 Z M 21 12 L 28.28125 12 L 30 17.125 L 30 23 L 28.84375 23 C 28.398438 21.28125 26.851563 20 25 20 C 23.148438 20 21.601563 21.28125 21.15625 23 L 21 23 Z M 2 14 L 2 16 L 8 16 L 8 14 Z M 9 22 C 10.117188 22 11 22.882813 11 24 C 11 25.117188 10.117188 26 9 26 C 7.882813 26 7 25.117188 7 24 C 7 22.882813 7.882813 22 9 22 Z M 25 22 C 26.117188 22 27 22.882813 27 24 C 27 25.117188 26.117188 26 25 26 C 23.882813 26 23 25.117188 23 24 C 23 22.882813 23.882813 22 25 22 Z"/></svg>   
                            Shipping Container</a>
                        </li>
                        <li class="{{$url == 'SC-video-management.index'? 'active':'' }}">
                            <a href="{{route('SC-video-management.index')}}" >
                                <svg id="Layer_1" style="enable-background:new 0 0 128 128;" version="1.1" viewBox="0 0 128 128" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path style="stroke:#ffffff; fill:#999c9f;stroke-width:0.8;stroke-linecap:round;" d="M127,1H1v126h126V1z M119,119H9V9h110V119z"/><path style="fill:#999c9f; stroke-width:0.8;stroke-linecap:round; stroke:#ffffff;" d="M98.7,64L43.3,32.1l0,63.9L98.7,64z M82.7,64L51.3,82.1l0-36.1L82.7,64z"/></g></svg>
                                SC Video
                                </a>
                        </li>
                        <li class="{{$url == 'slime-seat-management.index'? 'active':'' }}">
                            <a href="{{route('slime-seat-management.index')}}" >
                                <svg class="feather feather-layers" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                                Slime Seat
                            </a>
                        </li>
                        <li class="{{$url == 'slime-tour.index'? 'active':'' }}">
                            <a href="{{route('slime-tour.index')}}" >
                                <svg class="feather feather-underline" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" x2="20" y1="21" y2="21"/></svg>
                                Slime Tour
                                </a>
                        </li>
                        <li class="{{$url == 'page-management.index'? 'active':'' }}">
                            <a href="{{route('page-management.index')}}" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                Pages
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
<!--  END SIDEBAR  -->