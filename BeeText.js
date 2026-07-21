import { BeeEntity } from './BeeEntity.js';
export class BeeText extends BeeEntity {
    constructor({
        x = 0,
        y = 0,
        text = "",
        font = "24px Arial",
        color = "white",
        align = "left",
        baseline = "top"
    } = {}) {
        super({ x, y, width: 0, height: 0 });

        this.text = text;
        this.font = font;
        this.color = color;
        this.align = align;
        this.baseline = baseline;
    }

    draw(ctx) {
        if (!this.visible) return;

        ctx.save();
        ctx.font = this.font;
        ctx.fillStyle = this.color;
        ctx.textAlign = this.align;
        ctx.textBaseline = this.baseline;
        ctx.fillText(this.text, this.x, this.y);
        ctx.restore();

        super.draw(ctx);
    }
}
/** 🌟 * Classe BeeText: Gestisce tutti gli elementi di testo a schermo (UI).
 * Permette di configurare font, colori e posizioni per mostrare 
 * informazioni dinamiche come il punteggio, le vite, i timer o i messaggi di game over.
 */
