<script id="chatBroEmbedCode">
    /* Chatbro Widget Embed Code Start */
    function ChatbroLoader (chats, async) {
        async = async !== false;
        var params = {
            embedChatsParameters: chats instanceof Array ? chats : [chats],
            needLoadCode:         typeof Chatbro === 'undefined'
        };
        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        xhr.onload = function () {
            eval(xhr.responseText)
        };
        xhr.onerror = function () {
            console.error('Chatbro loading error')
        };
        xhr.open('POST', '//www.chatbro.com/embed_chats/', async);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('parameters=' + encodeURIComponent(JSON.stringify(params)))
    }

    /* Chatbro Widget Embed Code End */
    ChatbroLoader({
        encodedChatId: '1662',
        chatLanguage:  '{{ config('app.locale') }}',

        siteDomain: '{{ config('app.domain') }}',

        @if (Auth::check())
            siteUserExternalId: '{{ Auth::id() }}',
            siteUserFullName: '{{ addslashes(Auth::user()->nickname) }}',
            siteUserAvatarUrl: '{{ Auth::user()->fullAvatarLink() }}',
            siteUserProfileUrl: '{{ route('profile_page', ['slug' => Auth::user()->slug], true) }}',

            @if (Auth::user()->isGroup(config('roles.admins')))
                permissions: ['ban', 'delete'],
            @endif

            signature: '{{ Auth::user()->getChatbroSignature() }}',
        @else
            siteUserExternalId: null,
            siteUserFullName: null,
            siteUserProfileUrl: null,
            signature: null
        @endif
    });
</script>
