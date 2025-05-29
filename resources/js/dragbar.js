function initDragbar() {
    const dragbar = document.getElementById('dragbar');
    const summaryPanelWrapper = document.getElementById('summary-panel-wrapper');
    const container = document.getElementById('challenge-container');

    if (!dragbar || !summaryPanelWrapper || !container) return;

    let isDragging = false;

    dragbar.addEventListener('mousedown', (e) => {
        isDragging = true;
        document.body.style.cursor = 'ew-resize';
        e.preventDefault();
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;

        const containerRect = container.getBoundingClientRect();
        const containerRight = containerRect.right;
        const newWidth = containerRight - e.clientX;

        // Limit width: min 250px, max 70% of container
        const maxAllowed = containerRect.width * 0.7;
        const clampedWidth = Math.max(250, Math.min(newWidth, maxAllowed));

        summaryPanelWrapper.style.width = `${clampedWidth}px`;
    });

    document.addEventListener('mouseup', () => {
        if (isDragging) {
            isDragging = false;
            document.body.style.cursor = 'default';
        }
    });
}

// ✅ Expose globally for reuse
window.initDragbar = initDragbar;

// ✅ Initialize on load
document.addEventListener('DOMContentLoaded', initDragbar);
