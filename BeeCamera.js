export class BeeCamera {
    constructor(canvasWidth, canvasHeight) {
        this.x = 0;
        this.y = 0;
        this.w = canvasWidth;
        this.h = canvasHeight;

        this.bounds = null;
    }

    setBounds(x, y, width, height) {
        this.bounds = { x, y, width, height };
    }

    follow(target, smooth = 0.1) {
        let targetX = target.x + target.width / 2 - this.w / 2;
        let targetY = target.y + target.height / 2 - this.h / 2;

        this.x += (targetX - this.x) * smooth;
        this.y += (targetY - this.y) * smooth;

        if (this.bounds) {
            this.x = Math.max(this.bounds.x, Math.min(this.x, this.bounds.x + this.bounds.width - this.w));
            this.y = Math.max(this.bounds.y, Math.min(this.y, this.bounds.y + this.bounds.height - this.h));
        }
    }

    apply(ctx) {
        ctx.translate(-Math.round(this.x), -Math.round(this.y));
    }
}
/** 🌟 * Classe BeeCamera: Gestisce l'inquadratura visiva e lo scorrimento del gioco.
 * Segue fluidamente un oggetto bersaglio (di solito il Player) per mostrare 
 * la porzione corretta del mondo di gioco quando la mappa è più grande dello schermo.
 */
