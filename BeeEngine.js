import { BeeSceneManager } from './src/BeeSceneManager.js';
import { BeeSave } from './src/BeeSave.js';
import { BeeParticleSystem } from './src/BeeParticleSystem.js';
import { BeeTilemap } from './src/BeeTilemap.js';
import { BeeButton } from './src/BeeButton.js';
import { BeeText } from './src/BeeText.js';
import { BeeTimer } from './src/BeeTimer.js';
import { BeeRectCollider } from './src/BeeRectCollider.js';
import { BeeAssetManager } from './src/BeeAssetManager.js';
import { BeeMenuScene } from './src/BeeMenuScene.js';
import { BeeBullet } from './src/BeeBullet.js';
import { BeePlayer } from './src/BeePlayer.js';
import { BeeEntity } from './src/BeeEntity.js';
import { BeeGrid } from './src/BeeGrid.js';
import { BeeCamera } from './src/BeeCamera.js';
import { BeeSprite } from './src/BeeSprite.js';
import { BeeTouchControls } from './src/BeeTouchControls.js';
import { BeeInput } from './src/BeeInput.js';

export class BeeEngine {
    constructor(canvasId, width, height) {
        this.canvas = document.getElementById(canvasId);
        this.ctx = this.canvas.getContext('2d');
        this.canvas.width = width;
        this.canvas.height = height;


        this.assets = new BeeAssetManager();
        this.input = new BeeInput(this.canvas);
        this.entities = [];
        this.lastTime = 0;
        this.camera = null;
        this.grid = null;         // Metto a posto la Griglia
        this.currentScene = null; // Metto a posto la Scena Corrente
        this.scenes = new Map();  // Metto a posto il magazzino Scene
        this.events = {};



        this.lockOrientation();

        if ('ontouchstart' in window) {
            this.touchControls = new BeeTouchControls(this.canvas, this.input);
        }
    }

    // Gestione del cambio scena
    setScene(scene) {
        if (this.currentScene && this.currentScene.exit) {
            this.currentScene.exit();
        }
        this.currentScene = scene;
        this.currentScene.engine = this;
        if (this.currentScene && this.currentScene.enter) {
            this.currentScene.enter();
        }
    }

    lockOrientation() {
        if (screen.orientation && screen.orientation.lock) {
            screen.orientation.lock('landscape').catch((err) => console.warn("Orientamento:", err));
        }
    }

    // ... qui sotto continuano i tuoi metodi start(updateCallback, renderCallback) e loop(timestamp) ...


    start(updateCallback, renderCallback) {
        this.update = updateCallback;
        this.render = renderCallback;
        requestAnimationFrame((timestamp) => this.loop(timestamp));
    }

    loop(timestamp) {
        if (!this.lastTime) this.lastTime = timestamp;

        let deltaTime = (timestamp - this.lastTime) / 1000;
        this.lastTime = timestamp;

        // Evita salti enormi se la scheda si blocca
        deltaTime = Math.min(deltaTime, 0.05);

        this.updateEntities(deltaTime, this.input);

        if (this.update) this.update(deltaTime, this.input);

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        this.ctx.save();

        if (this.camera) this.camera.apply(this.ctx);

        this.renderEntities(this.ctx);

        if (this.render) this.render(this.ctx);

        this.ctx.restore();

        if (this.touchControls) this.touchControls.draw(this.ctx);

        // Importante: reset input di un singolo frame
        this.input.endFrame();

        requestAnimationFrame((timestamp) => this.loop(timestamp));
    }
    // Permette a un'entità (es. il punteggio o i suoni) di mettersi in ascolto di un evento
    on(evento, callback) {
        if (!this.events[evento]) this.events[evento] = [];
        this.events[evento].push(callback);
    }

    // Permette a una tessera di gridare al mondo che è esplosa!
    emit(evento, dati) {
        if (this.events[evento]) {
            this.events[evento].forEach(callback => callback(dati));
        }
    }


    addEntity(entity) { this.entities.push(entity); }
    updateEntities(dt, input) {
        this.entities.forEach(e => {
            if (e.update) e.update(dt, input);

        });

        this.entities = this.entities.filter(e => !e.destroyed);
    }
    renderEntities(ctx) { this.entities.forEach(e => e.draw && e.draw(ctx)); }

    checkCollision(rect1, rect2) {
        return (rect1.x < rect2.x + rect2.width && rect1.x + rect1.width > rect2.x &&
            rect1.y < rect2.y + rect2.height && rect1.y + rect1.height > rect2.y);
    }

    async loadAsset(type, name, src) {
        if (type === 'image') {
            return new Promise((resolve, reject) => {
                const img = new Image();

                img.onload = () => {
                    this.assets[name] = img;
                    resolve(img);
                };

                img.onerror = () => {
                    reject("Errore caricamento immagine: " + src);
                };

                img.src = src;
            });
        }

        if (type === 'audio') {
            return new Promise((resolve, reject) => {
                const audio = new Audio();

                audio.addEventListener('canplaythrough', () => {
                    this.assets[name] = audio;
                    resolve(audio);
                }, { once: true });

                audio.onerror = () => {
                    reject("Errore caricamento audio: " + src);
                };

                audio.src = src;
            });
        }
    }

    getAsset(name) {
        return this.assets[name];
    }

    playSound(audioAsset) {
        if (!audioAsset) return;
        const soundClone = audioAsset.cloneNode();
        soundClone.play().catch((err) => console.warn("Audio bloccato:", err));
    }

    playMusic(audioAsset, volume = 0.5) {
        if (!audioAsset) return;
        audioAsset.loop = true;
        audioAsset.volume = volume;
        audioAsset.play().catch(() => {
            const startAudio = () => {
                audioAsset.play();
                window.removeEventListener('click', startAudio);
                window.removeEventListener('keydown', startAudio);
            };
            window.addEventListener('click', startAudio);
            window.addEventListener('keydown', startAudio);
        });
    };
}
/** 🌟 Il ruolo di BeeEngine
 * È il cuore del motore di gioco.
 * Gestisce l'aggiornamento e il rendering delle entità.
 * Gestisce i controlli e gli input.
 * Carica e gestisce le risorse.
 * Coordina l'intero ciclo di vita dell'applicazione: aggiorna la logica (Update),
 * renderizza la grafica (Draw), gestisce gli input e carica le risorse (immagini/suoni).
 */




