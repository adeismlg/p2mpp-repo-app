

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', async () => {
    document.querySelectorAll('form[data-upload-progress-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const hasFiles = [...form.querySelectorAll('input[type="file"]')]
                .some((input) => input.files?.length);

            if (! hasFiles || form.dataset.uploading === 'true') {
                if (form.dataset.uploading === 'true') {
                    event.preventDefault();
                }

                return;
            }

            event.preventDefault();

            const progressPanel = form.querySelector('[data-upload-progress]');
            const progressBar = form.querySelector('[data-upload-progress-bar]');
            const status = form.querySelector('[data-upload-status]');
            const percentage = form.querySelector('[data-upload-percentage]');
            const submitButtons = [...form.querySelectorAll('button[type="submit"], input[type="submit"]')];

            if (! progressPanel || ! progressBar || ! status || ! percentage) {
                form.submit();

                return;
            }

            form.dataset.uploading = 'true';
            progressPanel.hidden = false;
            progressBar.value = 0;
            progressBar.setAttribute('aria-valuenow', '0');
            status.textContent = 'Mengunggah file...';
            percentage.textContent = '0%';
            submitButtons.forEach((button) => {
                button.disabled = true;
            });

            const xhr = new XMLHttpRequest();
            xhr.open(form.method.toUpperCase(), form.action);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.addEventListener('progress', (progressEvent) => {
                if (! progressEvent.lengthComputable) {
                    status.textContent = 'Mengunggah file...';

                    return;
                }

                const progress = Math.min(100, Math.round((progressEvent.loaded / progressEvent.total) * 100));
                progressBar.value = progress;
                progressBar.setAttribute('aria-valuenow', String(progress));
                percentage.textContent = `${progress}%`;
                status.textContent = progress === 100
                    ? 'Unggahan diterima, sedang diproses...'
                    : 'Mengunggah file...';
            });

            xhr.upload.addEventListener('load', () => {
                progressBar.value = 100;
                progressBar.setAttribute('aria-valuenow', '100');
                percentage.textContent = '100%';
                status.textContent = 'Unggahan selesai, sedang disimpan...';
            });

            const restoreForm = (message) => {
                status.textContent = message;
                percentage.textContent = '';
                form.dataset.uploading = 'false';
                submitButtons.forEach((button) => {
                    button.disabled = false;
                });
            };

            xhr.addEventListener('load', () => {
                let response;

                try {
                    response = JSON.parse(xhr.responseText);
                } catch {
                    restoreForm(`Server memberikan respons tidak valid (HTTP ${xhr.status}).`);

                    return;
                }

                if (xhr.status >= 200 && xhr.status < 300 && typeof response.redirect === 'string') {
                    window.location.assign(response.redirect);

                    return;
                }

                const validationMessage = Object.values(response.errors || {})
                    .flat()
                    .find((message) => typeof message === 'string');
                const message = xhr.status === 413
                    ? 'Unggahan ditolak server. Pastikan ukuran file tidak lebih dari 25 MB.'
                    : response.message || validationMessage || `Unggah gagal (HTTP ${xhr.status}).`;

                restoreForm(message);
            });

            xhr.addEventListener('error', () => {
                restoreForm('Unggah gagal karena gangguan koneksi. Silakan coba lagi.');
            });

            xhr.addEventListener('abort', () => {
                restoreForm('Unggahan dibatalkan. Silakan coba lagi.');
            });

            queueMicrotask(() => xhr.send(new FormData(form)));
        });
    });

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
        import('tinymce/plugins/image'),
        import('tinymce/plugins/link'),
        import('tinymce/plugins/lists'),
        import('tinymce/plugins/table'),
        import('tinymce/skins/ui/oxide/skin.css'),
    ]);

    await Promise.all(textareas.map((textarea) => {
        const supportsContentBlocks = textarea.hasAttribute('data-rich-editor-blocks');
        const imageUploadUrl = textarea.dataset.richEditorUploadUrl;

        return tinymce.init({
            target: textarea,
            height: 520,
            menubar: false,
            branding: false,
            plugins: `code link lists table${imageUploadUrl ? ' image' : ''}`,
            toolbar: `undo redo | blocks | bold italic underline | bullist numlist | link table${imageUploadUrl ? ' image' : ''}${supportsContentBlocks ? ' | contentblocks' : ''} | code`,
            ...(imageUploadUrl ? {
                automatic_uploads: true,
                image_caption: true,
                images_file_types: 'jpg,jpeg,png,webp',
                images_upload_handler(blobInfo, progress) {
                    return new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        const formData = new FormData();

                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        xhr.open('POST', imageUploadUrl);
                        xhr.setRequestHeader('X-CSRF-TOKEN', textarea.dataset.richEditorCsrf);

                        xhr.upload.addEventListener('progress', (event) => {
                            if (event.lengthComputable) {
                                progress((event.loaded / event.total) * 100);
                            }
                        });

                        xhr.addEventListener('load', () => {
                            let response;

                            try {
                                response = JSON.parse(xhr.responseText);
                            } catch {
                                reject(`Server mengembalikan respons yang tidak valid (HTTP ${xhr.status}).`);

                                return;
                            }

                            if (xhr.status < 200 || xhr.status >= 300 || ! response.location) {
                                reject(response.message || response.errors?.file?.[0] || `Gagal mengunggah gambar (HTTP ${xhr.status}).`);

                                return;
                            }

                            resolve(response.location);
                        });

                        xhr.addEventListener('error', () => reject('Gagal mengunggah gambar karena gangguan jaringan.'));
                        xhr.addEventListener('abort', () => reject('Unggahan gambar dibatalkan.'));
                        xhr.send(formData);
                    });
                },
            } : {}),
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
