<header id="page-header">
    <div class="content-header">
        <div>
            <button type="button" class="btn btn-dual mr-1" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>
        <div>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-user d-sm-none"></i>
                    <span class="d-none d-sm-inline-block">
                        @if (Auth::user()->first_name && Auth::user()->last_name)
                            {{Auth::user()->first_name}} {{Auth::user()->last_name}}
                        @else
                            {{Auth::user()->name}}
                        @endif                        
                    </span>
                    <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
                    <div class="bg-primary-darker rounded-top font-w600 text-white text-center p-3">
                        User Options
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="far fa-fw fa-user mr-1"></i> {{__('page.profile')}}
                        </a>
                        <div role="separator" class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"
                        >
                            <i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> {{__('page.sign_out')}}
                        </a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>