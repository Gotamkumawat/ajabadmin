/* =====================================================================
 *  Admin-wide CKEditor 4 standardisation
 * ---------------------------------------------------------------------
 *  Goal: EVERY rich-text editor in the admin shows exactly ONE toolbar
 *  — the same layout the old (textAngular) admin used — and nothing else.
 *
 *  Toolbar (ported 1:1 from old admin's textAngularSetup.js):
 *    Row 1 : H1 H2 H3 H4 H5 H6  P  pre  quote  | couplet | refrain
 *    Row 2 : Bold Italic Underline | UL OL | Redo Undo | RemoveFormat
 *    Row 3 : JustifyLeft Center Right | Indent Outdent
 *    Row 4 : Source(Toggle HTML)  Image  Link  Video
 *
 *  Headings (H1..H6 / P / pre / quote) are individual buttons — NOT a
 *  Format dropdown — to match the old admin's look exactly.
 *
 *  "couplet" / "refrain" are custom buttons carried over from the old
 *  admin. They wrap the selected text in a <span> carrying the SAME
 *  CSS classes the old data uses, so existing content stays compatible:
 *      couplet -> <span class="initial-couplets">…</span>
 *      refrain -> <span class="refrain">…</span>
 *
 *  This file is the single source of truth. It FORCES this toolbar onto
 *  every editor via CKEDITOR.on('instanceCreated'), overriding whatever
 *  inline `toolbar:[...]` a view may still pass — so no other options can
 *  ever appear, no matter how an individual page configures replace().
 * ===================================================================== */
(function () {
    'use strict';
    if (typeof CKEDITOR === 'undefined') return;

    CKEDITOR.config.versionCheck = false;

    // ------------------------------------------------------------------
    //  Plugin: ajabblocks — individual block-format buttons
    //  (H1..H6, P, pre, quote) rendered as text buttons like the old UI.
    // ------------------------------------------------------------------
    var BLOCK_BUTTONS = [
        { name: 'H1',    tag: 'h1',         label: 'H1' },
        { name: 'H2',    tag: 'h2',         label: 'H2' },
        { name: 'H3',    tag: 'h3',         label: 'H3' },
        { name: 'H4',    tag: 'h4',         label: 'H4' },
        { name: 'H5',    tag: 'h5',         label: 'H5' },
        { name: 'H6',    tag: 'h6',         label: 'H6' },
        { name: 'Para',  tag: 'p',          label: 'P' },
        { name: 'Pre',   tag: 'pre',        label: 'pre' },
        { name: 'Quote', tag: 'blockquote', label: '”' } // shows a quote glyph
    ];

    CKEDITOR.plugins.add('ajabblocks', {
        init: function (editor) {
            BLOCK_BUTTONS.forEach(function (b, i) {
                var style = new CKEDITOR.style({ element: b.tag });
                var cmdName = 'ajabBlock_' + b.tag;

                editor.addCommand(cmdName, {
                    exec: function (ed) {
                        if (style.checkActive(ed.elementPath(), ed)) {
                            ed.removeStyle(style);          // toggle back to default paragraph
                        } else {
                            ed.applyStyle(style);
                        }
                        ed.fire('saveSnapshot');
                    },
                    refresh: function (ed, path) {
                        this.setState(style.checkActive(path, ed) ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF);
                    }
                });

                editor.ui.add(b.name, CKEDITOR.UI_BUTTON, {
                    label: b.label,
                    title: 'Format: ' + b.label,
                    command: cmdName,
                    toolbar: 'ajabblocks,' + (i + 1)
                });
            });
        }
    });

    // ------------------------------------------------------------------
    //  Plugin: ajabmarks — custom "couplet" & "refrain" inline marks.
    //  Wrap selection in a span with the legacy CSS class.
    // ------------------------------------------------------------------
    var COUPLET_STYLE = new CKEDITOR.style({ element: 'span', attributes: { 'class': 'initial-couplets' } });
    var REFRAIN_STYLE = new CKEDITOR.style({ element: 'span', attributes: { 'class': 'refrain' } });

    CKEDITOR.plugins.add('ajabmarks', {
        init: function (editor) {
            function mark(name, style, title) {
                var cmdName = 'ajab_' + name;
                editor.addCommand(cmdName, {
                    exec: function (ed) {
                        if (style.checkActive(ed.elementPath(), ed)) {
                            ed.removeStyle(style);
                        } else {
                            ed.applyStyle(style);
                        }
                        ed.fire('saveSnapshot');
                    },
                    refresh: function (ed, path) {
                        this.setState(style.checkActive(path, ed) ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF);
                    }
                });
                editor.ui.add(name, CKEDITOR.UI_BUTTON, {
                    label: name,
                    title: title,
                    command: cmdName,
                    toolbar: 'ajabmarks,' + (name === 'couplet' ? 10 : 20)
                });
            }
            mark('couplet', COUPLET_STYLE, 'Mark as couplet');
            mark('refrain', REFRAIN_STYLE, 'Mark as refrain');
        }
    });

    // ------------------------------------------------------------------
    //  Plugin: ajabvideo — "Video" button.
    //  Prompts for a YouTube/Vimeo/MP4 URL and embeds a responsive player,
    //  replacing the old admin's textAngular insertVideo tool.
    // ------------------------------------------------------------------
    CKEDITOR.plugins.add('ajabvideo', {
        init: function (editor) {
            editor.addCommand('ajabInsertVideo', {
                exec: function (ed) {
                    var url = window.prompt('Enter video URL (YouTube, Vimeo or .mp4):', 'https://');
                    if (!url || url === 'https://') return;
                    var embed = buildVideoEmbed(url.trim());
                    if (!embed) { window.alert('Unrecognised video URL.'); return; }
                    ed.insertHtml(embed);
                    ed.fire('saveSnapshot');
                }
            });
            editor.ui.add('Video', CKEDITOR.UI_BUTTON, {
                label: 'Video',
                title: 'Insert video',
                command: 'ajabInsertVideo',
                toolbar: 'insert,90'
            });
        }
    });

    function buildVideoEmbed(url) {
        var src = null, yt, vm;
        if ((yt = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([\w-]{11})/))) {
            src = 'https://www.youtube.com/embed/' + yt[1];
        } else if ((vm = url.match(/vimeo\.com\/(?:video\/)?(\d+)/))) {
            src = 'https://player.vimeo.com/video/' + vm[1];
        }
        if (src) {
            return '<div class="embed-responsive embed-responsive-16by9" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">' +
                   '<iframe src="' + src + '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allowfullscreen></iframe></div>';
        }
        if (/\.(mp4|webm|ogg)(\?.*)?$/i.test(url)) {
            return '<video controls style="max-width:100%"><source src="' + url + '"></video>';
        }
        return null;
    }

    // ------------------------------------------------------------------
    //  The single canonical toolbar (matches the old admin 1:1).
    // ------------------------------------------------------------------
    var ADMIN_TOOLBAR = [
        { name: 'ajabblocks', items: ['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'Para', 'Pre', 'Quote'] },
        { name: 'ajabmarks',  items: ['couplet', 'refrain'] },
        '/',
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Redo', 'Undo', '-', 'RemoveFormat'] },
        { name: 'paragraph',   items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', '-', 'Indent', 'Outdent'] },
        '/',
        { name: 'document',    items: ['Source'] },
        { name: 'insert',      items: ['Image', 'Link', 'Video'] }
    ];

    // Relabel the native "Source" button to "Toggle HTML" (old-admin wording).
    CKEDITOR.on('instanceReady', function (ev) {
        try {
            var btn = ev.editor.ui.get('Source');
            if (btn) { btn.label = btn.title = 'Toggle HTML'; }
            var el = ev.editor.container && ev.editor.container.$ &&
                     ev.editor.container.$.querySelector('.cke_button__source .cke_button_label');
            if (el) { el.textContent = 'Toggle HTML'; el.style.display = 'inline'; }
            // The native Source button hides its label by default; force it.
            var src = ev.editor.container && ev.editor.container.$ &&
                      ev.editor.container.$.querySelector('.cke_button__source');
            if (src) {
                src.setAttribute('title', 'Toggle HTML');
                var icon = src.querySelector('.cke_button_icon');
                if (icon) icon.style.display = 'none';
            }
        } catch (e) { /* non-fatal */ }
    });

    // ------------------------------------------------------------------
    //  FORCE this config onto every editor instance, overriding any
    //  inline toolbar a view may still pass.
    // ------------------------------------------------------------------
    CKEDITOR.on('instanceCreated', function (ev) {
        var editor = ev.editor;
        editor.on('configLoaded', function () {
            var c = editor.config;
            c.toolbar = ADMIN_TOOLBAR;
            c.toolbarGroups = null;
            c.removeButtons = '';
            c.removePlugins = '';
            c.extraPlugins = mergePlugins(c.extraPlugins, 'ajabblocks,ajabmarks,ajabvideo,justify');
            c.allowedContent = true;         // keep couplet/refrain/pre/heading markup intact
            c.height = c.height || 200;
            c.resize_enabled = false;
            // Inject the admin marks CSS into the editing iframe so couplet/
            // refrain styling is visible while editing.
            if (window.__ADMIN_EDITOR_CSS) {
                c.contentsCss = [].concat(c.contentsCss || CKEDITOR.getUrl('contents.css'), window.__ADMIN_EDITOR_CSS);
            }
        });
    });

    function mergePlugins(existing, add) {
        var set = {};
        (String(existing || '') + ',' + add).split(',').forEach(function (p) {
            p = p.trim();
            if (p) set[p] = true;
        });
        return Object.keys(set).join(',');
    }
})();
