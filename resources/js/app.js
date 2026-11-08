

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', async () => {
    const textareas = [...document.querySelectorAll('textarea[data-rich-editor]')];

    if (textareas.length === 0) {
        return;
    }

    const { default: tinymce } = await import('tinymce');

    await Promise.all([
        import('tinymce/icons/default'),
        import('tinymce/models/dom'),
        import('tinymce/themes/silver'),
        import('tinymce/plugins/code'),
        import('tinymce/plugins/link'),
        import('tinymce/plugins/lists'),
        import('tinymce/plugins/table'),
        import('tinymce/skins/ui/oxide/skin.css'),
    ]);

    await Promise.all(textareas.map((textarea) => {
        const supportsContentBlocks = textarea.hasAttribute('data-rich-editor-blocks');

        return tinymce.init({
            target: textarea,
            height: 520,
            menubar: false,
            branding: false,
            plugins: 'code link lists table',
            toolbar: `undo redo | blocks | bold italic underline | bullist numlist | link table${supportsContentBlocks ? ' | contentblocks' : ''} | code`,
            body_class: supportsContentBlocks ? 'page-content' : '',
            content_css: textarea.dataset.richEditorCss || false,
            content_style: `body { font-family: ${supportsContentBlocks ? 'Poppins' : 'Inter'}, sans-serif; font-size: 14px; line-height: 1.7; }`,
            skin: false,
            license_key: 'gpl',
            promotion: false,
            setup(editor) {
                if (! supportsContentBlocks) {
                    return;
                }

                editor.ui.registry.addMenuButton('contentblocks', {
                    text: 'Blok',
                    tooltip: 'Sisipkan blok konten siap pakai',
                    fetch(callback) {
                        callback([
                            {
                                type: 'menuitem',
                                text: 'Panel biru',
                                onAction() {
                                    editor.insertContent('<div class="page-content-panel page-content-panel--blue"><h3>Judul panel</h3><p>Tulis isi panel di sini.</p></div><p></p>');
                                },
                            },
                            {
                                type: 'menuitem',
                                text: 'Panel hangat',
                                onAction() {
                                    editor.insertContent('<div class="page-content-panel page-content-panel--amber"><h3>Judul panel</h3><p>Tulis isi panel di sini.</p></div><p></p>');
                                },
                            },
                            {
                                type: 'menuitem',
                                text: 'Kutipan',
                                onAction() {
                                    editor.insertContent('<blockquote class="page-content-quote"><p>Tulis kutipan di sini.</p><cite>Nama sumber</cite></blockquote><p></p>');
                                },
                            },
                            {
                                type: 'menuitem',
                                text: 'Dua kolom',
                                onAction() {
                                    editor.insertContent('<div class="page-content-columns"><div class="page-content-column"><h3>Kolom 1</h3><p>Tulis isi kolom di sini.</p></div><div class="page-content-column"><h3>Kolom 2</h3><p>Tulis isi kolom di sini.</p></div></div><p></p>');
                                },
                            },
                        ]);
                    },
                });
            },
        });
    }));
});
