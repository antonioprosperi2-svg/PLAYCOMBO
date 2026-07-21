import { BeeEngine } from './BeeEngine.js';
import { BeeMenuScene } from './src/BeeMenuScene.js'; // Nome del file tutto attaccato!
import { BeeNemico } from './src/BeeNemico.js';    // Il tuo nemico
import { BeePlayer } from './src/BeePlayer.js'; // Il tuo player
const gioco = new BeeEngine('testCanvas', 800, 600);

// Crei i personaggi
const giocatore = new BeePlayer(100, 100);
const mioNemico = new BeeNemico(200, 200);
const menu = new BeeMenuScene(); // Usiamo lo stampino pulito
gioco.setScene(menu);
// Li aggiungi al motore
gioco.addEntity(giocatore);
gioco.addEntity(mioNemico);
// Aggiungiamo 3 nemici in posizioni diverse
// ... tieni tutto quello che hai già scritto sopra ...

// Aggiungi queste righe in fondo, prima di engine.start()
const nemico2 = new BeeNemico(400, 100);
const nemico3 = new BeeNemico(600, 300);

gioco.addEntity(nemico2);
gioco.addEntity(nemico3);

// ... poi tieni il tuo engine.start() che avevi già ...
gioco.start();
// Fai partire tutto
/**
 * FILE MAIN.JS (Il Motore del Gioco)
 * -----------------------------------------------------------------
 * È il punto di ingresso e il "regista" di tutta l'applicazione.
 * - Inizializza il Canvas e il contesto grafico.
 * - Importa e coordina tutte le classi del gioco (Player, Nemici, Controlli).
 * - Gestisce il Game Loop principale (Update e Draw) che gira a 60 FPS.
 */



