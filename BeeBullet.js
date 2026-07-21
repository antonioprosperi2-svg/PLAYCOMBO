export class BeeBullet {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.width = 8;
        this.height = 4;
        this.speed = 500;
        this.destroyed = false;
    }

    update(dt) {
        this.x += this.speed * dt;
        if (this.x > 2000) {
            this.destroyed = true;
        }
    }

    draw(ctx) {
        ctx.fillStyle = "yellow";
        ctx.fillRect(this.x, this.y, this.width, this.height);
    }
}
/** 🌟 * Classe BeeBullet: Rappresenta un proiettile in movimento (eredita da BeeEntity).
 * Gestisce la traiettoria, la velocità e il rendering dei colpi sparati dal giocatore 
 * (o dai nemici), controllando quando esce dallo schermo per essere eliminato.
 */
