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
         @if(Session::has('apiCreateError'))

         <div class="alert alert-danger" role="alert">
            API Creation  <br>
            <a href="#" class="alert-link">{{ Session::get('apiCreateError') }}</a>
        </div>

        @elseif(Session::has('apiCreateSuccess'))

        <div class="alert alert-success" role="alert">
            API Creation <br>
            <a href="#" class="alert-link">{{ Session::get('apiCreateSuccess') }}</a>
        </div>

        @endif
        <form action="{{ url('admin/new-api-key') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="org">Organization Name</label>
                <input type="text" class="form-control" id="org" aria-describedby="orgHelp" placeholder="Enter Organization Name" name="orgName">
                <small id="orgHelp" class="form-text text-muted">The name of the organization that will be consuming the API</small>
            </div>
            <div class="form-group">
                <label for="orgContact">Contact Person</label>
                <input type="text" class="form-control" id="orgContact" placeholder="Enter Name of the contact person" name="orgContact">
                <small id="orgHelp" class="form-text text-muted">The name of contact person representing the organization</small>
            </div>
            <button type="submit" class="btn btn-primary">Create API Token</button>
        </form>
    </div>
</div>
</div>
</body>
</html>
