/**
 * @author Dongsheng Cai <dongsheng@moodle.com>
 */

(function() {
    var each = tinymce.each;

    tinymce.PluginManager.requireLangPack('roledmedia');

    tinymce.create('tinymce.plugins.RoledmediaPlugin', {
        init : function(ed, url) {
            var t = this;

            t.editor = ed;
            t.url = url;

            // Register commands.
            ed.addCommand('mceRoledMedia', function() {
                ed.windowManager.open({
                    file : url + '/roledmedia.htm',
                    width : 480 + parseInt(ed.getLang('media.delta_width', 0)),
                    height : 480 + parseInt(ed.getLang('media.delta_height', 0)),
                    inline : 1
                }, {
                    plugin_url : url
                });
            });

            // Register buttons.
            ed.addButton('roledmedia', {
                    title : 'roledmedia.desc',
                    image : url + '/img/icon.gif',
                    cmd : 'mceRoledMedia'});

        },

        _parse : function(s) {
            return tinymce.util.JSON.parse('{' + s + '}');
        },

        getInfo : function() {
            return {
                longname : 'Roled media',
                author : 'Justin Hunt <poodllsupport@gmail.com>',
                version : "1.0"
            };
        }

    });

    // Register plugin.
    tinymce.PluginManager.add('roledmedia', tinymce.plugins.RoledmediaPlugin);
})();
