 <ul class="nav justify-content-end">
  <li class="nav-item">
    <a class="btn btn-primary" href="{{ url('admin/home') }}">
      Users
    </a>
  </li>
  <li class="nav-item">
    <a class="btn btn-primary" href="{{ url('admin/groups') }}">
      Groups 
    </a>
  </li>
  <li class="nav-item">
    <a class="btn btn-primary" href="{{ url('admin/roles') }}">
      Roles
    </a>
  </li>

  <li class="nav-item">
    <a class="btn btn-primary" href="{{ url('admin/api') }}">
      API Access
    </a>
  </li>

  <li class="nav-item">
    {{ Auth::user()->name }} <br>
    <a href="{{ url('logout') }}" title="Disconnect">Logout</a>
  </li>
</ul>