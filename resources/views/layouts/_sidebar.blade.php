<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">

        <ul class="navbar-nav theme-brand flex-row  text-center">
            <li class="nav-item theme-logo">
                <a href="{{route('home')}}">
                    <img src="{{asset('assets/img/logo.png')}}" style="width: 71px; height: 66px;" class="navbar-logo" alt="logo">
                </a>
            </li>
            <li class="nav-item theme-text">
                <a href="{{route('home')}}" class="nav-link"> MSL </a>
            </li>
            <li class="nav-item toggle-sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-arrow-left sidebarCollapse">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </li>
        </ul>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionMenu">
            <li class="menu">
                <a href="{{route('home')}}" data-toggle="collapse" data-link="true" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            @permission('User-List')
            <li class="menu">
                <a href="{{route('users.index')}}" data-toggle="collapse" data-link="true" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>Users</span>
                    </div>
                    <div>
                    </div>
                </a>
            </li>
                @endpermission
                
                @permission('Role-List')
                <li class="menu">
                <a href="{{route('roles.index')}}" data-toggle="collapse" data-link="true" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Roles</span>
                    </div>
                    <div>
                    </div>
                </a>
            </li>
             @endpermission
        <ul class="list-unstyled menu-categories" id="accordionExample" style="padding:0px;">
             <li class="menu">
                        <a href="#components" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>                                <span>Master Data</span>
                            </div>
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="components" data-parent="#accordionExample">
                        <li>
                            <a href="#pages" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> Ports <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="pages" data-parent="#pages">
                                @permission('Ports-List')
                                    <li>
                                        <a href="{{route('ports.index')}}"> Ports </a>
                                    </li>
                                @endpermission

                                @permission('PortTypes-List')
                                    <li>
                                        <a href="{{route('port-types.index')}}"> Port Types </a>
                                    </li>
                                @endpermission
                                </ul>
                        </li>

                                @permission('Terminals-List')
                                    <li>
                                        <a href="{{route('terminals.index')}}"> Terminals </a>
                                    </li>
                                @endpermission
                                
                                @permission('Agents-List')
                                    <li>
                                        <a href="{{route('agents.index')}}"> Agents</a>
                                    </li>
                                @endpermission
                        </ul>
                    </li>
    </nav>

</div>
