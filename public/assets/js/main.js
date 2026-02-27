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
    const cropX = document.getElementById('cropX');
    const cropY = document.getElementById('cropY');
    const zoomHidden = document.getElementById('avatarZoom');
    const xHidden = document.getElementById('avatarX');
    const yHidden = document.getElementById('avatarY');
    const cHidden = document.getElementById('avatarCanvas');

    let image = null;

    const draw = () => {
        if (!image) {
            return;
        }

        const size = canvas.width;
        const zoomValue = Number(zoom.value);
        const xValue = Number(cropX.value);
        const yValue = Number(cropY.value);

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

        zoomHidden.value = String(zoomValue);
        xHidden.value = String(xValue);
        yHidden.value = String(yValue);
        cHidden.value = String(size);
    };

    [zoom, cropX, cropY].forEach((el) => el.addEventListener('input', draw));

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            const img = new Image();
            img.onload = () => {
                image = img;
                editor.style.display = 'block';
                zoom.value = '1';
                cropX.value = '0';
                cropY.value = '0';
                draw();
            };
            img.src = String(reader.result);
        };

        reader.readAsDataURL(file);
    });
})();
