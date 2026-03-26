
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
                'X-Requested-With': 'XMLHttpRequest'
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

function initFormAjax({
    formId,
    onSuccess = () => { },
    onError = (error) => console.error(error)
}) {
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
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            onSuccess({ data, form, response });
        } catch (error) {
            onError(error);
        }
    });
}
