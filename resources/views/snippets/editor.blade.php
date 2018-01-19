<script type="text/javascript">
    tinymce.init({
        selector:            'textarea',
        height:              300,
        element_format:      'html',
        plugins:             [
            'advlist autolink lists link image charmap hr autoresize media contextmenu paste textcolor colorpicker textpattern autosave wordcount code'
        ],
        external_plugins:    {
            spoiler: '{{ '/tinymce/plugins/spoiler/plugin.js?' . config('app.build') }}'
        },
        spoiler_caption:     'Спойлер',
        autosave_interval:   "10s",
        menu:                {},
        toolbar1:            'restoredraft | undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image | preview media | forecolor backcolor | spoiler-add spoiler-remove | code',
        language:            'ru',
        language_url:        '/tinymce/ru.js',
        theme:               'modern',
        skin:                'light',
        skin_url:            '/tinymce/skins/light',
        valid_elements:      '{{ config('purifier.settings.default')['HTML.Allowed']  }}',
        default_link_target: '_blank',
        target_list:         false,
        body_class:          'white',
    });

    $(document).on('focusin', function (e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });
</script>