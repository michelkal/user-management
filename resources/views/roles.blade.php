<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/style.css') }}">
</head>
<body>
 @include('menu')
 <div class="container-fluid">
  @if(Session::has('roleDeleteError'))

  <div class="alert alert-danger" role="alert">
    Group Deletion  <br>
    <a href="#" class="alert-link">{{ Session::get('roleDeleteError') }}</a>
  </div>

  @elseif(Session::has('roleDeleteSuccess'))

  <div class="alert alert-success" role="alert">
    Group Deletion <br>
    <a href="#" class="alert-link">{{ Session::get('roleDeleteSuccess') }}</a>
  </div>

  @endif

  <h3>Roles List</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Role Name</th>
        <th scope="col">Role Slug</th>
        <th scope="col">Description</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @php 

      $i = 0;

      @endphp
      @foreach($roles as $role)
      @php

      $i++;

      @endphp
      <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $role->name }}</td>
        <td>{{ $role->slug }}</td>
        <td>{{ $role->details }}</td>
        <td>
          <a href="#" role-delete="{{ $role->id }}" title="Delete Role" class="badge badge-danger">Delete</a>
        </td>
      </tr>
      @endforeach
      @csrf
    </tbody>
  </table>
  <p class="text-center">
    {{ $roles->links() }}
  </p>
</div>

@include('bottom-menu')
<script>

  $('a[role-delete]').on('click', function(e){
    e.preventDefault();
    var csrf = $('input[name=_token]').val(), role = $(this).attr('role-delete');
    $.ajax({
      url: "role-delete/"+role,
      type: "POST",
      dataType: "json",
      data: {delete: true, role: role, _token: csrf},
      success: function(data){

        location.reload();
      }
    })
  })
</script>
</body>
</html>
