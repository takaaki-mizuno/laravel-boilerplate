<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="{!! \URLHelper::asset('libs/plugins/jQuery/jQuery-2.1.4.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/bootstrap/js/bootstrap.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs//plugins/iCheck/icheck.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/adminlte/js/app.min.js', 'admin') !!}"></script>
<script type="text/javascript">
    var Boilerplate = {
        'csrfToken': "{!! csrf_token() !!}"
    };
</script>