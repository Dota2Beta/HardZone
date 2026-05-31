document.querySelectorAll('.needs-validation').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });
});

(() => {
    const input = document.getElementById('avatarInput');
    const editor = document.getElementById('avatarEditor');
    const canvas = document.getElementById('avatarPreviewCanvas');

    if (!input || !editor || !canvas) {
        return;
    }

    const ctx = canvas.getContext('2d');
    const zoom = document.getElementById('cropZoom');
    const resetBtn = document.getElementById('cropResetBtn');
    const zoomHidden = document.getElementById('avatarZoom');
    const xHidden = document.getElementById('avatarX');
    const yHidden = document.getElementById('avatarY');
    const cHidden = document.getElementById('avatarCanvas');
    const croppedHidden = document.getElementById('avatarCropped');

    let image = null;
    let xValue = 0;
    let yValue = 0;
    let dragging = false;
    let lastX = 0;
    let lastY = 0;

    const clamp = (value, min, max) => Math.max(min, Math.min(max, value));

    const getLocalPoint = (clientX, clientY) => {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        return {
            x: (clientX - rect.left) * scaleX,
            y: (clientY - rect.top) * scaleY,
        };
    };

    const draw = () => {
        if (!image) {
            return;
        }

        const size = canvas.width;
        const zoomValue = Number(zoom.value);

        const baseScale = Math.max(size / image.width, size / image.height);
        const displayScale = baseScale * zoomValue;
        const drawW = image.width * displayScale;
        const drawH = image.height * displayScale;

        const maxOffsetX = Math.max(0, (drawW - size) / 2);
        const maxOffsetY = Math.max(0, (drawH - size) / 2);

        const offsetX = maxOffsetX * (xValue / 100);
        const offsetY = maxOffsetY * (yValue / 100);

        const drawX = ((size - drawW) / 2) + offsetX;
        const drawY = ((size - drawH) / 2) + offsetY;

        ctx.clearRect(0, 0, size, size);
        ctx.drawImage(image, drawX, drawY, drawW, drawH);

        ctx.strokeStyle = 'rgba(255,255,255,0.85)';
        ctx.lineWidth = 2;
        ctx.strokeRect(1, 1, size - 2, size - 2);

        zoomHidden.value = String(zoomValue);
        xHidden.value = String(Math.round(xValue));
        yHidden.value = String(Math.round(yValue));
        cHidden.value = String(size);

        if (croppedHidden) {
            croppedHidden.value = canvas.toDataURL('image/jpeg', 0.92);
        }
    };

    const startDrag = (x, y) => {
        dragging = true;
        lastX = x;
        lastY = y;
        canvas.classList.add('dragging');
    };

    const moveDrag = (x, y) => {
        if (!dragging || !image) {
            return;
        }

        const dx = x - lastX;
        const dy = y - lastY;
        lastX = x;
        lastY = y;

        const size = canvas.width;
        const zoomValue = Number(zoom.value);
        const baseScale = Math.max(size / image.width, size / image.height);
        const displayScale = baseScale * zoomValue;
        const drawW = image.width * displayScale;
        const drawH = image.height * displayScale;

        const maxOffsetX = Math.max(1, (drawW - size) / 2);
        const maxOffsetY = Math.max(1, (drawH - size) / 2);

        xValue = clamp(xValue + (dx / maxOffsetX) * 100, -100, 100);
        yValue = clamp(yValue + (dy / maxOffsetY) * 100, -100, 100);

        draw();
    };

    const endDrag = () => {
        dragging = false;
        canvas.classList.remove('dragging');
    };

    zoom.addEventListener('input', draw);

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            zoom.value = '1';
            xValue = 0;
            yValue = 0;
            draw();
        });
    }

    canvas.addEventListener('mousedown', (e) => {
        const p = getLocalPoint(e.clientX, e.clientY);
        startDrag(p.x, p.y);
    });

    window.addEventListener('mousemove', (e) => {
        if (!dragging) return;
        const p = getLocalPoint(e.clientX, e.clientY);
        moveDrag(p.x, p.y);
    });

    window.addEventListener('mouseup', endDrag);
    canvas.addEventListener('mouseleave', endDrag);

    canvas.addEventListener('touchstart', (e) => {
        const t = e.touches[0];
        if (!t) return;
        const p = getLocalPoint(t.clientX, t.clientY);
        startDrag(p.x, p.y);
    }, { passive: true });

    canvas.addEventListener('touchmove', (e) => {
        const t = e.touches[0];
        if (!t || !dragging) return;
        const p = getLocalPoint(t.clientX, t.clientY);
        moveDrag(p.x, p.y);
    }, { passive: true });

    canvas.addEventListener('touchend', endDrag, { passive: true });



    const profileForm = input.closest('form');
    if (profileForm) {
        profileForm.addEventListener('submit', () => {
            if (image && croppedHidden) {
                draw();
            }
        });
    }

    input.addEventListener('change', () => {
        const file = input.files?.[0];
        if (!file) {
            if (croppedHidden) croppedHidden.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            const img = new Image();
            img.onload = () => {
                image = img;
                editor.style.display = 'block';
                zoom.value = '1';
                xValue = 0;
                yValue = 0;
                draw();
            };
            img.src = String(reader.result);
        };
        reader.readAsDataURL(file);
    });
})();
