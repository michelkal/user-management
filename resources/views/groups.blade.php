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
  @if(Session::has('grpDeleteError'))

  <div class="alert alert-danger" role="alert">
    Group Deletion  <br>
    <a href="#" class="alert-link">{{ Session::get('grpDeleteError') }}</a>
  </div>

  @elseif(Session::has('grpDeleteSuccess'))

  <div class="alert alert-success" role="alert">
    Group Deletion <br>
    <a href="#" class="alert-link">{{ Session::get('grpDeleteSuccess') }}</a>
  </div>

  @endif
  <h3>Groups List</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Group Name</th>
        <th scope="col">Description</th>
        <th scope="col">Number of Users</th>
        <th scope="col">Group's Role</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
     @php 

     $i = 0;

     @endphp
     @foreach($groups as $grp)
     @php

     $i++;

     @endphp
     <tr>
      <th scope="row">{{ $i }}</th>
      <td>{{ $grp->grpName }}</td>
      <td>{{ $grp->grpDescription }}</td>
      <td>{{ count($grp->groupMembers) }}</td>
      <td>{{ $grp->role->slug }}</td>
      <td>
        <a href="#" grp-delete="{{ $grp->id }}" title="Delete Group" class="badge badge-danger">Delete</a>
      </td>
    </tr>
    @endforeach
    @csrf
  </tbody>
</table>
<p class="text-center">
  {{ $groups->links() }}
</p>
</div>

@include('bottom-menu')

<script>

  $('a[grp-delete]').on('click', function(e){
    e.preventDefault();
    var csrf = $('input[name=_token]').val(), grpId = $(this).attr('grp-delete');
    $.ajax({
      url: "grp-delete/"+grpId,
      type: "POST",
      dataType: "json",
      data: {delete: true, grp: grpId, _token: csrf},
      success: function(data){

        location.reload();
      }
    })
  })
</script>
</body>
</html>
