<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <script src="{{URL::asset('js/eloquent.js')}}"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script>
            var UserModel = EloquentJs.ModelFactory('User');

            UserModel.all().then(data => console.log('All users: ', data));
            UserModel.find(10).then(data => console.log('User with id=10: ', data));
            UserModel.where('id',  10).get().then(data => console.log('User with id=10: ', data));
            UserModel.where('name', 'like', 'Dr.%').get().then(data => console.log('Doctors: ', data));
            UserModel.where('name', 'like', 'Dr.%').orWhere('name', 'like', 'Prof.%').get().then(data => console.log('Drs and Profs: ', data));

            var ObjectModel = EloquentJs.ModelFactory('Object');
            ObjectModel.all().then(data => console.log('All objects: ', data));
            UserModel.where('id',  '<', 5).with('objects').get().then(data => console.log('Some users with related objects: ', data));

            //update
            ObjectModel.where('id', '<', 5).update({capacity: 1});

            //Count
            UserModel.count().then(data => console.log('Count users: ', data))
            UserModel.where('email', 'like', '%example.org').count().then(data => console.log('Count users with mail account at example.org: ', data))

            //paginate
            UserModel.paginate(10,1).then(data => console.log('Paginate users: ', data));
            UserModel.orderBy('name').paginate(10,1).then(data => console.log('Paginate ordered users: ', data));;


        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>
