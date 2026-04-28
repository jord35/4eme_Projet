async function poll({
    url,
    onSuccess = () => { },
    onError = (error) => console.error(error),
    loadingGuard = null
}) {
    if (loadingGuard?.isLoading) {
        return;
    }

    if (loadingGuard) {
        loadingGuard.isLoading = true;
    }

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        onSuccess(data);
    } catch (error) {
        onError(error);
    } finally {
        if (loadingGuard) {
            loadingGuard.isLoading = false;
        }
    }
}

function createPoller({
    urlFn,
    onSuccess,
    onError = (error) => console.error(error)
}) {
    const loadingGuard = { isLoading: false };
    let intervalId = null;
    let currentInterval = 3000;

    function runOnce() {
        return poll({
            url: urlFn(),
            onSuccess,
            onError,
            loadingGuard
        });
    }

    function start(interval = currentInterval) {
        stop();
        currentInterval = interval;
        runOnce();
        intervalId = setInterval(runOnce, currentInterval);
    }

    function stop() {
        if (intervalId !== null) {
            clearInterval(intervalId);
            intervalId = null;
        }
    }

    function restart() {
        start(currentInterval);
    }

    function setSpeed(interval) {
        start(interval);
    }

    return {
        start,
        stop,
        restart,
        setSpeed,
        runOnce
    };
}

function applyResponsiveImageData(
    image,
    picture,
    {
        fallbackSrc = '',
        fallbackAlt = '',
        defaultWidth = 0,
        defaultHeight = 0
    } = {}
) {
    if (!image) {
        return;
    }

    if (!picture || !picture.src) {
        if (fallbackSrc !== '') {
            image.src = fallbackSrc;
        }

        image.removeAttribute('srcset');
        image.removeAttribute('sizes');

        if (fallbackAlt !== '') {
            image.alt = fallbackAlt;
        }

        if (defaultWidth > 0) {
            image.width = Number(defaultWidth);
        }

        if (defaultHeight > 0) {
            image.height = Number(defaultHeight);
        }

        image.style.display = 'block';
        return;
    }

    image.src = picture.src || fallbackSrc || '';

    if (picture.srcset) {
        image.setAttribute('srcset', picture.srcset);
    } else {
        image.removeAttribute('srcset');
    }

    if (picture.sizes) {
        image.setAttribute('sizes', picture.sizes);
    } else {
        image.removeAttribute('sizes');
    }

    image.alt = picture.alt || fallbackAlt || image.alt || '';

    if (picture.width || defaultWidth > 0) {
        image.width = Number(picture.width || defaultWidth);
    }

    if (picture.height || defaultHeight > 0) {
        image.height = Number(picture.height || defaultHeight);
    }

    image.style.display = 'block';
}

function bindImagePreview({
    input,
    preview,
    onBeforeChange = () => { },
    localAlt = "Aperçu local de l'image sélectionnée"
}) {
    if (!input || !preview) {
        return {
            release() { }
        };
    }

    let previewObjectUrl = null;

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        onBeforeChange();

        if (!file) {
            return;
        }

        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
        }

        previewObjectUrl = URL.createObjectURL(file);
        preview.src = previewObjectUrl;
        preview.removeAttribute('srcset');
        preview.removeAttribute('sizes');
        preview.alt = localAlt;
        preview.style.display = 'block';
    });

    return {
        release() {
            if (!previewObjectUrl) {
                return;
            }

            URL.revokeObjectURL(previewObjectUrl);
            previewObjectUrl = null;
        }
    };
}

function initFormAjax(
    formId,
    onSuccess,
    onError = (error) => console.error(error)) {
    const form = document.getElementById(formId);

    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        try {
            const response = await fetch(form.action || window.location.href, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const contentType = response.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                throw new Error('Unexpected non-JSON response');
            }

            const data = await response.json();
            onSuccess(data, form, response);
        } catch (error) {
            onError(error, form);
        }
    });
}

