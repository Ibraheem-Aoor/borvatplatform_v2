  <!-- Sidebar Start -->
  <div class="sidebar pe-4 pb-3">
      <nav class="navbar bg-light navbar-light">
          <a href="/" class="navbar-brand mx-4 mb-3">
              <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>BORVAT-BOL</h3>
          </a>
          <div class="d-flex align-items-center ms-4 mb-4">
              <div class="position-relative">
                  <img class="rounded-circle" src="{{ asset('assets/img/user.jpg') }}" alt=""
                      style="width: 40px; height: 40px;">
                  <div
                      class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                  </div>
              </div>
              <div class="ms-3">
                  <h6 class="mb-0">Jhon Doe</h6>
                  <span>Admin</span>
              </div>
          </div>
          <div class="navbar-nav w-100">
              <a href="/" class="nav-item nav-link {{ areActiveRoutes(['home']) }}"><i
                      class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
              {{-- <div class="nav-item dropdown">
                  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><img
                          src="{{ asset('assets/img/bol-logo.jpg') }}" style="border-radius:50%" width="40px"
                          alt="">&nbsp; Bol API</a>
                  <div class="dropdown-menu bg-transparent border-0">
                      <a href="{{ route('order.index') }}" class="dropdown-item">orders</a>
                      <a href="{{ route('shippment.index') }}" class="dropdown-item">Shipments</a>

                  </div>
              </div> --}}
              {{--  Home For Quick Api data orders & shipments. --}}
              <div class="nav-item dropdown">
                  <a href="#" class="nav-link dropdown-toggle {{ areActiveRoutes(['order.index', 'shippment.index']) }} {{ areActiveRoutes(['order.index', 'shippment.index'], 'show') }}" data-bs-toggle="dropdown"><img
                          src="{{ asset('assets/img/bol-logo.jpg') }}" style="border-radius:50%" width="40px"
                          alt="">&nbsp;bol Home </a>
                  <div
                      class="dropdown-menu bg-transparent border-0 {{ areActiveRoutes(['order.index', 'shippment.index']) }} {{ areActiveRoutes(['order.index', 'shippment.index'], 'show') }}">
                      <a href="{{ route('order.index') }}"
                          class="dropdown-item {{ areActiveRoutes(['order.index']) }}">orders</a>
                      <a href="{{ route('shippment.index') }}"
                          class="dropdown-item {{ areActiveRoutes(['shippment.index']) }}">Shipments</a>
                  </div>
              </div>
              <div class="nav-item dropdown ">
                  <a href="#" class="nav-link dropdown-toggle {{ areActiveRoutes(['product.no-image', 'product.index']) }} {{ areActiveRoutes(['product.no-image', 'product.index'], 'show') }}" data-bs-toggle="dropdown"><i
                          class="fa fa-th me-2"></i>Products</a>
                  <div
                      class="dropdown-menu bg-transparent border-0 {{ areActiveRoutes(['product.no-image', 'product.index']) }} {{ areActiveRoutes(['product.no-image', 'product.index'], 'show') }}">
                      <a href="{{ route('product.no-image') }}"
                          class="dropdown-item {{ areActiveRoutes(['product.no-image']) }}">No Image</a>
                      <a href="{{ route('product.index') }}"
                          class="dropdown-item {{ areActiveRoutes(['product.index']) }}">All Products</a>
                  </div>
              </div>
              <div class="nav-item dropdown">
                  <a href="#" class="nav-link dropdown-toggle  {{ areActiveRoutes(['order.archive', 'shippment.archive', 'shippment.recents']) }} {{ areActiveRoutes(['order.archive', 'shippment.archive', 'shippment.recents'], 'show') }}" data-bs-toggle="dropdown"><i
                          class="fa fa-file me-2"></i>Archive</a>
                  <div
                      class="dropdown-menu bg-transparent border-0 {{ areActiveRoutes(['order.archive', 'shippment.archive', 'shippment.recents']) }} {{ areActiveRoutes(['order.archive', 'shippment.archive', 'shippment.recents'], 'show') }}">
                      <a href="{{ route('order.archive') }}" class="dropdown-item {{ areActiveRoutes(['order.archive']) }}">All Orders</a>
                      <a href="{{ route('shippment.archive') }}" class="dropdown-item {{ areActiveRoutes(['shippment.archive']) }}">All Shipments</a>
                      <a href="{{ route('shippment.recents') }}" class="dropdown-item {{ areActiveRoutes(['shippment.recents']) }}">Recent Downloads</a>
                  </div>
              </div>
              {{-- <div class="nav-item dropdown">
                  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                          class="fa fa-wrench me-2"></i>Settings</a>
                  <div class="dropdown-menu bg-transparent border-0">
                      <a href="{{ route('clear-cache') }}" class="dropdown-item"> <i class="fa fa-folder-gear"></i>
                          CLEAR CACHE</a>
                      <a href="{{ route('settings.sender-details.index') }}" class="dropdown-item"> <i
                              class="fa fa-address"></i> SENDER DETAILS</a>
                      <a href="{{ route('settings.email-msg.index') }}" class="dropdown-item"> <i
                              class="fa fa-address"></i> EMAIL MESSAGE</a>
                  </div>
              </div> --}}
              <a href="{{ route('bol_accounts.index') }}" class="nav-item nav-link {{ areActiveRoutes(['bol_accounts.index']) }}"><i
                      class="fa fa-cubes me-2"></i>Accounts</a>
          </div>
      </nav>
  </div>
  <!-- Sidebar End -->
