    <nav class='navbar navbar-inverse navbar-fixed-top'>
      <div class='container-fluid'>
        <div class='navbar-header'>
          <button aria-controls='navbar' aria-expanded='false' class='navbar-toggle collapsed' data-target='#navbar' data-toggle='collapse' type='button'>
            <span class='sr-only'>Toggle navigation</span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
          </button>
          <a class='navbar-brand' href='#' id='menu-toggle'>Main Menu</a>
        </div>
        <div class='navbar-collapse collapse' id='navbar' data-hover="dropdown">
          <ul class='nav navbar-nav navbar-right'>
            <li class="dropdown"> 
              <a aria-expanded='false' aria-haspopup='true' data-target='#' data-toggle='dropdown' href='#' role='button'>
                Notifications
                  @if ($ncount >= 1)
                  <span class="badge" style="background:red;">{{ $ncount }}</span>
                  @endif
              </a>
              <ul class="dropdown-menu">
                <li>
                    <a href="#">
                        <b>Zone 1 Intelligent Prediction</b> &nbsp <small>today</small>
                        <br/>
                        - Any further increase in temperature may affect servers operation
                        <br/>
                    </a>
                </li>
                <li role="separator" class="divider"></li>
                <li>
                    <a href="#">
                      <b>Zone 2 Intelligent Prediction</b> &nbsp 1 day ago
                        <br/>
                        - Any further temperature droppping may result in unnecessary electrical power usage
                    </a>
                </li>
                <li role="separator" class="divider"></li>
                <li>
                    <a href="#">
                        <center>
                            <span class="glyphicon glyphicon-option-horizontal"></span>
                        </center>
                    </a>
                </li>
              </ul>
            </li>

            <li class='dropdown'>
              <a aria-expanded='false' aria-haspopup='true' data-target='#' data-toggle='dropdown' href='#' role='button'>
                Settings
                <span class='caret'></span>
              </a>
              <ul class='dropdown-menu'>
                <li>
                  <a href='zones'>
                    <span class="glyphicon glyphicon-th-large"></span>
                    Manage Zones
                  </a>
                </li>
                <li>
                  <a href='parameters'>
                    <span class="glyphicon glyphicon-th-list"></span>
                    Manage Parameters
                  </a>
                </li>
                <li>
                  <a href='readings'>
                    <span class="glyphicon glyphicon-scale"></span>
                    Manage Readings
                  </a>
                </li>
                <li>
                  <a href='thresholds'>
                    <span class="glyphicon glyphicon-dashboard"></span>
                    Manage Thresholds
                  </a>
                </li>
                @if(Auth::user()->isAdmin())
                <li>
                  <a href="manage_accounts">
                    <span class="glyphicon glyphicon-user"></span>
                    Manage Accounts
                  </a>
                </li>
                <li>
                  <a href="logs">
                    <span class="glyphicon glyphicon-list-alt"></span>
                    System Logs
                  </a>
                </li>
                @endif
              </ul>
            </li>
            <li class="dropdown">
              <a aria-expanded='false' aria-haspopup='true' data-target='#' data-toggle='dropdown' href='#' role='button'>
                Welcome, {{Auth::user()->name}}
                <span class='caret'></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                    <a href="profile"> <span class="glyphicon glyphicon-user"></span> 
                        Profile 
                    </a>
                </li>
                <li>
                    <a href="auth/logout">
                      <span class="glyphicon glyphicon-log-out"></span>
                      Logout
                    </a>
                </li>
              </ul>
            </li>
          </ul>
          <!--  -->
          <!-- <form class="navbar-form navbar-right"> -->
          <!-- <input type="text" class="form-control" placeholder="Search..."> -->
          <!-- </form> -->
        </div>
      </div>
    </nav>