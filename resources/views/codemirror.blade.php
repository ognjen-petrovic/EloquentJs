<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>EloquentJs - codemirror</title>
        <script src="{{URL::asset('js/eloquent.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.38.0/codemirror.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.38.0/mode/javascript/javascript.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.38.0/codemirror.min.css">

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
            .CodeMirror {
                border: 1px solid #eee;
                height: auto;
            }
        </style>
    </head>
    <body>
        <textarea id="cm">
var UserModel = EloquentJs.ModelFactory('User');
UserModel.find(10).then(data => console.log('User with id=10: ', data));
//UserModel.all().then(data => console.log('All users: ', data));
//UserModel.where('id',  10).get().then(data => console.log('User with id=10: ', data));
//UserModel.where('name', 'like', 'Dr.%').get().then(data => console.log('Doctors: ', data));
//UserModel.where('name', 'like', 'Dr.%').orWhere('name', 'like', 'Prof.%').get().then(data => console.log('Drs and Profs: ', data));

//var ObjectModel = EloquentJs.ModelFactory('Object');
//ObjectModel.all().then(data => console.log('All objects: ', data));
//UserModel.where('id',  '<', 5).with('objects').get().then(data => console.log('Some users with related objects: ', data));

//update
//ObjectModel.where('id', '<', 5).update({capacity: 1});

//paginate
//UserModel.paginate(10,1).then(data => console.log('Paginate users: ', data));
//UserModel.orderBy('name').paginate(10,1).then(data => console.log('Paginate ordered users: ', data));
        </textarea>
        <br>
        <button onclick="exec()">Execute</button>&nbsp;<button onclick="clearResult()">Clear result</button>
        <br><br>
        <textarea id="result"></textarea>

    <script>
        var cm = document.getElementById('cm');
        var editor = CodeMirror.fromTextArea(cm, {
            lineNumbers: true,
            mode: 'javascript'
        });

        var result = CodeMirror.fromTextArea(document.getElementById('result'), {
            lineNumbers: true,
            mode: 'javascript'
        });


        var consoleLog = console.log;
        console.log = function(...params)
        {
            consoleLog(params);
            var buffer = '';
            for (var param of params)
            {
                if (typeof param == 'string')
                {
                    buffer += '//' + param + '\n';
                }
                else
                {
                    buffer += JSON.stringify(param, false, 2) + '\n';
                }
            }
            result.getDoc().setValue(result.getValue() + buffer);
        }

        var exec = function()
        {
            var js = editor.getValue();//http://codemirror.net/doc/manual.html#getValue
            var s = document.createElement('script');
            s.textContent = js;//inne
            document.body.appendChild(s);
            s.parentNode.removeChild(s);
        }

        function clearResult()
        {
            result.getDoc().setValue('');
        }

        exec();
    </script>
    </body>
</html>
