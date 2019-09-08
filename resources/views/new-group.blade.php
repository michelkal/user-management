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
      @if(Session::has('groupCreateError'))

      <div class="alert alert-danger" role="alert">
        Group Creation  <br>
        <a href="#" class="alert-link">{{ Session::get('groupCreateError') }}</a>
      </div>

      @elseif(Session::has('groupCreateSuccess'))

      <div class="alert alert-success" role="alert">
        Group Creation <br>
        <a href="#" class="alert-link">{{ Session::get('groupCreateSuccess') }}</a>
      </div>

      @endif
      <form action="{{ url('admin/new-group') }}" method="post">
        @csrf
        <div class="form-group">
          <label for="name">Group Name</label>
          <input type="text" class="form-control" id="name" placeholder="Enter The Group's Name" name="grpName">
        </div>
        <div class="form-group">
          <label for="slug">Select Group Role</label>
          <select class="form-control" name="groupRole">
            @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Bief Description of the Group</label>
          <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="grpDescription" aria-describedby="slugHelp"></textarea>
          <small id="groupHelp" class="form-text text-muted">This is a brief description of the new group you're creating</small>
        </div>
        <button type="submit" class="btn btn-primary">Create New Group</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
