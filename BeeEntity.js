export class BeeEntity {
    // Vecchia versione: constructor({ x = 0, y = 0... } = {}) 
    // CAMBIALO IN QUESTO MODO per mantenere la compatibilità:

    constructor(x = 0, y = 0, width = 32, height = 32) {
        this.x = x;
        this.y = y;
        this.width = width;
        this.height = height;

        // Mantieni le tue nuove funzionalità
        this.vx = 0;
        this.vy = 0;
        this.active = true;
        this.visible = true;
        this.collider = null;
        this.children = [];
    }

    addRectCollider(offsetX = 0, offsetY = 0, width = null, height = null) {
        this.collider = new BeeRectCollider(
            this,
            offsetX,
            offsetY,
            width ?? this.width,
            height ?? this.height
        );

        return this.collider;
    }

    addChild(entity) {
        this.children.push(entity);
        return entity;
    }

    removeChild(entity) {
        this.children = this.children.filter(child => child !== entity);
    }

    update(dt, scene) {
        this.x += this.vx * dt;
        this.y += this.vy * dt;

        for (const child of this.children) {
            if (child.active) {
                child.update(dt, scene);
            }
        }
    }

    draw(ctx) {
        for (const child of this.children) {
            if (child.visible) {
                child.draw(ctx);
            }
        }
    }

    destroy() {
        this.active = false;
        this.visible = false;
    }
}
/**🌟 Il ruolo di BeeEntity
 * Ereditarietà: Funge da modello astratto. 
 * Le altre classi(come il giocatore, i nemici o i proiettili) "ereditano" 
 * le sue proprietà.Evitare ridondanza: Senza questa classe, 
 * dovresti riscrivere le variabili x, y, width e height in ogni singolo elemento del gioco.
 * Polimorfismo: Permette al motore di gioco di gestire una lista unica di oggetti diversi e 
 * aggiornarli tutti insieme con un ciclo, chiamando il metodo per disegnarsi (es. draw())
 *  senza sapere esattamente cosa siano. 
 *  la classe madre di tutto il motore. 
 * Definisce le regole base che valgono per qualsiasi cosa appaia sullo schermo 
 * (posizione $x$ e $y$, larghezza, altezza e il comando per disegnarsi).
 * Come si usa: Non la usi quasi mai da sola nel main.js, 
 * ma serve come "fondamenta" per costruire le altre classi.*/