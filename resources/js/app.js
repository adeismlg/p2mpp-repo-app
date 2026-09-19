

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', async () => {
    if (! document.querySelector('textarea[data-rich-editor]')) {
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

    tinymce.init({
        selector: 'textarea[data-rich-editor]',
        height: 520,
        menubar: false,
        branding: false,
        plugins: 'code link lists table',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link table | code',
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.7; }',
        skin: false,
        content_css: false,
        license_key: 'gpl',
        promotion: false,
    });
});
