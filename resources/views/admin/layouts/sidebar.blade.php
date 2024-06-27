<!-- menu -->
<div class="menu">
    <div class="menu-header">
        <a href="index.html" class="menu-header-logo">
            <img src="{{ asset('admin/logo.jpg')}}" alt="logo" style="width: 200px">
        </a>
        <a href="{{ url('/')}}" class="btn btn-sm menu-close-btn">
            <i class="bi bi-x"></i>
        </a>
    </div>
    <div class="menu-body">
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown">
                <div class="avatar me-3">
                    <img src="{{ asset('admin/assets/images/user/man_avatar3.jpg')}}"
                         class="rounded-circle" alt="image">
                </div>
                <div>
                    <div class="fw-bold">Nasa Studio</div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="#" class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-person dropdown-item-icon"></i> Profile
                </a>
                <a href="#" class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-envelope dropdown-item-icon"></i> Inbox
                </a>
                <a href="#" class="dropdown-item d-flex align-items-center" data-sidebar-target="#settings">
                    <i class="bi bi-gear dropdown-item-icon"></i> Settings
                </a>
                <a href="./login.html" class="dropdown-item d-flex align-items-center text-danger"
                   target="_blank">
                    <i class="bi bi-box-arrow-right dropdown-item-icon"></i> Logout
                </a>
            </div>
        </div>
        <ul>
            <li class="menu-divider">E-Commerce</li>
            <li>
                <a  class="active"
                    href="index.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-bar-chart"></i>
                    </span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Orders</span>
                </a>
                <ul>
                    <li>
                        <a  href="./orders.html">List</a>
                    </li>
                    <li>
                        <a  href="./order-detail.html">Detail</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-truck"></i>
                    </span>
                    <span>Products</span>
                </a>
                <ul>
                    <li>
                        <a  href="./product-list.html">List
                            View</a>
                    </li>
                    <li>
                        <a  href="./product-grid.html">Grid
                            View</a>
                    </li>
                    <li>
                        <a  href="./product-detail.html">Product Detail</a>
                    </li>
                    <li>
                        <a  href="./shopping-cart.html">Shopping
                            Cart</a>
                    </li>
                    <li>
                        <a  href="./checkout.html">Checkout</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-wallet2"></i>
                    </span>
                    <span>Buyer</span>
                </a>
                <ul>
                    <li>
                        <a  href="./buyer-dashboard.html">Dashboard</a>
                    </li>
                    <li>
                        <a  href="./buyer-orders.html">Orders</a>
                    </li>
                    <li>
                        <a  href="./buyer-addresses.html">Addresses</a>
                    </li>
                    <li>
                        <a  href="./buyer-wishlist.html">Wishlist</a>
                    </li>
                </ul>
            </li>
            <li>
                <a  href="./customers.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-person-badge"></i>
                    </span>
                    <span>Customers</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-receipt"></i>
                    </span>
                    <span>Invoices</span>
                </a>
                <ul>
                    <li>
                        <a href="./invoices.html"
                           >List</a>
                    </li>
                    <li>
                        <a href="./invoice-detail.html"
                           >Detail</a>
                    </li>
                </ul>
            </li>
            <li class="menu-divider">Apps</li>
            <li>
                <a  href="./chats.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-chat-square"></i>
                    </span>
                    <span>Chats</span>
                    <span class="badge bg-success rounded-circle ms-auto">2</span>
                </a>
            </li>
            <li>
                <a href="./email.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <span>Email</span>
                </a>
                <ul>
                    <li>
                        <a  href="./email.html">
                            <span>Inbox</span>
                        </a>
                    </li>
                    <li>
                        <a  href="./email-detail.html">
                            <span>Detail</span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="./email-template.html">
                            <span>Email Template</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="./todo-list.html">
                    <span class="nav-link-icon">
                        <i class="bi bi-check-circle"></i>
                    </span>
                    <span>Todo App</span>
                </a>
                <ul>
                    <li>
                        <a  href="./todo-list.html">
                            <span>List</span>
                        </a>
                    </li>
                    <li>
                        <a  href="./todo-detail.html">
                            <span>Details</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-divider">Pages</li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-person"></i>
                    </span>
                    <span>Profile</span>
                </a>
                <ul>
                    <li>
                        <a  href="#">Post</a>
                    </li>
                    <li>
                        <a  href="#">Connections</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-person-circle"></i>
                    </span>
                    <span>Users</span>
                </a>
                <ul>
                    <li><a  href="#">List View</a></li>
                    <li><a  href="#">Grid View</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-lock"></i>
                    </span>
                    <span>Authentication</span>
                </a>
                <ul>
                    <li>
                        <a href="#" target="_blank">Login</a>
                    </li>
                    <li>
                        <a href="#" target="_blank">Register</a>
                    </li>
                    <li>
                        <a href="#" target="_blank">Reset Password</a>
                    </li>
                    <li>
                        <a href="#" target="_blank">Lock Screen</a>
                    </li>
                    <li>
                        <a href="#" target="_blank">Account Verified</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-exclamation-octagon"></i>
                    </span>
                    <span>Error Pages</span>
                </a>
                <ul>
                    <li>
                        <a href="#" target="_blank">404</a>
                    </li>
                    <li>
                        <a  href="#">Access Denied</a>
                    </li>
                    <li>
                        <a href="#" target="_blank">Under Construction</a>
                    </li>
                </ul>
            </li>
            <li>
                <a  href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-gear"></i>
                    </span>
                    <span>Settings</span>
                </a>
            </li>
            <li>
                <a  href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-wallet2"></i>
                    </span>
                    <span>Pricing Table</span>
                    <span class="badge bg-success ms-auto">New</span>
                </a>
            </li>
            <li>
                <a  href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    <span>Search Page</span>
                </a>
            </li>
            <li>
                <a  href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-question-circle"></i>
                    </span>
                    <span>FAQ</span>
                </a>
            </li>
            <li class="menu-divider">User Interface</li>
            <li>
                <a href="#" target="_blank">
                    <span class="nav-link-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </span>
                    <span>Components</span>
                </a>
                <ul>
                    <li>
                        <a  href="#">Accordion</a>
                    </li>
                    <li>
                        <a  href="#">Alerts</a>
                    </li>
                    <li>
                        <a  href="#">Badge</a>
                    </li>
                    <li>
                        <a  href="#">Breadcrumb</a>
                    </li>
                    <li>
                        <a  href="#">Buttons</a>
                    </li>
                    <li>
                        <a  href="#">Button Group</a>
                    </li>
                    <li>
                        <a  href="#">Card</a>
                    </li>
                    <li>
                        <a  href="#">Card Masonry</a>
                    </li>
                    <li>
                        <a  href="#">Carousel</a>
                    </li>
                    <li>
                        <a  href="#">Collapse</a>
                    </li>
                    <li>
                        <a  href="#">Dropdowns</a>
                    </li>
                    <li>
                        <a  href="#">List Group</a>
                    </li>
                    <li>
                        <a  href="#">Modal</a>
                    </li>
                    <li>
                        <a  href="#">Navs and Tabs</a>
                    </li>
                    <li>
                        <a  href="#">Pagination</a>
                    </li>
                    <li>
                        <a  href="#">Popovers</a>
                    </li>
                    <li>
                        <a  href="#">Progress</a>
                    </li>
                    <li>
                        <a  href="#">Spinners</a>
                    </li>
                    <li>
                        <a  href="#">Toasts</a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Tables</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">Tooltip</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" target="_blank">
                    <span class="nav-link-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </span>
                    <span>Forms</span>
                </a>
                <ul>
                    <li>
                        <a href="#">
                            <span>Form Elements</span>
                        </a>
                        <ul>
                            <li>
                                <a  href="#">Overview</a>
                            </li>
                            <li>
                                <a  href="#">Form Controls</a>
                            </li>
                            <li>
                                <a  href="#">Select</a>
                            </li>
                            <li>
                                <a  href="#">Checks and Radios</a>
                            </li>
                            <li>
                                <a  href="#">Range</a>
                            </li>
                            <li>
                                <a  href="#">Input Group</a>
                            </li>
                            <li>
                                <a  href="#">Floating Label</a>
                            </li>
                            <li>
                                <a  href="#">Form Layout</a>
                            </li>
                            <li>
                                <a  href="#">Validation</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Wizard</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Repeater</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>File Upload</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>CKEditor</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Range Slider</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Select2</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Tags Input</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Input Mask</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Datepicker</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Clock Picker</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-heart"></i>
                    </span>
                    <span>Content</span>
                </a>
                <ul>
                    <li>
                        <a  href="#">
                            <span>Typography</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Images</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Figures</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Avatar</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Icons</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Colors</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-bar-chart"></i>
                    </span>
                    <span>Charts</span>
                </a>
                <ul>
                    <li>
                        <a  href="#">Apex Chart</a>
                    </li>
                    <li>
                        <a  href="#">Chartjs</a>
                    </li>
                    <li>
                        <a  href="#">Justgage</a>
                    </li>
                    <li>
                        <a  href="#">Morsis</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-paperclip"></i>
                    </span>
                    <span>Extensions</span>
                </a>
                <ul>
                    <li>
                        <a  href="#">
                            <span>Vector Map</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">
                            <span>Datatable</span>
                        </a>
                    </li>
                    <li>
                        <a  href="#">Sweet Alert</a>
                    </li>
                    <li>
                        <a  href="#">Lightbox</a>
                    </li>
                    <li>
                        <a  href="#">Introjs</a>
                    </li>
                    <li>
                        <a  href="#">Nestable</a>
                    </li>
                    <li>
                        <a  href="#">Rating</a>
                    </li>
                    <li>
                        <a  href="#">Code Highlighter</a>
                    </li>
                </ul>
            </li>
            <li class="menu-divider">Other</li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="bi bi-list"></i>
                    </span>
                    <span>Menu Item</span>
                </a>
                <ul>
                    <li><a href="#">Menu Item 1</a></li>
                    <li>
                        <a href="#">Menu Item 2</a>
                        <ul>
                            <li>
                                <a href="#">Menu Item 2.1</a>
                            </li>
                            <li>
                                <a href="#">Menu Item 2.2</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="disabled">
                    <span class="nav-link-icon">
                        <i class="bi bi-hand-index-thumb"></i>
                    </span>
                    <span>Disabled</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- ./  menu -->