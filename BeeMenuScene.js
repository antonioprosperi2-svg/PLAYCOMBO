export class BeeMenuScene {
    constructor() {
        this.engine = null; // Verrà riempito automaticamente da setScene
    }

    // Viene chiamato dal motore quando la scena inizia
    enter() {
        console.log("🐝 BeeEngine: Entrato nel menu di gioco con successo!");
    }

    // Viene chiamato dal motore quando la scena finisce
    exit() {
        console.log("🐝 BeeEngine: Uscito dal menu di gioco.");
    }

    // Gestisce la logica dei tasti e del touch
    update(dt, input) {
        // Se premi INVIO, BARRA SPAZIATRICE o tocchi lo schermo, il motore rileva il comando
        if (input.wasPressed("Enter") || input.isPressed(" ") || input.mouse.wasPressed) {
            console.log("💥 CAPITANO! Il touch o il tasto funzionano! Il motore è pronto al 100%!");
        }
    }

    // Disegna la schermata sul tuo Canvas
    draw(ctx) {
        // 1. Puliamo lo schermo con un bel fondo nero profondo
        ctx.fillStyle = "#000000";
        ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);

        // 2. Disegniamo una griglia sfumata di sfondo (molto stile arcade/retrò)
        ctx.strokeStyle = "rgba(255, 215, 0, 0.1)"; // Giallo ape quasi invisibile
        ctx.lineWidth = 1;
        const gridSize = 40;
        for (let x = 0; x < ctx.canvas.width; x += gridSize) {
            ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, ctx.canvas.height); ctx.stroke();
        }
        for (let y = 0; y < ctx.canvas.height; y += gridSize) {
            ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(ctx.canvas.width, y); ctx.stroke();
        }

        // 3. Disegniamo il titolo principale del gioco "BEE GAME"
        ctx.fillStyle = "#FFD700"; // Giallo Oro lucido
        ctx.font = "bold 50px sans-serif";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";

        // Un piccolo trucco per fare l'ombra nera dietro la scritta gialla
        ctx.shadowColor = "rgba(0, 0, 0, 0.5)";
        ctx.shadowBlur = 4;
        ctx.shadowOffsetX = 3;
        ctx.shadowOffsetY = 3;

        ctx.fillText("BEE GAME", ctx.canvas.width / 2, ctx.canvas.height / 3);

        // Resettiamo l'ombra per non rovinare i testi piccoli
        ctx.shadowColor = "transparent";

        // 4. Disegniamo le istruzioni per il giocatore
        ctx.fillStyle = "#FFFFFF"; // Bianco pulito
        ctx.font = "20px sans-serif";
        ctx.fillText("Premi ENTER o TOCCA lo schermo per iniziare", ctx.canvas.width / 2, ctx.canvas.height / 1.7);

        // 5. Un piccolo marchio di fabbrica in basso a destra per bellezza
        ctx.fillStyle = "#555555";
        ctx.font = "12px monospace";
        ctx.textAlign = "right";
        ctx.fillText("Powered by BeeEngine v1.0", ctx.canvas.width - 20, ctx.canvas.height - 20);
    }
}
/** 🌟 * Classe BeeMenuScene: Gestisce la schermata del menu principale.
 * Si occupa di mostrare la grafica iniziale, i pulsanti di avvio e le opzioni,
 * intercettando l'input dell'utente per far partire il gioco o cambiare scena.
 */
