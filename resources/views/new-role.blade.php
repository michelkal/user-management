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
 <div class="flex-center position-ref full-height">
  <div class="content">
    <div class="m-b-md">
      @if(Session::has('roleCreateError'))

      <div class="alert alert-danger" role="alert">
        Role Creation  <br>
        <a href="#" class="alert-link">{{ Session::get('roleCreateError') }}</a>
      </div>

      @elseif(Session::has('roleCreateSuccess'))

      <div class="alert alert-success" role="alert">
        Role Creation <br>
        <a href="#" class="alert-link">{{ Session::get('roleCreateSuccess') }}</a>
      </div>

      @endif
      <form action="{{ url('admin/new-role') }}" method="post">
        @csrf
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" id="name" placeholder="Enter The Role's Name" name="roleName">
        </div>
        <div class="form-group">
          <label for="slug">Slug</label>
          <input type="text" class="form-control" id="slug" placeholder="Enter short Name of this role" aria-describedby="slugHelp" name="slug">
          <small id="slugHelp" class="form-text text-muted">This is the shortname that describes the new role you're creating</small>
        </div>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Bief Description of the Role</label>
          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="roleDetails"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create New Role</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
