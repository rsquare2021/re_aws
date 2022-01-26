@if ($page_name != 'coming_soon' && $page_name != 'contact_us' && $page_name != 'error404' && $page_name != 'error500' && $page_name != 'error503' && $page_name != 'faq' && $page_name != 'helpdesk' && $page_name != 'maintenence' && $page_name != 'privacy' && $page_name != 'auth_boxed' && $page_name != 'auth_default')

    <!--  BEGIN TOPBAR  -->
    <div class="topbar-nav header navbar" role="banner">
        <nav id="topbar">
            <ul class="navbar-nav theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="index.html">
                        <img src="{{asset('public/storage/img/logo2.svg')}}" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="index.html" class="nav-link"> NEXT SS システム </a>
                </li>
            </ul>

            <ul class="list-unstyled menu-categories" id="topAccordion">
                
                @if ($page_name != 'alt_menu' && $page_name != 'blank_page' && $page_name != 'boxed' && $page_name != 'breadcrumb' )

                <li class="menu  {{ ($category_name === 'dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.home') }}" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle autodroprown">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>管理画面</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                </li>

                <li class="menu single-menu {{ ($category_name === 'apps') ? 'active' : '' }}">
                    <a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            <span>キャンペーン管理</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="app" data-parent="#topAccordion">
                        <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                            <a href="{{ route('admin.project.index') }}"> キャンペーン一覧 </a>
                        </li>
                        @if (Auth::user() and Auth::user()->isSuperAdmin())
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.project.create') }}"> キャンペーン追加 </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.project.download_shipping_csv', date('Ymd')) }}"> CSVダウンロード </a>
                            </li>
                        @endif
                    </ul>
                </li>

                @if (Auth::user() and Auth::user()->isSuperAdmin() and Auth::user()->company->isAdministration())
                    <li class="menu single-menu  class="{{ ($category_name === 'elements') ? 'active' : '' }}"">
                        <a href="#uiKit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift"><polyline points="20 12 20 22 4 22 4 12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                                <span>景品管理</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="uiKit" data-parent="#topAccordion">
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.product.index') }}"> 景品一覧 </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.product.create') }}"> 景品追加 </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.preset.index') }}"> 景品プリセット一覧 </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.preset.create') }}"> 景品プリセット追加 </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.product_cat.index') }}"> 景品カテゴリー一覧 </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.product_cat.create') }}"> 景品カテゴリー追加 </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user() and Auth::user()->isSuperAdmin())
                    <li class="menu single-menu {{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }} {{ ($category_name === 'datatable') ? 'active' : '' }}">
                        <a href="#tables" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                <span>ユーザー管理</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="tables"  data-parent="#topAccordion">
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.index') }}"> ユーザーリスト </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.create') }}"> ユーザー追加 </a>
                            </li>
                            <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                <a href="{{ route('admin.shop_tree.edit', 1) }}"> 店舗設定 </a>
                            </li>
                            @if (Auth::user()->company->isAdministration())
                                <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                                    <a href="{{ route('admin.shop_tree.create') }}"> ツリー追加 </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <li class="menu single-menu {{ ($category_name === 'forms') ? 'active' : '' }}">
                    <a href="#forms" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                            <span>レシート管理</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="forms"  data-parent="#topAccordion">
                        <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                            <a href="/test/admin/re/"> レシート一覧 </a>
                        </li>
                    </ul>
                </li>

                <li class="menu single-menu {{ ($category_name === 'drag_n_drop') ? 'active' : '' }} {{ ($category_name === 'maps') ? 'active' : '' }} {{ ($category_name === 'charts') ? 'active' : '' }} {{ ($category_name === 'widgets') ? 'active' : '' }} {{ ($category_name === 'fonticons') ? 'active' : '' }}">
                    <a href="#more" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>エンドユーザー</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="more" data-parent="#topAccordion">
                        <li class="{{ ($category_name === 'bootstrap_basic_table') ? 'active' : '' }}">
                            <a href="/test/admin/enduser/"> エンドユーザー一覧 </a>
                        </li>
                    </ul>
                </li>

                @else

                <li class="menu single-menu">
                    <a href="#menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle autodroprown">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>Menu 1</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="menu1" data-parent="#topAccordion">
                        <li>
                            <a href="javascript:void(0);"> Submenu 1 </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"> Submenu 2 </a>
                        </li>
                    </ul>
                </li>


                <li class="menu single-menu">
                    <a href="#menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle autodroprown">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            <span>Menu 2</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="menu2" data-parent="#topAccordion">
                        <li>
                            <a href="javascript:void(0);"> Submenu 1 </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"> Submenu 2 </a>
                        </li>
                        <li class="sub-sub-submenu-list">
                            <a href="#sub-sub-category" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Submenu 3 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                            <ul class="collapse list-unstyled sub-submenu" id="sub-sub-category" data-parent="#menu"> 
                                <li>
                                    <a href="javascript:void(0);"> Sub-Submenu 1 </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"> Sub-Submenu 2 </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"> Sub-Submenu 3 </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="menu single-menu active">
                    <a href="#starter-kit" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle autodroprown">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                            <span>Starter Kit</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="starter-kit" data-parent="#topAccordion">
                        <li class="{{ ($category_name === 'blank_page') ? 'active' : '' }}">
                            <a href="/starter-kit/blank_page"> Blank Page </a>
                        </li>
                        <li class="{{ ($category_name === 'breadcrumbs') ? 'active' : '' }}">
                            <a href="/starter-kit/breadcrumbs"> Breadcrumb </a>
                        </li>
                    </ul>
                </li>
                
                @endif
            </ul>
            
        </nav>
        
        <h5 class="title mt-3">{{ $title }}</h5>
        
    </div>
    <!--  END TOPBAR  -->

@endif