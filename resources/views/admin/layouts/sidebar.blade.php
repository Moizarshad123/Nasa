 <!-- Menu -->
 <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
        
        <span class="app-brand-text demo menu-text fw-bolder ms-2">
          <img src="{{ asset('admin/assets/img/logo.png') }}" style="height: 190px;width: 275px;" alt="">
        </span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

      @if(auth()->user()->role_id == 1)
        <!-- Dashboard -->
        <li class="menu-item {{ request()->IS('admin/dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
          </a>
        </li>

        

        <li class="menu-item {{ request()->IS('admin/orderBigDC') ? 'active' : '' }}">
          <a href="{{ route('admin.orderBigDC.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Analytics">orderBigDC</div>
          </a>
        </li>

        {{-- <li class="menu-item {{ request()->IS('admin/players') ? 'active' : '' }}">
          <a href="{{ route('admin.players.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cube-alt"></i>
            <div data-i18n="Analytics">Players</div>
          </a>
        </li>

        <li class="menu-item {{ request()->IS('admin/attendance') ? 'active' : '' }}">
          <a href="{{ route('admin.attendance.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cube-alt"></i>
            <div data-i18n="Analytics">Attendance</div>
          </a>
        </li>
        <li class="menu-item {{ request()->IS('admin/coaches') ? 'active' : '' }}">
          <a href="{{ route('admin.coaches.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cube-alt"></i>
            <div data-i18n="Analytics">Coaches</div>
          </a>
        </li>

        <li class="menu-item {{ request()->IS('admin/salary') ? 'active' : '' }}">
          <a href="{{ route('admin.salary.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cube-alt"></i>
            <div data-i18n="Analytics">Salary</div>
          </a>
        </li>
        <li class="menu-item {{ request()->IS('admin/fee-listing') ? 'active' : '' }}">
          <a href="{{ route('admin.feeListing') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-crown"></i>
            <div data-i18n="Basic">Fees</div>
          </a>
        </li> --}}
      @endif



    </ul>
  </aside>
  <!-- / Menu -->