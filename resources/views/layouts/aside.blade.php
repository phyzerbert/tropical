@php
    $page = config('site.page');
@endphp
<nav id="sidebar" aria-label="Main Navigation">
    <div class="bg-header-dark">
        <div class="content-header bg-white-10">
            <a class="link-fx font-w600 font-size-lg text-white" href="{{route('home')}}">
                <span class="smini-visible">
                    <span class="text-white-75">T</span>
                    <span class="text-white">G</span>
                </span>
                <span class="smini-hidden">
                    <span class="text-white-75">TROPICAL</span>
                    <span class="text-white"> GIDA</span>
                </span>
            </a>
            <div>
                {{-- <a class="js-class-toggle text-white-75" data-target="#sidebar-style-toggler" data-class="fa-toggle-off fa-toggle-on" data-toggle="layout" data-action="sidebar_style_toggle" href="javascript:void(0)">
                    <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
                </a> --}}
                <a class="d-lg-none text-white ml-2" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                    <i class="fa fa-times-circle"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="content-side content-side-full">
        <ul class="nav-main">
            <li class="nav-main-item @if($page == 'home') active @endif">
                <a class="nav-main-link" href="{{route('home')}}">
                    <i class="nav-main-link-icon si si-home"></i>
                    <span class="nav-main-link-name">{{__('page.dashboard')}}</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'product') active @endif" href="{{route('product.index')}}">
                    <i class="nav-main-link-icon si si-present"></i>
                    <span class="nav-main-link-name">{{__('page.product')}}</span>
                </a>
            </li>
            @php
                $invoice_items = ['invoice', 'add_invoice'];
            @endphp
            <li class="nav-main-item @if($page == in_array($page, $invoice_items)) open @endif">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-puzzle"></i>
                    <span class="nav-main-link-name">{{__('page.invoice')}}</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'invoice') active @endif" href="{{route('invoice.index')}}">
                            <span class="nav-main-link-name">{{__('page.invoices')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'add_invoice') active @endif" href="{{route('invoice.create')}}">
                            <span class="nav-main-link-name">{{__('page.add_invoice')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @php
                $proforma_items = ['proforma', 'add_proforma', 'shipment'];
            @endphp
            <li class="nav-main-item @if($page == in_array($page, $proforma_items)) open @endif">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="be_tables_datatables.html#">
                    <i class="nav-main-link-icon si si-star"></i>
                    <span class="nav-main-link-name">PRO-FORM NR INVOICE</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'proforma') active @endif" href="{{route('proforma.index')}}">
                            <span class="nav-main-link-name">{{__('page.proforma')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'add_proforma') active @endif" href="{{route('proforma.create')}}">
                            <span class="nav-main-link-name">{{__('page.add_proforma')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'shipment') active @endif" href="{{route('shipment.index')}}">
                            <span class="nav-main-link-name">{{__('page.shipment')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @php
                $container_items = ['container', 'add_container', 'container_bl', 'container_booking'];
            @endphp
            <li class="nav-main-item @if($page == in_array($page, $container_items)) open @endif">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-grid"></i>
                    <span class="nav-main-link-name">{{__('page.container_load')}}</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'container') active @endif" href="{{route('container.index')}}">
                            <span class="nav-main-link-name">{{__('page.container_load')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'add_container') active @endif" href="{{route('container.create')}}">
                            <span class="nav-main-link-name">{{__('page.add_container')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'container_bl') active @endif" href="{{route('container.bl')}}">
                            <span class="nav-main-link-name">BL</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'container_booking') active @endif" href="{{route('container.booking')}}">
                            <span class="nav-main-link-name">{{__('page.booking')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @php
                $sale_proforma_items = ['sale_proforma', 'add_sale_proforma', 'sale_shipment'];
            @endphp
            <li class="nav-main-item @if($page == in_array($page, $sale_proforma_items)) open @endif">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="be_tables_datatables.html#">
                    <i class="nav-main-link-icon si si-star"></i>
                    <span class="nav-main-link-name">{{__('page.customer_proforma')}}</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'sale_proforma') active @endif" href="{{route('sale_proforma.index')}}">
                            <span class="nav-main-link-name">{{__('page.proforma')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'add_sale_proforma') active @endif" href="{{route('sale_proforma.create')}}">
                            <span class="nav-main-link-name">{{__('page.add_proforma')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'sale_shipment') active @endif" href="{{route('sale_shipment.index')}}">
                            <span class="nav-main-link-name">{{__('page.shipment')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @php
                $sale_items = ['sale', 'add_sale'];
            @endphp
            <li class="nav-main-item @if($page == in_array($page, $sale_items)) open @endif">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon si si-puzzle"></i>
                    <span class="nav-main-link-name">{{__('page.product_sale')}}</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'sale') active @endif" href="{{route('sale.index')}}">
                            <span class="nav-main-link-name">{{__('page.sales_list')}}</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if($page == 'add_sale') active @endif" href="{{route('sale.create')}}">
                            <span class="nav-main-link-name">{{__('page.add_sale')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'transaction') active @endif" href="{{route('transaction.index')}}">
                    <i class="nav-main-link-icon si si-wallet"></i>
                    <span class="nav-main-link-name">{{__('page.transaction')}}</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'daily_transaction') active @endif" href="{{route('transaction.daily')}}">
                    <i class="nav-main-link-icon si si-wallet"></i>
                    <span class="nav-main-link-name">{{__('page.daily_transaction')}}</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'supplier') active @endif" href="{{route('supplier.index')}}">
                    <i class="nav-main-link-icon si si-handbag"></i>
                    <span class="nav-main-link-name">{{__('page.supplier')}}</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'customer') active @endif" href="{{route('customer.index')}}">
                    <i class="nav-main-link-icon si si-basket"></i>
                    <span class="nav-main-link-name">{{__('page.customer')}}</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'user') active @endif" href="{{route('users.index')}}">
                    <i class="nav-main-link-icon si si-people"></i>
                    <span class="nav-main-link-name">{{__('page.user')}}</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'category') active @endif" href="{{route('category.index')}}">
                    <i class="nav-main-link-icon si si-people"></i>
                    <span class="nav-main-link-name">{{__('page.category')}}</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link @if($page == 'search') active @endif" href="{{route('search')}}">
                    <i class="nav-main-link-icon si si-magnifier"></i>
                    <span class="nav-main-link-name">{{__('page.search')}}</span>
                </a>
            </li>
        </ul>
    </div>
</nav>