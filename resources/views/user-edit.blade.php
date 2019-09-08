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
            @if(Session::has('userEditError'))

            <div class="alert alert-danger" role="alert">
                User Creation  <br>
                <a href="#" class="alert-link">{{ Session::get('userEditError') }}</a>
            </div>

            @elseif(Session::has('userEditSuccess'))

            <div class="alert alert-success" role="alert">
                User Creation <br>
                <a href="#" class="alert-link">{{ Session::get('userEditSuccess') }}</a>
            </div>

            @endif
            <form action="{{ url('admin/edit-user') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email" value="{{ $user->user_email }}">
                    <small id="emailHelp" class="form-text text-muted">Can be used by the user as username to login</small>
                    <input type="hidden" name="member_id" value="{{ $user->id }}">
                    <input type="hidden" name="user_id" value="{{ @$user->isUser->id }}">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Name of the user" name="name" value="{{ $user->name }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" placeholder="Enter phone number of the user" name="phone" value="{{ $user->phone }}">
                </div>
                <div class="form-group">
                    <label for="grp">Assign User to Group</label>
                    <select class="form-control" name="group" id="grp" name="group">
                        @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ ($group->id == $user->group_id) ? 'selected' : '' }}>{{ $group->grpName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="userLogin">User can login</label>
                    <input type="checkbox" class="form-control" id="userLogin" aria-describedby="userLoginHelp" name="userLogin" value="YES" {{ (@$user->isUser->exists) ? 'checked="true"' : '' }}>
                    <small id="userLoginHelp" class="form-text text-muted">check this if you want user to be able to login - default password for the user will be <strong>{{ date('Ymd') }}</strong></small>
                </div>
                <button type="submit" class="btn btn-primary">Update Details</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
