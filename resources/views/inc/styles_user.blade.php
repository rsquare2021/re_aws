<link href="{{asset('assets/css/global.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/user.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/kinoshita.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/users/swiper.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/css/users/all.min.css')}}">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

@if ($page_name != 'coming_soon' && $page_name != 'contact_us' && $page_name != 'error404' && $page_name != 'error500' && $page_name != 'error503' && $page_name != 'faq' && $page_name != 'helpdesk' && $page_name != 'maintenence' && $page_name != 'privacy' && $page_name != 'auth_boxed' && $page_name != 'auth_default')
<link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
@endif
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->