@if (Session::has('alert.message'))
    <script type="text/javascript">
        swal({
            @if (Session::has('alert.title'))
                title: "{{ Session::get('alert.title') }}",
            @endif
            html:               "{!! Session::get('alert.message') !!}",
            type:               "{{ Session::get('alert.type') }}",
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass:  'btn btn-danger',
            customClass:        'modal-content',
            buttonsStyling:     false,
        });
    </script>
@endif
