Implementato un algoritmo di sfoltimento grafico (*Culling*) legato ai confini visivi di `BeeCamera`. 
* Prima di inviare i dati di disegno al Canvas, il motore verifica se l'entità o la porzione di mappa si trova dentro lo schermo.
* Se l'oggetto è fuori dalla visuale, il comando di rendering viene saltato. Questo **abbatte drasticamente l'uso della CPU**, mantenendo i 60 FPS stabili anche su mappe di grandi dimensioni.
* Elementi fissi come l'**HUD delle vite/punteggio** e i **Controlli Touch mobili** sono protetti dal culling per rimanere ancorati allo schermo.

### 3. Supporto Professionale NPM & IntelliSense Completo
Il motore è stato standardizzato per essere distribuito come libreria riutilizzabile:
* **`index.d.ts`**: Un file di oltre 600 righe di definizioni di tipo TypeScript che mappa tutte le 22 classi del motore. Abilita l'autocompletamento intelligente (IntelliSense) in VS Code/Cursor anche scrivendo in puro JavaScript.
* **`package.json`**: Configurato con standard ESM (ES Modules) ed esportazioni pulite per essere installato tramite terminale con `npm install`.

---

## 🛠️ Esempio d'Uso del Nuovo Sistema di Collisioni

All'interno dell'inizializzazione della tua scena in `main.js`:

```javascript
// 1. Pulisci e definisci i gruppi all'avvio della scena
gioco.collisions.clear();
gioco.collisions.setGroup('solids', this.piattaforme);
gioco.collisions.setGroup('player', [this.giocatore]);
gioco.collisions.setGroup('hazards', this.nemici);

// 2. Definisci le regole del mondo
gioco.collisions.solid('player', 'solids'); // Il giocatore atterra sui solidi

gioco.collisions.overlap('player', 'hazards', (player, hazard, engine) => {
    player.takeDamage(1); // Gestione danno automatica al contatto
});

// 3. Nel ciclo di update(), esegui tutte le regole in un colpo solo
gioco.collisions.run();
```

---
*BeeEngine è sviluppato con passione per rendere lo sviluppo di giochi 2D in JavaScript semplice, performante ed elegante!* 🐝
