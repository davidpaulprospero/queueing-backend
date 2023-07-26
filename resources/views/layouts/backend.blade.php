<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \Session::get('app.title') }} :: @yield('title')</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset(Session::get('app.favicon')) }}" type="image/x-icon" />
    <!-- template bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <link href="{{ asset('public/assets/css/template.min.css') }}" rel='stylesheet prefetch'>
    <!-- roboto -->
    <link href="{{ asset('public/assets/css/roboto.css') }}" rel='stylesheet'>
    <!-- material-design -->
    <link href="{{ asset('public/assets/css/material-design.css') }}" rel='stylesheet'>
    <!-- small-n-flat -->
    <link href="{{ asset('public/assets/css/small-n-flat.css') }}" rel='stylesheet'>
    <!-- font-awesome -->
    <link href="{{ asset('public/assets/css/font-awesome.min.css') }}" rel='stylesheet'>
    <!-- jquery-ui -->
    <link href="{{ asset('public/assets/css/jquery-ui.min.css') }}" rel='stylesheet'>
    <!-- datatable -->
    <link href="{{ asset('public/assets/css/dataTables.min.css') }}" rel='stylesheet'>
    <!-- select2 -->
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel='stylesheet'>
    <!-- custom style -->
    <link href="{{ asset('public/assets/css/style.css') }}" rel='stylesheet'>

    <!-- Page styles -->
    @stack('styles')
    <!-- Jquery  -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
</head>

<body class="cm-no-transition cm-1-navbar loader-process">
    @include('backend.common.info')

    <!-- <div class="loader" style="background: rgba(0,0,0,0.5);"> -->

    </div>
    @if(Auth::user()->hasAnyRole(['admin', 'officer']))
    <!-- Starts of Sidebar -->
    <div id="cm-menu" class="shadow">
        <nav class="cm-navbar cm-navbar-primary">
            <div class="cm-flex">
                <a href="javascript:void(0)" class="cm-logo">
                    <img src="{{ asset('public/assets/img/icons/logo.jpg') }}" width="210" height="50">
                </a>
            </div>
            <div class="btn btn-primary md-menu-white" data-toggle="cm-menu"></div>

        </nav>



        </nav>

        <div id="cm-menu-content" class="shadow">
            <div id="cm-menu-items-wrapper">
                <div id="cm-menu-scroller">
                    <ul class="cm-menu-items">
                        <!-- // ADMIN MENU -->
                        @if(Auth::user()->hasRole('admin'))
                        <li class="{{ ((Request::is('admin')) ? 'active' : '') }}">
                            <a href="{{ url('admin') }}" class="sf-dashboard nav-link" style="padding: 5px; color: black;">
                                <span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.dashboard') }}</span>
                            </a>
                            <style>
                                .nav-link:hover {
                                    background-color: #A3202B;
                                    color: #A3202B;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                    color: white !important;
                                }
                            </style>
                        </li>



                        <li class="cm-submenu {{ (Request::segment(3)=='department' ? 'open' : '') }}">
                            <a class="sf-carton nav-link" style="background-color: transparent; padding: 5px; color: black;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.department') }}&nbsp;<span class="caret"></span>
                            </a>
                            <script>
                                $(document).ready(function() {
                                    $('.cm-submenu > a').click(function() {
                                        $(this).toggleClass('active');
                                    });
                                });
                            </script>
                            <ul>
                                <li class="{{ Request::is('admin/department/create') ? 'active' : '' }}">
                                    <a href="{{ url('admin/department/create') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.add_department') }}</a>
                                </li>
                                <li class="{{ Request::is('admin/department') ? 'active' : '' }}">
                                    <a href="{{ url('admin/department') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.department_list') }}</a>
                                </li>
                                <li class="{{ Request::is('admin/department/transaction') ? 'active' : '' }}">
                                    <a href="{{ url('admin/department/transaction') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.transaction_type') }}</a>
                                </li>
                            </ul>

                            <style>
                                .nav-link:hover {
                                    background-color: red !important;
                                    color: white !important;
                                }
                            </style>

                        <li class="cm-submenu {{ (Request::segment(2)=='display' ? 'open' : '') }}">
                            <a target="_blank" class="sf-device-tablet nav-link" style="background-color: transparent; padding: 5px; color: black;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.display') }}&nbsp;
                                <span class="caret"></span>
                            </a>

                            <ul>
                                @if (session()->has('custom_displays'))
                                @foreach(session()->get('custom_displays') as $key => $name)
                                <li>
                                    <a href="http://localhost:5173/window" target="_blank" class="nav-link" style="background-color: white; color: black;">{{ trans('app.custom_display') }} - {{ $name }}</a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: red !important;
                                    color: white !important;
                                }
                            </style>




                        <li class="cm-submenu {{ (Request::segment(2)=='counter' ? 'open' : '') }}">
                            <a class="sf-star nav-link" style="background-color: transparent; padding: 5px; color: black;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.counter') }}&nbsp;<span class="caret"></span>
                            </a>
                            <ul>
                                <li class="{{ Request::is('admin/counter/create') ? 'active' : '' }}">
                                    <a href="{{ url('admin/counter/create') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.add_counter') }}</a>
                                </li>
                                <li class="{{ Request::is('admin/counter') ? 'active' : '' }}">
                                    <a href="{{ url('admin/counter') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.counter_list') }}</a>
                                </li>
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: red !important;
                                    color: white !important;
                                }
                            </style>

                        <li class="cm-submenu {{ (Request::segment(2)=='user' ? 'open' : '') }}">
                            <a class="sf-profile-group nav-link" style="background-color: transparent; padding: 5px; color: black;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.users') }}&nbsp;
                                <span class="caret"></span>
                            </a>
                            <ul>
                                <li class="{{ Request::is('admin/user/create') ? 'active' : '' }}">
                                    <a href="{{ url('admin/user/create') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.add_user') }}</a>
                                </li>
                                <li class="{{ Request::is('admin/user') ? 'active' : '' }}">
                                    <a href="{{ url('admin/user') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.user_list') }}</a>
                                </li>
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: red !important;
                                    color: white !important;
                                }
                            </style>

                            <!-- 
                            <li class="cm-submenu {{ (Request::segment(2)=='sms' ? 'open' : '') }}">
                                <a class="sf-bubbles" style="background-color:#A3202B; color:white;">{{ trans('app.sms') }} <span class="caret"></span></a>
                                <ul>
                                    <li class="{{ (Request::is('admin/sms/new') ? 'active' : '') }}">
                                        <a href="{{ url('admin/sms/new') }}" style="background-color:#dc3545;color: white;">{{ trans('app.new_sms') }}</a>
                                    </li>
                                    <li class="{{ (Request::is('admin/sms/list') ? 'active' : '') }}">
                                        <a href="{{ url('admin/sms/list') }}" style="background-color:#dc3545;color: white;">{{ trans('app.sms_history') }}</a>
                                    </li>
                                    <li class="bg-danger {{ (Request::is('admin/sms/setting') ? 'active' : '') }}">
                                        <a href="{{ url('admin/sms/setting') }}" style="background-color:#dc3545;color: white;">{{ trans('app.sms_setting') }}</a>
                                    </li>
                                </ul>
                            </li>  -->

                        <li class="cm-submenu {{ (Request::segment(2)=='token' ? 'open' : '') }}">
                            <a class="sf-user-id nav-link" style="background-color: transparent; padding: 5px; color: black;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.token') }}&nbsp;
                                <span class="caret"></span>
                            </a>
                            <ul>
                                <li class="{{ Request::is('admin/token/list') ? 'active' : '' }}">
                                    <a href="{{ url('admin/token/auto') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.auto_token') }}</a>
                                </li>
                                <li class="{{ Request::is('admin/token/create') ? 'active' : '' }}">
                                    <a href="{{ url('admin/token/create') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.manual_token') }}</a>
                                </li>
                                <li class="{{ Request::is('admin/token/current') ? 'active' : '' }}">
                                    <a href="{{ url('admin/token/current') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.active') }} / {{ trans('app.todays_token') }} <i class="fa fa-dot-circle-o" style="color:#03d003"></i></a>
                                </li>
                                <li class="{{ Request::is('admin/token/report') ? 'active' : '' }}">
                                    <a href="{{ url('admin/token/report') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.token_report') }}</a>
                                </li>
                                <!-- <li class="{{ Request::is('admin/token/performance') ? 'active' : '' }}">
                                <a href="{{ url('admin/token/performance') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.performance_report') }}</a>
                            </li> -->
                                <li class="bg-danger {{ Request::is('admin/token/setting') ? 'active' : '' }}">
                                    <a href="{{ url('admin/token/setting') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.auto_token_setting') }}</a>
                                </li>
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: red !important;
                                    color: white !important;
                                }
                            </style>


                        <li class="cm-submenu {{ (Request::segment(2)=='setting' ? 'open' : '') }}">
                            <a class="sf-cog nav-link" style="background-color: transparent; padding: 5px; color: black;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.setting') }}&nbsp;
                                <span class="caret"></span>
                            </a>
                            <ul>
                                @if (auth()->user()->hasRole('admin'))
                                <li class="{{ Request::is('admin/setting') ? 'active' : '' }}">
                                    <a href="{{ url('admin/setting') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.app_setting') }}</a>
                                </li>
                                <li class="{{ Request::is('admin/setting/display') ? 'active' : '' }}">
                                    <a href="{{ url('admin/setting/display') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.display_setting') }}</a>
                                </li>

                                <li class="{{ Request::is('admin/setting/display/video') ? 'active' : '' }}">
                                    <a href="{{ url('admin/setting/display/video') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('Display Video Settings') }}</a>
                                </li>

                                @endif
                                <li class="{{ Request::is('common/setting/*') ? 'active' : '' }}">
                                    <a href="{{ url('common/setting/profile') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.profile_information') }}</a>
                                </li>
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: white !important;
                                    color: black !important;
                                }
                            </style>

                        </li>
                        <li class="{{ Request::is('logout') ? 'active' : '' }}">
                            <a href="{{ url('logout') }}" class="sf-lock nav-link" style="background-color: transparent; color: black;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.signout') }}&nbsp;
                            </a>
                            <style>
                                .nav-link:hover {

                                    background-color: #A3202B !important;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                    color: white !important;
                                }
                            </style>
                        </li>
                        @endif

                        <!-------------------------------------------------------->
                        <!-- OFFICER MENU                                       -->
                        <!-------------------------------------------------------->
                        @if(Auth::user()->hasRole('officer'))
                        <li class="{{ Request::is('officer') ? 'active' : '' }}">
                            <a href="{{ url('officer') }}" class="sf-dashboard" style="background-color: transparent; color: black;">
                                <span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.dashboard') }}</span>
                            </a>
                            <style>
                                .sf-dashboard:hover {
                                    background-color: #A3202B !important;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                    color: white !important;
                                }
                            </style>
                        </li>

                        <li class="cm-submenu nav-link {{ Request::segment(2) == 'display' ? 'open' : '' }}">
                            <a href="#" class="sf-device-tablet" style="background-color: transparent; color: black;">
                                <span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.display') }}</span>
                                <span class="caret"></span>
                            </a>
                            <ul>
                                @if (session()->has('custom_displays'))
                                @foreach(session()->get('custom_displays') as $key => $name)
                                <li>
                                    <a href="{{ url('common/display?type=6&custom='.$key) }}" class="nav-link" target="_blank" style="background-color: transparent; color: black;">
                                        {{ trans('app.custom_display') }} - {{ $name }}
                                    </a>
                                </li>
                                @endforeach
                                @endif
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: #A3202B !important;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                    color: white !important;
                                }
                            </style>
                        </li>



                        <li class="cm-submenu {{ Request::segment(2) == 'token' ? 'open' : '' }}">
                            <a class="sf-user-id nav-link" style="background-color: transparent; color: black;">
                                <span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.token') }}</span>
                                <span class="caret"></span>
                            </a>
                            <ul>
                                <li class="{{ Request::is('officer/token/current') ? 'active' : '' }}">
                                    <a href="{{ url('officer/token/current') }}" class="nav-link" style="background-color: transparent; color: black;">
                                        {{ trans('app.active') }} / {{ trans('app.todays_token') }}
                                        <i class="fa fa-dot-circle-o" style="color: #03d003"></i>
                                    </a>
                                </li>
                                <li class="{{ Request::is('officer/token') ? 'active' : '' }}">
                                    <a href="{{ url('officer/token') }}" class="nav-link" style="background-color: transparent; color: black;">
                                        {{ trans('app.token_list') }}
                                    </a>
                                </li>
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: #A3202B !important;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                    color: white !important;
                                }
                            </style>
                        </li>

                        <li class="cm-submenu {{ Request::segment(2) == 'setting' ? 'open' : '' }}">
                            <a class="sf-cog nav-link" style="background-color: transparent; color: black;">
                                <span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.setting') }}</span>
                                <span class="caret"></span>
                            </a>
                            <ul>
                                @if (auth()->user()->hasRole('admin'))
                                <li class="{{ (Request::is('admin/setting') ? 'active' : '') }}">
                                    <a href="{{ url('admin/setting') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.app_setting') }}</a>
                                </li>
                                <li class="{{ (Request::is('admin/setting/display') ? 'active' : '') }}">
                                    <a href="{{ url('admin/setting/display') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.display_setting') }}</a>
                                </li>
                                @endif

                                <li class="{{ (Request::is('common/setting/*') ? 'active' : '') }}">
                                    <a href="{{ url('common/setting/profile') }}" class="nav-link" style="background-color: white; color: black;">{{ trans('app.profile_information') }}</a>
                                </li>
                            </ul>
                            <style>
                                .nav-link:hover {
                                    background-color: #A3202B !important;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                    color: white !important;
                                }
                            </style>
                        </li>
                        </li>
                        <li class="{{ Request::is('logout') ? 'active' : '' }}">
                            <a href="{{ url('logout') }}" class="sf-lock" style="background-color: transparent; color: black;">
                                <span style="font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('app.signout') }}</span>
                            </a>
                            <style>
                                .sf-lock:hover {
                                    background-color: #A3202B !important;
                                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                                    color: white !important;
                                }
                            </style>
                        </li>
                        @endif

                        <!-------------------------------------------------------->
                        <!-- RECEPTIONIST MENU                               -->
                        <!-------------------------------------------------------->
                        @if(Auth::user()->hasRole('receptionist'))





                        @endif


                        <!-------------------------------------------------------->
                        <!-- COMMON MENU                                        -->
                        <!-------------------------------------------------------->

                        <!-- <li class="cm-submenu {{ (Request::segment(2)=='display' ? 'open' : '') }}">
                                <a target="_blank" class="sf-device-tablet" style="background-color:#A3202B; color:white;">
                                    {{ trans('app.display') }} 
                                    <span class="caret"></span>
                                </a>
                                <ul>
                                    <li class="{{ (session()->get('app.display')==1 ? 'active' : '') }}">
                                        <a href="{{ url('common/display?type=1') }}" target="_blank" style="background-color:#dc3545;color: white;">{{ trans('app.display_1') }}</a>
                                    </li> 
                                    <li class="{{ (session()->get('app.display')==2 ? 'active' : '') }}">
                                        <a href="{{ url('common/display?type=2') }}" target="_blank" style="background-color:#dc3545;color: white;">{{ trans('app.display_2') }}</a>
                                    </li> 
                                    <li class="{{ (session()->get('app.display')==3 ? 'active' : '') }}">
                                        <a href="{{ url('common/display?type=3') }}" target="_blank" style="background-color:#dc3545;color: white;">{{ trans('app.display_3') }}</a>
                                    </li> 
                                    <li class="{{ (session()->get('app.display')==4 ? 'active' : '') }}">
                                        <a href="{{ url('common/display?type=4') }}" target="_blank" style="background-color:#dc3545;color: white;">{{ trans('app.display_4') }}</a>
                                    </li> 
                                    <li class="{{ (session()->get('app.display')==5 ? 'active' : '') }}">
                                        <a href="{{ url('common/display?type=5') }}" target="_blank" style="background-color:#dc3545;color: white;">{{ trans('app.display_5') }}</a>
                                    </li>   

                                    @if (session()->has('custom_displays'))
                                    @foreach(session()->get('custom_displays') as $key => $name)
                                    <li>
                                        <a href="{{ url('common/display?type=6&custom='.$key) }}" target="_blank" style="background-color:#dc3545;color: white;">{{ trans('app.custom_display') }} - {{ $name }}</a>
                                    </li>
                                    @endforeach
                                    @endif 
                                </ul>
                            </li>  -->

                        <!-- <li class="cm-submenu {{ (Request::segment(2)=='message' ? 'open' : '') }}">
                                <a class="sf-envelope-letter" style="background-color:#A3202B; color:white;">{{ trans('app.message') }} <span class="caret"></span></a>
                                <ul>
                                    <li class="{{ (Request::is('common/message') ? 'active' : '') }}">
                                        <a href="{{ url('common/message') }}" style="background-color:#dc3545;color: white;">{{ trans('app.new_message') }}</a>
                                    </li>
                                    <li class="{{ (Request::is('common/message/inbox') ? 'active' : '') }}">
                                        <a href="{{ url('common/message/inbox') }}" style="background-color:#dc3545;color: white;">{{ trans('app.inbox') }}</a>
                                    </li>
                                    <li class="{{ (Request::is('common/message/sent') ? 'active' : '') }}">
                                        <a href="{{ url('common/message/sent') }}" style="background-color:#dc3545;color: white;">{{ trans('app.sent') }}</a>
                                    </li>
                                </ul>
                            </li>  -->


                        <!-- <li class="{{ Request::is('logout') ? 'active' : '' }}">
  <a href="{{ url('logout') }}" class="sf-lock" style="background-color:#A3202B; color:white;">
    {{ trans('app.signout') }}
  </a>
</li> -->
                        <div style="background-color: red;">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Ends of Sidebar -->


    <!-- Starts of Header/Menu -->
    <header id="cm-header">
        <nav class="cm-navbar cm-navbar-warning" style="background-color:#A3202B;">

            <div class="cm-flex">
                <h1 class="clearfix">{{ \Session::get('app.title') }}</h1>
            </div>

            <!-- <div class="dropdown pull-right">
                    <a href="{{ url('common/message/inbox') }}" class="btn btn-primary md-local-post-office-white"> <span class="label label-danger" id="message-notify">0</span> </a> 
                </div> -->
            <!-- <div class="dropdown pull-right">
                    <button class="btn btn-primary md-language-white" data-toggle="dropdown"> <span class="label label-danger">{{ Session::get('locale')? Session::get('locale'):'en' }}</span></button>
                    <div class="popover cm-popover bottom">
                        <div class="arrow"></div>
                        <div class="popover-content">
                            <div class="list-group"> 
                                <a href="javascript:void(0)" data-locale="en" class="select-lang list-group-item {{ ((Session::get('locale')=='en' || !Session::has('locale'))?'active':'') }}">
                                    <h4 class="list-group-item-heading"></i> English</h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>  -->
            @if($user = Auth::user())
            <div class="dropdown pull-right">
                <button class="btn btn-primary md-account-circle-white" data-toggle="dropdown"></button>
                <ul class="dropdown-menu">
                    @if(Auth::user()->hasAnyRole(['admin', 'officer']))
                    <li class="disabled text-center">
                        <img src="{{ !empty($user->photo)?asset($user->photo):asset('public/assets/img/icons/no_user.jpg') }}" width="140" height="105">
                    </li>
                    <li class="disabled text-center">
                        <a style="cursor:default;"><strong>{{ $user->firstname .' '. $user->lastname }}</strong>
                        </a>
                        <span class="label label-success">{{ auth()->user()->role() }}</span>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ url('common/setting/profile') }}"><i class="fa fa-user"></i> {{ trans('app.profile_information') }}</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> {{ trans('app.signout') }}</a>
                    </li>
                </ul>
            </div>
            @endif
        </nav>
    </header>
    <!-- Ends of Header/Menu -->


    <div id="global">


        <div class="container-fluid p-3 w-auto rounded-lg overflow-hidden">
            <!-- Starts of Message -->
            <div class="info-message bg-danger text-white p-3 rounded-lg shadow">
                @yield('info.message')
            </div>
            <!-- Ends of Message -->

            <!-- Starts of Content -->
            <div class="content bg-white rounded-lg shadow mt-3 p-3">
                @yield('content')
            </div>
            <!-- Ends of Contents -->
        </div>




        <!-- Starts of Copyright -->

        <footer class="cm-footer text-right">
            <span class="hidden-xs">{{ \Session::get('app.copyright_text') }}</span>
            <span class="pull-left text-center">@yield('info.powered-by') @yield('info.version')</span>
        </footer>
        <!-- Ends of Copyright -->
    </div>


    <!-- All js -->
    <!-- bootstrp -->
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('public/assets/js/select2.min.js') }}"></script>
    <!-- juery-ui -->
    <script src="{{ asset('public/assets/js/jquery-ui.min.js') }}"></script>
    <!-- jquery.mousewheel.min -->
    <script src="{{ asset('public/assets/js/jquery.mousewheel.min.js') }}"></script>
    <!-- jquery.cookie.min -->
    <script src="{{ asset('public/assets/js/jquery.cookie.min.js') }}"></script>
    <!-- fastclick -->
    <script src="{{ asset('public/assets/js/fastclick.min.js') }}"></script>
    <!-- template -->
    <script src="{{ asset('public/assets/js/template.js') }}"></script>
    <script src="{{ asset('node_modules/push.js/bin/push.min.js') }}"></script>
    <!-- datatable -->
    <script src="{{ asset('public/assets/js/dataTables.min.js') }}"></script>
    <!-- custom script -->
    <script src="{{ asset('public/assets/js/script.js') }}"></script>

    <!-- Page Script -->
    @stack('scripts')




    <script type="text/javascript">
        (function() {
            //notification
            notify();
            setInterval(function() {
                notify();
            }, 30000);

            function notify() {
                $.ajax({
                    type: 'GET',
                    url: '{{ URL::to("common/message/notify") }}',
                    data: '_token = <?php echo csrf_token() ?>',
                    success: function(data) {
                        $("#message-notify").html(data);
                    }
                });
            }

            //language switch
            $(".select-lang").on('click', function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ url("common/language") }}',
                    data: {
                        'locale': $(this).data("locale"),
                        '_token': '<?php echo csrf_token() ?>'
                    },
                    success: function(data) {
                        history.go(0);
                    },
                    error: function() {
                        alert('failed');
                    }
                });
            });

        })();
    </script>
</body>

</html>