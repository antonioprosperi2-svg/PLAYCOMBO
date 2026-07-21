export class BeeSceneManager {
    constructor(ctx) {
        this.ctx = ctx;
        this.scenes = new Map();
        this.currentScene = null;
        this.currentSceneName = null;
    }

    add(name, scene) {
        if (!scene.entities) {
            scene.entities = [];
        }

        this.scenes.set(name, scene);
    }

    change(name, data = null) {
        if (!this.scenes.has(name)) {
            throw new Error(`Scena non trovata: ${name}`);
        }

        if (this.currentScene?.onExit) {
            this.currentScene.onExit();
        }

        this.currentScene = this.scenes.get(name);
        this.currentSceneName = name;

        if (this.currentScene.onEnter) {
            this.currentScene.onEnter(data);
        }
    }

    addEntity(entity) {
        if (!this.currentScene) return;

        this.currentScene.entities.push(entity);
    }

    update(dt) {
        if (!this.currentScene) return;

        if (this.currentScene.update) {
            this.currentScene.update(dt, this);
        }

        for (const entity of this.currentScene.entities) {
            if (entity.active) {
                entity.update(dt, this);
            }
        }

        this.currentScene.entities = this.currentScene.entities.filter(
            entity => entity.active
        );

        BeeButton.endFrame();
    }

    draw() {
        if (!this.currentScene) return;

        const canvas = this.ctx.canvas;

        this.ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (this.currentScene.draw) {
            this.currentScene.draw(this.ctx, this);
        }

        for (const entity of this.currentScene.entities) {
            if (entity.visible) {
                entity.draw(this.ctx);
            }
        }
    }
}
/** 🌟 * Classe BeeSceneManager: Gestisce la transizione e il rendering delle scene nel gioco.
 * Permette di organizzare il contenuto del gioco in diverse "scene" (menu, livelli, ecc.),
 * caricando e disegnando gli elementi appropriati per ogni stato del gioco.
 /**
 * Classe BeeSceneManager: Coordina il passaggio tra le diverse schermate (scene) del gioco.
 * Controlla quale scena deve essere attiva in ogni momento, gestendo la transizione fluida
 * dal Menu Principale (BeeMenuScene), alla partita vera e propria, fino al Game Over.
 */
