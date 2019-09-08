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
  @if(Session::has('deleteError'))

  <div class="alert alert-danger" role="alert">
    User Deletion  <br>
    <a href="#" class="alert-link">{{ Session::get('deleteError') }}</a>
  </div>

  @elseif(Session::has('deleteSuccess'))

  <div class="alert alert-success" role="alert">
    User Deletion <br>
    <a href="#" class="alert-link">{{ Session::get('deleteSuccess') }}</a>
  </div>

  @endif
  <h3>Users List</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Phone</th>
        <th scope="col">Group</th>
        <th scope="col">Role</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @php 

      $i = 0;

      @endphp
      @foreach($users as $user)

      @php

      $i++;

      @endphp
      <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $user->name }}</td>
        <td>{{ $user->phone }}</td>
        <td>{{ $user->group->grpName }}</td>
        <td>{{ $user->group->role->slug }}</td>
        <td>
          <a href="{{ url('admin/user/edit/'.$user->id) }}" title="Edit User" class="badge badge-info">Edit</a> 
          <a href="#" title="Delete User" class="badge badge-danger" user-delete="{{ $user->id }}">Delete</a>
        </td>
        
      </tr>
      @endforeach

      @csrf
    </tbody>
  </table>
  <p class="text-center">
    {{ $users->links() }}
  </p>
</div>

@include('bottom-menu')

<script>

  $('a[user-delete]').on('click', function(e){
    e.preventDefault();
    var csrf = $('input[name=_token]').val(), userId = $(this).attr('user-delete');
    $.ajax({
      url: "dlt-usr/"+userId,
      type: "POST",
      dataType: "json",
      data: {delete: true, user: userId, _token: csrf},
      success: function(data){

        location.reload();
      }
    })
  })
</script>
</body>
</html>
