<script src="{!! \URLHelper::asset('libs/plugins/jQuery/jquery-2.2.3.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/plugins/jQueryUI/jQuery-ui.min.js', 'admin') !!}"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>$.widget.bridge('uibutton', $.ui.button);</script>

<script src="{!! \URLHelper::asset('libs/bootstrap/js/bootstrap.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/adminlte/js/app.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('libs/plugins/toastr/toastr.min.js', 'admin') !!}"></script>
<script src="{!! \URLHelper::asset('js/script.js', 'admin') !!}"></script>

<script type="text/javascript">
    var Boilerplate = {
        'csrfToken': "{!! csrf_token() !!}"
    };

    @if(Session::has('message-success'))
        toastr["success"]("{{ Session::get('message-success') }}", "Successfully !!!");
    @endif
        @if(Session::has('message-failed'))
        toastr["error"]("{{ Session::get('message-failed') }}", "Error !!!");
    @endif
</script>