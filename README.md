BeeEngine ha già il nucleo classico: entità, scene, asset, input, camera, AABB, sprite, tilemap, particelle, timer, save. Quello che manca, rispetto a Godot 2D, Unity 2D o Phaser, non è un altro nemico o un altro pulsante: sono i **sistemi di framework** che fanno girare qualsiasi genere, non un singolo platformer.

Ecco le classi fondamentali successive, in ordine di impatto.

### 1. Tempo globale (`BeeTime`)
Oggi il `dt` è un numero che passa nel loop. Serve un orologio di motore: delta scalato, delta “reale” (UI e audio che non congelano in pausa), `timeScale` per slow-motion, accumulo fisso per la fisica. Senza questo, pausa, tween e fisica restano tre orologi diversi.

### 2. Trasformata completa (`BeeTransform`)
Avete locale/mondo su X/Y. Manca il resto della trasformata 2D: rotazione, scala, pivot/origine, profondità (`zIndex`). I figli devono ereditare anche rotazione e scala del parent. Senza questo lo scene graph resta un albero di scatole allineate agli assi.

### 3. Corpo e mondo fisico (`BeeRigidBody` + `BeePhysicsWorld`)
L’entità deve restare dati. La fisica va in un mondo: massa, impulsi, gravità di scena, layer/mask (“player ignora i trigger”), forme oltre il rettangolo (cerchio, capsule). Il collision system attuale è un risolutore AABB a gruppi, non un motore fisico. I grandi engine separano *Transform* da *Body*.

### 4. Indice spaziale (`BeeSpatialHash` o quadtree)
Le collisioni a coppie di gruppi esplodono con decine di proiettili e nemici. Un indice spaziale risponde: “chi è vicino a questo AABB?”. È il pezzo che rende solida la fisica, i overlap e anche i query tipo “esplosione in raggio”.

### 5. Pool di oggetti (`BeePool`)
Create/destroy di bullet e particelle è il memory leak e il GC hitch più comune in Canvas. Un pool prealloca, `spawn`/`release`, resetta lo stato. Senza pooling un engine 2D “completo” non regge uno shoot ’em up.

### 6. Tween e timeline (`BeeTween` + `BeeTimeline`)
Interpolare posizione, alpha, scala, volume, con easing e sequenze. Il timer attuale è un cooldown, non un sistema di animazione di proprietà. Menu, camere, feedback e cutscene nei motori grandi passano quasi tutti da qui.

### 7. Macchina a stati delle animazioni (`BeeAnimator`)
`BeeAnimatedSprite` riproduce un clip. Manca il grafo: idle → run → jump, transizioni, priorità, lock del clip di attacco. Lo sprite resta il renderer; l’animator decide *quale* clip e *quando*.

### 8. Mixer audio (`BeeAudioMixer`)
Avete play one-shot e musica loop. Manca il grafo: bus (SFX / musica / UI), volume per canale, fade, ducking della musica quando parla un dialogo, audio 2D (volume/pan in base alla camera). Altrimenti l’audio resta una demo, non un sistema.

### 9. Pipeline di disegno (`BeeLayer` / ordinamento)
Oggi l’ordine è “array di entità”. Serve: layer (sfondo, world, y-sort, UI), ordinamento stabile, UI disegnata in spazio schermo *dopo* la camera. I motori grandi non mischiano HUD e mondo nello stesso passaggio.

### 10. Prefab / fabbrica (`BeePrefab`)
Istantiare “un nemico identico a questo template” da dati (posizione, sprite, vita, collider), non `new BeeEnemy(...)` sparso nel gioco. È quello che rende riutilizzabile il framework: livelli, wave, spawn da tilemap.

### 11. Pathfinding (`BeePathfinder`)
A* (o flow field) sulla griglia/tilemap: camminabile vs solido, ricalcolo, inseguimento. Senza questo l’AI 2D resta pattuglia e chase in linea retta.

### 12. UI vera (`BeeUI` / nodi layout)
Un bottone su canvas non è un sistema UI. Servono: ancoraggi (angoli, stretch), stack/pannelli, nine-slice, focus tastiera/gamepad, spazio schermo indipendente dalla camera. Godot ha `Control`; Unity ha uGUI. È il buco più visibile per menu e HUD complessi.



---

**Cosa non mettere nella lista “fondamentale”:** altre classi di genere (`BeeBoss`, `BeeInventory`, `BeeDialogue`). Quello è contenuto di gioco. I motori grandi le lasciano all’utente, o le danno come plugin.

**Ordine sensato:** `BeeTime` → trasformata completa → fisica+indice spaziale → pool → tween → animator → audio mixer → layer di render. Prefab, pathfinding, UI e debug possono entrare in parallelo, ma senza i primi cinque resti un kit da platformer, non un framework 2D.
