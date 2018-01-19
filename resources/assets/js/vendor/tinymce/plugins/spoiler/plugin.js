/*
 Spoiler plugin for TinyMCE 4 editor

 It adds special markup that in combination with a site-side JS script
 can create spoiler effect (hidden text that is shown on clik) on a web-page.
 An example of a site-side script: https://jsfiddle.net/romanvm/7w9shc27/

 (c) 2016, Roman Miroshnychenko <romanvm@yandex.ua>
 License: LGPL <http://www.gnu.org/licenses/lgpl-3.0.en.html>
 */
tinymce.PluginManager.add('spoiler', function (editor, url) {
    var $ = editor.$;
    editor.contentCSS.push(url + '/css/spoiler.css');

    function addSpoiler () {
        editor.windowManager.open({
            title:    "Add spoiler",
            body:     [
                {type: 'textbox', name: 'title', label: 'Title'}
            ],
            onsubmit: function (e) {
                var selection = editor.selection;
                var node = selection.getNode();
                if (node) {
                    editor.undoManager.transact(function () {
                        var content = selection.getContent();
                        var title = e.data.title;

                        if (!content) {
                            content = 'Текст спойлера';
                        }

                        if (!title) {
                            title = 'Спойлер';
                        }
                        selection.setContent(
                            '<ul class="collapsible popout">' +
                            '   <li>' +
                                    '<div class="collapsible-header">' +
                                        '<strong>' + title + '</strong>' +
                                        '<span class="pull-right"><em>Спойлер</em></span>' +
                                    '</div>' +

                                    '<div class="collapsible-body">' + content + '</div>' +
                                '</li>' +
                            '</ul>'
                        );
                    });
                    editor.nodeChanged();
                }
            }
        })
    }

    function removeSpoiler () {
        var selection = editor.selection;
        var node = selection.getNode();
        // magic, do not choose collapsible with - symbol
        if (node && node.className.substring(0, 12) === 'collapsible ') {
            editor.undoManager.transact(function () {
                var newPara = document.createElement('p');
                newPara.innerHTML = node.getElementsByClassName('collapsible-body')[0].innerHTML;
                node.parentNode.replaceChild(newPara, node);
            });
            editor.nodeChanged();
        }
    }

    editor.on('PreProcess', function (e) {
        $('[class*="collapsible"]', e.node).each(function (index, elem) {
            if (elem.hasAttribute('contenteditable')) {
                elem.removeAttribute('contentEditable');
            }
        });
    });

    editor.on('SetContent', function () {
        $('[class*="collapsible"]').each(function (index, elem) {
            if (!elem.hasAttribute('contenteditable')) {
                var $elem = $(elem);
                if ($elem.hasClass('collapsible')) {
                    elem.contentEditable = false;
                }
                else if ($elem.hasClass('collapsible-header')) {
                    $(elem).children('strong')[0].contentEditable = true;
                }
                else if ($elem.hasClass('collapsible-body')) {
                    elem.contentEditable = true;
                }
            }
        });
    });

    editor.addButton('spoiler-add',
        {
            tooltip: 'Add spoiler',
            image:   url + '/img/eye-plus.png',
            onclick: addSpoiler
        });

    editor.addMenuItem('spoiler-add',
        {
            text:    'Add spoiler',
            context: 'format',
            onclick: addSpoiler
        });

    editor.addButton('spoiler-remove',
        {
            tooltip: 'Remove spoiler',
            image:   url + '/img/eye-blocked.png',
            onclick: removeSpoiler
        });

    editor.addMenuItem('spoiler-remove',
        {
            text:    'Remove spoiler',
            context: 'format',
            onclick: removeSpoiler
        });
});
