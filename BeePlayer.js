import { BeeEntity } from './BeeEntity.js'; // Assicurati che il percorso sia giusto!
export class BeePlayer extends BeeEntity {
    constructor(x, y) {
        super(x, y, 32, 32);
        this.speed = 200;
    }

    update(dt, input) {
        if (input.isPressed("ArrowRight")) this.x += this.speed * dt;
        if (input.isPressed("ArrowLeft")) this.x -= this.speed * dt;
        if (input.isPressed("ArrowDown")) this.y += this.speed * dt;
        if (input.isPressed("ArrowUp")) this.y -= this.speed * dt;
    }

    draw(ctx) {
        ctx.fillStyle = "orange";
        ctx.fillRect(this.x, this.y, this.width, this.height);
    }
}
/** 🌟  Classe BeePlayer: Rappresenta il personaggio controllato dal giocatore (eredita da BeeEntity).
 * Gestisce i movimenti guidati dall'input, le animazioni di volo/corsa, la barra delle vite,
 * il punteggio accumulato e le reazioni quando viene colpito dai nemici.
 */
