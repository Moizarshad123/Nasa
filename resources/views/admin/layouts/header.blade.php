<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center header">
            {{-- <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                  aria-label="Search..." />
              </div> --}}
             
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            @if(auth()->user()->role_id == 1)
             <!-- User -->
             <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    {{-- @if(count($notifications) > 0)
                        <span class="my-badge">{{ count($notifications) }}</span>
                    @else
                        <span class="my-badge">0</span>
                    @endif --}}
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAhVJREFUSEvt1kvITVEUwPHflzKT18BMSlEGSB5FXoWSgYEyQBFlIkMpygQpQ2QgUcgAKSXJI5SECCMmMjGQIo+ZvM762qdu173nnH2+r+7EmpzO2Xut/9lrr9eQAcnQgLjagsdjd/rp4/iae4A24Il4jBkJ9hqL8D0HngOegP3YgYB3yiecwSF8a/IDTcGbcAyTa4x+xE5cq4M3Ae/D4Q5DL3AXz9O3+ViN2R17duFkFbwOvAq3k4HP2IybfQyux3mMS+sL8KwfvAoca29SEP1IAfSyxoXLcJ/hbHlYeGZpG/A6XE+KB3Cw7t7S+gmEq0PmIa7mH6k6cQRT5OqvIrAmNY1WzEyeCtheHM0F38BavMLchqctt33AFJzD1lzwveK+VuBBeuaw436X4E6K+CxXR5Asbwmu1a2641rlChfU6v4Hl96LKnQJY1PlWpMTWbiVgioKz8ZetbuXq7ekNCjXNhR1+GomOHSuJJ2oA9twodNGN3hakXfv0oafiK50ORNabo+TXiwq3hj8KdrmVLwvF7vBkbeRvyHbcbYltFSL3n06vaxMdXz4tQocFevLCMExPMxpAl5YlMknI4T1U4/x6Gk/V8d9nCr66vRRhr9Nk0kEWk9XjzKvv7m6CaRbM7pUTBXhmU75nXpvxEUjyQWH0eive7qsH0HMZo2lDTiMzyqq0+KUn48Qs3WWtAVnQXptHhj4L+DWYR/kVSW3AAAAAElFTkSuQmCC"/>
                </a>
                <ul class="dropdown-menu-notification dropdown-menu dropdown-menu-end">
                    {{-- @if(count($notifications) > 0)
                        @forelse ($notifications as $item)
                            <li>
                                <div class="dropdown-item">
                                    <span class="align-middle">{{$item->message}}</span>
                                </div>
                            </li>
                        @empty  
                        @endforelse
                    @endif --}}
                </ul>
            </li>
            <!--/ User -->
            @endif


            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('admin/assets/img/avatars/1.png')}}" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('admin/assets/img/avatars/1.png')}}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li> --}}

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
