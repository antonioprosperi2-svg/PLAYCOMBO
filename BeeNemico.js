import { BeeEntity } from './BeeEntity.js';

export class BeeNemico extends BeeEntity {
    constructor(x, y) {
        super(x, y, 32, 32);
        this.velocita = 50;
    }

    update(dt) {
        this.x += this.velocita * dt;

        // Se superano 700, tornano indietro
        if (this.x > 700) {
            this.velocita = -50;
        }
        // Se tornano troppo indietro (es. 0), ripartono a destra
        if (this.x < 0) {
            this.velocita = 50;
        }
        console.log("Nemico alla posizione:", this.x);
    } // <-- La parentesi dell'update finisce qui!

    draw(ctx) { // <-- Questo è un metodo separato
        ctx.beginPath();
        ctx.arc(this.x, this.y, 20, 0, Math.PI * 2);
        ctx.fillStyle = 'yellow';
        ctx.fill();
        ctx.strokeStyle = 'black';
        ctx.stroke();

        ctx.fillStyle = 'black';
        ctx.font = '12px Arial';
        ctx.fillText(`X:${Math.round(this.x)} Y:${Math.round(this.y)}`, this.x - 20, this.y - 25);
    }
}




/**🌟 * Classe BeeNemico: Rappresenta i personaggi ostili nel gioco.
 * Eredita da BeeEntity e aggiunge comportamenti specifici come pattern di movimento,
 * intelligenza artificiale base, gestione dei punti vita (HP) e danni al Player.
 * 
1. Il movimento a pattuglia (Avanti e indietro)Il nemico cammina in una direzione.
 Se tocca un muro o arriva alla fine di una piattaforma, si gira dall'altra parte.
 In codice: if (toccaMuro) velocità = -velocità; 
 
 2. L'inseguimento semplice (Se ti vede, ti viene incontro)
 Il nemico confronta la sua posizione con quella del Player. 
 Se il Player è più a destra, il nemico si muove a destra.
 In codice: if (player.x > nemico.x) nemico.x += 1;

 3. Lo spawn o l'attacco a tempoIl nemico usa un timer
  (magari proprio un BeeTimer) per decidere quando
   sparare un proiettile o cambiare direzione ogni tot secondi
   In pratica, si tratta di piccoli blocchi di codice if/else che danno al giocatore 
   l'illusione che il nemico stia "pensando" o reagendo a quello che succede sullo schermo.*/