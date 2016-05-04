<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">    
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="login page">
        <meta name="author" content="egenting-inti-fyp">
        <meta name="keywords" content="login">

        <!-- Title -->
        <title>eGenting: Log in to the system</title>

        <!-- Favorite icon -->
        <link rel="icon" href="favicon.ico">

        <!-- CSS: Bootstrap, Signin -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/signin.css">
    </head>

    <body>
        <div class="container">
            <form class="form-signin" method="POST" action="/auth/login">
                {!! csrf_field() !!}

                <h2 class="form-signin-heading">Log In</h2>
                
                <label for="username" class="sr-only">Username</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Company Email" required autofocus>
                
                <label for="password" class="sr-only">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                
                <div class="checkbox">
                    <label>
                    <input type="checkbox" value="remember"> Remember me
                    </label>
                </div>
                
                <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>

            </form>

            @if (count ($errors) > 0) 
                <ul>
                    @foreach($errors->all() as $error)
                        <li> {{ $error }} </li>
                    @endforeach
                </ul>
            @endif

        </div> <!-- /container -->
    </body>
</html>
