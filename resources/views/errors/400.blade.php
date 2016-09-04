
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>400 - Unknown Error </title>
    <style>
        @import url(http://fonts.googleapis.com/css?family=Bree+Serif|Source+Sans+Pro:300,400);
        *{
            maring: 0;
            padding: 0;
        }
        body{
            font-family: 'Source Sans Pro', sans-serif;
            background: #eeeeee;
            color: #888888;
        }
        a:link{
            color: #888888;
            text-decoration: none;
        }
        a:active{
            color: #1f3759;
            text-decoration: none;
        }
        a:hover{
            color: #9fb7d9;
            text-decoration: none;
        }
        a:visited{
            color: #888888;
            text-decoration: none;
        }

        a.underline, .underline{
            text-decoration: underline;
        }

        .bree-font{
            font-family: 'Bree Serif', serif;
        }

        #content{
            margin: 0 auto;
            width: 960px;
        }

        .clearfix:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            line-height: 0;
            height: 0;
        }

        .clearfix {
            display: block;
        }

        #logo {
            margin: 1em;
            float: left;
            display: block;
        }
        nav{
            float: right;
            display: block;
        }
        nav ul > li{
            list-style: none;
            float: left;
            margin: 0 2em;
            display: block;
        }

        #main-body{
            text-align: center;
        }

        .enormous-font{
            font-size: 10em;
            margin-bottom: 0em;
        }
        .big-font{
            font-size: 2em;
        }
        hr{
            width: 25%;
            height: 1px;
            background: #888888;
            border: 0px;
        }
    </style>
</head>
<body>
<div id="content">
    <div class="clearfix"></div>

    <div id="main-body">
        <p class="enormous-font bree-font"> 400 </p>
        <p class="big-font"> Something Wrong with your access..</p>
        <hr>
        <p class="big-font"> Let’s return to the <a href="{!! action('User\IndexController@index') !!}">home page</a></p>
    </div>
</div>
</body>
</html>
