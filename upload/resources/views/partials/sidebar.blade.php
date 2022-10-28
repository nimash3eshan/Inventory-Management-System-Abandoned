@if (Auth::check())
<aside class="main-sidebar">
	<section class="sidebar">
	
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      
        <li class=""><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> <span>{{trans('menu.dashboard')}}</span></a></li>

      @if(auth()->user()->checkSpPermission('customers.index'))
        <li class="{{(Request::is('customers')) ? 'active' : ''}} "><a href="{{ url('/customers') }}"><i class="fa fa-users"></i> <span>{{trans('menu.customers')}}</span></a></li>
      @endif

      @if(auth()->user()->checkSpPermission('items.index'))
    <li class="{{(Request::is('items')) ? 'active' : ''}} "><a href="{{ url('/items') }}"><i class="fa fa-bars"></i> <span>Stocks</span></a></li>
      @endif
<!-- <li><a href="{{ url('/item-kits') }}">{{trans('menu.item_kits')}}</a></li> -->
      @if(auth()->user()->checkSpPermission('suppliers.index'))
        <li class="{{(Request::is('suppliers')) ? 'active' : ''}} "><a href="{{ url('/suppliers') }}"><i class="fa fa-cubes"></i> <span>{{trans('menu.suppliers')}}</span></a></li>
      @endif

      @if(auth()->user()->checkSpPermission('receivings.index') || auth()->user()->checkSpPermission('receivings.create'))
      <li class="{{(Request::is('receivings') || Request::is('receivings/create')) ? 'active' : ''}} treeview">
          <a href="#"><i class="fa fa-sitemap"></i> <span>{{__('Purchases')}}</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
          <ul class="treeview-menu">
              @if(auth()->user()->checkSpPermission('receivings.index'))
              <li class="{{(Request::is('receivings')) ? 'active' : ''}} ">
                  <a href="{{ url('/receivings') }}"><i class="fa fa-circle-o"></i> <span>{{__('Purchase List')}}</span></a>
              </li>
              @endif
              @if(auth()->user()->checkSpPermission('receivings.create'))
                <li class="{{(Request::is('receivings/create')) ? 'active' : ''}} "><a href="{{ url('/receivings/create') }}"><i class="fa fa-circle-o"></i> <span>{{__('Create Purchase')}}</span></a></li>
              @endif
          </ul>
      </li>
      @endif

      @if(auth()->user()->checkSpPermission('sales.index') || auth()->user()->checkSpPermission('sales.create'))
      <li class="{{(Request::is('sales') || Request::is('sales/create')) ? 'active' : ''}} treeview">
          <a href="#"><i class="fa fa-shopping-cart"></i> <span>{{__('Sales')}}</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
          <ul class="treeview-menu">
              @if(auth()->user()->checkSpPermission('sales.index'))
              <li class="{{(Request::is('sales')) ? 'active' : ''}} ">
                  <a href="{{ url('/sales') }}"><i class="fa fa-circle-o"></i> <span>{{__('Sales List')}}</span></a>
              </li>
              @endif
              @if(auth()->user()->checkSpPermission('sales.create'))
              <li class="{{(Request::is('sales/create')) ? 'active' : ''}}">
                  <a href="{{ url('sales/create') }}"><i class="fa fa-circle-o"></i> {{__('Add Invoice')}}</a>
              </li>
              @endif
          </ul>
      </li>
      @endif


      @if(auth()->user()->checkSpPermission('accounts.index') || auth()->user()->checkSpPermission('transactions.index'))
      <li class="{{(Request::is('accounts') || Request::is('transactions')) ? 'active' : ''}} treeview">
          <a href="#"><i class="fa fa-university"></i> <span>{{trans('menu.accounts')}}</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
          <ul class="treeview-menu">
              @if(auth()->user()->checkSpPermission('accounts.index'))
              <li class="{{(Request::is('accounts')) ? 'active' : ''}} ">
                  <a href="{{ url('/accounts') }}"><i class="fa fa-circle-o"></i> <span>{{trans('menu.accounts')}}</span></a>
              </li>
              @endif
              @if(auth()->user()->checkSpPermission('transactions.index'))
              <li class="{{(Request::is('transactions')) ? 'active' : ''}}">
                  <a href="{{ url('transactions') }}"><i class="fa fa-circle-o"></i> Transactions</a>
              </li>
              @endif
          </ul>
      </li>
      @endif

    @if(auth()->user()->checkSpPermission('expense.index') || auth()->user()->checkSpPermission('expensecategory.index'))
    <li class="{{(Request::is('expense') || Request::is('expensecategory')) ? 'active' : ''}} treeview">
      <a href="#">
        <i class="fa fa-dollar"></i> <span>{{trans('menu.expense')}}</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        @if(auth()->user()->checkSpPermission('expense.index'))
        <li class="{{(Request::is('expense')) ? 'active' : ''}}"><a href="{{ url('/expense') }}"><i class="fa fa-circle-o"></i> <span>{{trans('menu.expense')}}</span></a></li>
        @endif
        @if(auth()->user()->checkSpPermission('expensecategory.index'))
        <li class="{{(Request::is('expensecategory')) ? 'active' : ''}}"><a href="{{ url('expensecategory') }}"><i class="fa fa-circle-o"></i> Expense Category</a></li>
        @endif
      </ul>
    </li>
    @endif

    @if(auth()->user()->checkSpPermission('dailyreport.index') || auth()->user()->checkSpPermission('dailyreport.create') || auth()->user()->checkSpPermission('report.sale') || auth()->user()->checkSpPermission('report.receving') || auth()->user()->checkSpPermission('report.stock'))
    <li class="{{(Request::is('reports/receivings') || Request::is('reports/sales') || Request::is('reports/dailyreport/create')) ? 'active' : ''}} treeview">
      <a href="#">
        <i class="fa fa-money"></i> <span>{{trans('menu.reports')}}</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        @if(auth()->user()->checkSpPermission('report.receiving'))
        <li class="{{(Request::is('reports/receivings')) ? 'active' : ''}}"><a href="{{ url('/reports/receivings') }}"><i class="fa fa-circle-o"></i> {{__('Expense Report')}}</a></li>
        @endif
        @if(auth()->user()->checkSpPermission('report.sale'))
        <li class="{{(Request::is('reports/sales')) ? 'active' : ''}}"><a href="{{ url('/reports/sales') }}"><i class="fa fa-circle-o"></i> {{__('Income Report')}}</a></li>
        @endif
        @if(auth()->user()->checkSpPermission('report.stock'))
        <li class="{{(Request::is('reports/stocks')) ? 'active' : ''}}"><a href="{{ route('report.stock') }}"><i class="fa fa-circle-o"></i> {{__('Stock Report')}}</a></li>
        @endif
        @if(auth()->user()->checkSpPermission('dailyreport.create'))
        <li class="{{(Request::is('reports/dailyreport/create')) ? 'active' : ''}}"><a href="{{ url('/reports/dailyreport/create') }}"><i class="fa fa-circle-o"></i> {{trans('menu.daily_report')}}</a></li>
        @endif
        @if(auth()->user()->checkSpPermission('dailyreport.index'))
        <li class="{{(Request::is('reports/dailyreport')) ? 'active' : ''}}"><a href="{{route('dailyreport.index')}}"><i class="fa fa-circle-o" aria-hidden="true"></i> {{__('Report Summary')}}</a></li>
        @endif
      </ul>
    </li>
    @endif

      @if(auth()->user()->checkSpPermission('employees.index'))
        <li class="{{(Request::is('employees')) ? 'active' : ''}}"><a href="{{ url('/employees') }}"><i class="fa fa-user"></i> <span>{{trans('menu.employees')}}</span></a></li>
      @endif
      @if(Auth::user()->checkSpPermission('flexiblepossetting.create'))
        <li class="{{(Request::is('flexiblepossetting/create')) ? 'active' : ''}}"><a href="{{ route('flexiblepossetting.create') }}"><i class="fa fa-gear"></i> <span>{{__('Settings')}}</span></a></li>
      @endif
    </ul>
    @if(config('fpos.demo'))
    <div class="mt-5">
      <a href="https://codecanyon.net/item/flexiblepos-with-inventory-management-system/23633865"><img src="{{asset('images/side_banner.jpg')}}" class="img-responsive" alt=""></a>
    </div>
    @endif
</section>
</aside>
@endif