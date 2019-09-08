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
  <h3>API Access Users</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Organization Name</th>
        <th scope="col">Organization Code</th>
        <th scope="col">Contact Person</th>
        <th scope="col">API Access Key</th>
      </tr>
    </thead>
    <tbody>
      @php 

      $i = 0;

      @endphp
      @foreach($apis as $api)

      @php

      $i++;

      @endphp
      <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $api->orgName }}</td>
        <td>{{ $api->orgCode }}</td>
        <td>{{ $api->orgContact }}</td>
        <td>{{ $api->tokenKey->accessKey }}</td>
        
      </tr>
      @endforeach
    </tbody>
  </table>
  <p class="text-center">
    {{ $apis->links() }}
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
