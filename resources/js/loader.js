let loaderTimerInterval = null;

export function showLoader(estimatedSeconds = 10) {
    const loader = document.getElementById('global-loader');
    const timerDisplay = document.getElementById('loader-timer');

    if (loader) loader.style.display = 'block';

    let secondsPassed = 0;
    if (timerDisplay) {
        timerDisplay.innerText = `Estimated wait: ~${estimatedSeconds} seconds`;

        loaderTimerInterval = setInterval(() => {
            secondsPassed++;
            timerDisplay.innerText = `Estimated wait: ~${estimatedSeconds} seconds (elapsed: ${secondsPassed}s)`;
        }, 1000);
    }
}

export function hideLoader() {
    const loader = document.getElementById('global-loader');
    const timerDisplay = document.getElementById('loader-timer');

    if (loader) loader.style.display = 'none';
    if (timerDisplay) timerDisplay.innerText = '';

    if (loaderTimerInterval) {
        clearInterval(loaderTimerInterval);
        loaderTimerInterval = null;
    }
}
