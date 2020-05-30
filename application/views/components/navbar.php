
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->
            <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
              <form action="#" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex" style='margin-bottom: 0;'>
                <!-- <div class="input-group input-group-seamless ml-3">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                  <input class="navbar-search form-control" type="text" placeholder="Search for something..." aria-label="Search"> 
                </div> -->
              </form>
              <ul class="navbar-nav border-left flex-row ">
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar rounded-circle mr-2" 
                      src="<?=base_url('assets/img/'.$this->session->userdata('level').'.png')?>"
                      alt='User <?=$this->session->userdata('level')?> Icon' data-toggle='tooltip' data-placement='bottom'  
                      title='<?=ucwords($this->session->userdata('level'))?> Icon' />
                    <span class="d-none d-md-inline-block">
                        <?=$this->session->userdata('username')?>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="<?=site_url('welcome')?>">
                      <i class="material-icons">home</i> Welcome Screen 
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="<?=site_url('auth/logout')?>">
                      <i class="material-icons text-danger">&#xE879;</i> Logout </a>
                  </div>
                </li>
              </ul>
              <nav class="nav">
                <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left"
                  onClick='toggleSidebar()'>
                  <i class="material-icons">&#xE5D2;</i>
                </a>
              </nav>
            </nav>
          </div>
          <!-- / .main-navbar -->
<script language='Javascript'>
     $('[data-toggle=tooltip]').tooltip();
</script>