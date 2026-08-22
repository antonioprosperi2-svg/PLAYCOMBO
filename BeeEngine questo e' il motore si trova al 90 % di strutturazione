// BEE ENGINE COMPLETO
class BeeEngine {
    constructor(canvasId, width, height) {
        this.canvas = document.getElementById(canvasId);
        this.ctx = this.canvas.getContext('2d');
        this.canvas.width = width;
        this.canvas.height = height;

        this.input = new BeeInput();
        this.entities = [];
        this.lastTime = 0;
        this.camera = null; // Aggiunto per gestire la telecamera

        this.lockOrientation();

        if ('ontouchstart' in window) {
            this.touchControls = new BeeTouchControls(this.canvas, this.input);
        }
    }

    lockOrientation() {
        if (screen.orientation && screen.orientation.lock) {
            screen.orientation.lock('landscape').catch((err) => console.warn("Orientamento:", err));
        }
    }

    start(updateCallback, renderCallback) {
        this.update = updateCallback;
        this.render = renderCallback;
        requestAnimationFrame((timestamp) => this.loop(timestamp));
    }

    loop(timestamp) {
        let deltaTime = (timestamp - this.lastTime) / 1000;
        this.lastTime = timestamp;

        this.updateEntities(deltaTime, this.input);
        this.update(deltaTime, this.input);

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Applichiamo la telecamera se esiste
        this.ctx.save();
        if (this.camera) this.camera.apply(this.ctx);

        this.renderEntities(this.ctx);
        this.render(this.ctx);

        this.ctx.restore(); // Chiude effetto telecamera

        // I controlli restano fuori (fissi)
        if (this.touchControls) this.touchControls.draw(this.ctx);
        requestAnimationFrame((timestamp) => this.loop(timestamp));
    }

    addEntity(entity) { this.entities.push(entity); }
    updateEntities(dt, input) { this.entities.forEach(e => e.update && e.update(dt, input)); }
    renderEntities(ctx) { this.entities.forEach(e => e.draw && e.draw(ctx)); }

    checkCollision(rect1, rect2) {
        return (rect1.x < rect2.x + rect2.width && rect1.x + rect1.width > rect2.x &&
            rect1.y < rect2.y + rect2.height && rect1.y + rect1.height > rect2.y);
    }

    async loadAsset(type, name, src) {
        if (type === 'image') {
            return new Promise((resolve) => {
                const img = new Image();
                img.src = src;
                img.onload = () => resolve(img);
            });
        }
    }
}

class BeeInput {
    constructor() {
        this.keys = {};
        this.pressed = {};
        this.mouse = {
            x: 0,
            y: 0,
            pressed: false,
            wasPressed: false
        };

        window.addEventListener('keydown', (e) => {
            if (!this.keys[e.key]) this.pressed[e.key] = true;
            this.keys[e.key] = true;
        });
        window.addEventListener('keyup', (e) => { this.keys[e.key] = false; });

        // Mouse events
        document.addEventListener('mousemove', (e) => {
            this.mouse.x = e.clientX;
            this.mouse.y = e.clientY;
        });
        document.addEventListener('mousedown', (e) => {
            this.mouse.pressed = true;
            this.mouse.wasPressed = true;
        });
        document.addEventListener('mouseup', (e) => {
            this.mouse.pressed = false;
        });
    }
    isPressed(key) { return !!this.keys[key]; }
    wasPressed(key) {
        let p = !!this.pressed[key];
        this.pressed[key] = false;
        return p;
    }
    setKey(key, value) {
        if (value && !this.keys[key]) this.pressed[key] = true;
        this.keys[key] = value;
    }
}

class BeeTouchControls {
    constructor(canvas, input) {
        this.canvas = canvas;
        this.input = input;
        this.btnSize = 60;
        this.margin = 20;
        this.buttons = {
            left: { key: 'ArrowLeft', x: 0, y: 0 },
            right: { key: 'ArrowRight', x: 0, y: 0 },
            up: { key: 'ArrowUp', x: 0, y: 0 },
            down: { key: 'ArrowDown', x: 0, y: 0 },
            action: { key: ' ', x: 0, y: 0 }
        };
        this.activeTouches = {};
        canvas.addEventListener('touchstart', (e) => this.handleTouch(e), { passive: false });
        canvas.addEventListener('touchmove', (e) => this.handleTouch(e), { passive: false });
        canvas.addEventListener('touchend', (e) => this.handleTouchEnd(e), { passive: false });
    }

    getButtonAt(x, y) {
        for (const name in this.buttons) {
            const b = this.buttons[name];
            const dx = x - b.x; const dy = y - b.y;
            if (Math.sqrt(dx * dx + dy * dy) < this.btnSize / 2 + 15) return name;
        }
        return null;
    }

    getCanvasCoords(touch) {
        const rect = this.canvas.getBoundingClientRect();
        return {
            x: (touch.clientX - rect.left) * (this.canvas.width / rect.width),
            y: (touch.clientY - rect.top) * (this.canvas.height / rect.height)
        };
    }

    handleTouch(e) {
        e.preventDefault();
        const stillActive = {};
        for (let touch of e.touches) {
            const { x, y } = this.getCanvasCoords(touch);
            const btnName = this.getButtonAt(x, y);
            if (btnName) stillActive[touch.identifier] = btnName;
        }
        for (let id in this.activeTouches) if (!stillActive[id]) this.input.setKey(this.buttons[this.activeTouches[id]].key, false);
        for (let id in stillActive) this.input.setKey(this.buttons[stillActive[id]].key, true);
        this.activeTouches = stillActive;
    }

    handleTouchEnd(e) {
        e.preventDefault();
        const stillActive = {};
        for (let touch of e.touches) {
            const { x, y } = this.getCanvasCoords(touch);
            const btnName = this.getButtonAt(x, y);
            if (btnName) stillActive[touch.identifier] = btnName;
        }
        for (let id in this.activeTouches) if (!stillActive[id]) this.input.setKey(this.buttons[this.activeTouches[id]].key, false);
        this.activeTouches = stillActive;
    }

    draw(ctx) {
        const w = this.canvas.width; const h = this.canvas.height;
        this.buttons.left.x = this.margin + this.btnSize / 2; this.buttons.left.y = h - this.margin - this.btnSize / 2;
        this.buttons.right.x = this.margin * 2 + this.btnSize * 1.5; this.buttons.right.y = h - this.margin - this.btnSize / 2;
        this.buttons.up.x = this.margin + this.btnSize; this.buttons.up.y = h - this.margin * 2 - this.btnSize;
        this.buttons.down.x = this.margin + this.btnSize; this.buttons.down.y = h - this.margin - this.btnSize / 2;
        this.buttons.action.x = w - this.margin - this.btnSize / 2; this.buttons.action.y = h - this.margin - this.btnSize / 2;

        ctx.save(); ctx.globalAlpha = 0.4;
        for (const name in this.buttons) {
            const b = this.buttons[name];
            const isActive = Object.values(this.activeTouches).includes(name);
            ctx.fillStyle = isActive ? '#ffffff' : '#888888';
            ctx.beginPath(); ctx.arc(b.x, b.y, this.btnSize / 2, 0, Math.PI * 2); ctx.fill();
            ctx.fillStyle = '#000000'; ctx.font = '20px sans-serif';
            ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
            const symbols = { left: '◀', right: '▶', up: '▲', down: '▼', action: '●' };
            ctx.fillText(symbols[name], b.x, b.y);
        }
        ctx.restore();
    }
}

class BeeSprite {
    constructor(image, frameWidth, frameHeight, framesPerRow, speed = 0.1) {
        this.image = image;
        this.frameWidth = frameWidth;
        this.frameHeight = frameHeight;
        this.framesPerRow = framesPerRow;
        this.speed = speed;
        this.frame = 0;
    }
    update(dt) { this.frame += this.speed; }
    draw(ctx, x, y) {
        const f = Math.floor(this.frame % this.framesPerRow);
        const row = Math.floor(this.frame / this.framesPerRow);
        ctx.drawImage(this.image, f * this.frameWidth, row * this.frameHeight,
            this.frameWidth, this.frameHeight, x, y, this.frameWidth, this.frameHeight);
    }
}

class BeeCamera {
    constructor(canvasWidth, canvasHeight) {
        this.x = 0; this.y = 0;
        this.w = canvasWidth; this.h = canvasHeight;
    }
    follow(target) {
        this.x = target.x - this.w / 2;
        this.y = target.y - this.h / 2;
    }
    apply(ctx) { ctx.translate(-this.x, -this.y); }
}

class BeeGrid {
    constructor(cols, rows, cellSize) {
        this.cols = cols;
        this.rows = rows;
        this.cellSize = cellSize;
        this.data = Array(rows).fill().map(() => Array(cols).fill(0));
    }
    setCell(c, r, val) { if (this.data[r]) this.data[r][c] = val; }
    getCell(c, r) { return this.data[r] ? this.data[r][c] : null; }
    draw(ctx, drawFunction) {
        for (let r = 0; r < this.rows; r++) {
            for (let c = 0; c < this.cols; c++) {
                drawFunction(ctx, c * this.cellSize, r * this.cellSize, this.data[r][c]);
            }
        }
    }
}
