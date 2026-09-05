

<main>
    <h2>I giochi più popolari</h2>

    <div class="grid-giochi">
        <article class="card-gioco">
            <a href="INSERISCI_QUI_URL_DEL_GIOCO_BOLLLE.php">
                <img src="immagini/bolle.jpg" alt="Gioco Sparabolle">
                <h3>Sparabolle Classic</h3>
            </a>
        </article>

        <article class="card-gioco">
            <a href="INSERISCI_QUI_URL_DEL_GIOCO_MEMORY.php">
                <img src="immagini/memory.jpg" alt="Gioco Memory">
                <h3>Memory Fantasy</h3>
            </a>
        </article>

        <article class="card-gioco">
            <a href="INSERISCI_QUI_URL_DEL_GIOCO_SOLITARIO.php">
                <img src="immagini/solitario.jpg" alt="Gioco Solitario">
                <h3>Solitario Gold</h3>
            </a>
        </article>
    </div>

</main>



<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Convertitore Avanzato PlayCombo (Con Tag Categoria)</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f0f4f8;
            color: #333;
        }

        .box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            height: 200px;
            font-family: monospace;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #ff9900;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #e08800;
        }

        pre {
            background: #222;
            color: #fff;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            max-height: 250px;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-top: 10px;
            font-size: 18px;
        }

        .info-feed {
            color: #0077be;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .categoria-container {
            background: #fff;
            border-left: 6px solid #0077be;
            padding: 15px;
            margin-top: 20px;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .categoria-titolo {
            font-size: 20px;
            font-weight: bold;
            color: #0077be;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .btn-copia-cat {
            background: #0077be;
            margin-bottom: 10px;
        }

        .btn-copia-cat:hover {
            background: #005a9c;
        }

        .conteggio-tag {
            background: #e1f0fa;
            color: #0077be;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 14px;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <h2>Convertitore Intelligente per www.playcombo.it</h2>
    <p>Riconosce in automatico il feed e inserisce il tag <strong>"categoria"</strong> dentro ogni singolo gioco per
        attivare i filtri live su WordPress.</p>

    <div class="box">
        <h3>1. Incolla qui il testo del feed</h3>
        <textarea id="inputJson" placeholder="Incolla qui il JSON di GamePix o GameMonetize..."></textarea>
        <button onclick="convertiEDividiFeedUniversale()">Converti e Prepara per WordPress</button>
    </div>

    <div id="risultatoGlobale" class="box" style="display:none;">
        <h3>2. Risultato della Conversione</h3>
        <p id="tipoFeedRilevato" class="info-feed"></p>
        <p id="infoConteggioTotale" class="success"></p>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <div id="areaCategorie"></div>
    </div>

    <!-- DA QUI IN POI C'È IL JAVASCRIPT CHE ABBIAMO CORRETTO -->
    <script>
        function convertiEDividiFeedUniversale() {
            const inputTesto = document.getElementById('inputJson').value.trim();
            if (!inputTesto) { alert('Incolla prima il testo del feed!'); return; }

            try {
                const dati = JSON.parse(inputTesto);
                let elencoGiochi = [];
                let tipoFeed = "Sconosciuto";

                if (Array.isArray(dati)) {
                    elencoGiochi = dati;
                    if (dati.length > 0 && (dati[0].thumb || dati[0].thumb1)) {
                        tipoFeed = "GameMonetize o HTML5 (Lista Diretta)";
                    } else {
                        tipoFeed = "Lista Standard";
                    }
                } else if (dati.data && Array.isArray(dati.data)) {
                    elencoGiochi = dati.data;
                    tipoFeed = "GamePix (Formato Partner / Data)";
                } else if (dati.games && Array.isArray(dati.games)) {
                    elencoGiochi = dati.games;
                    tipoFeed = "GamePix (Formato Standard / Games)";
                } else if (dati.items && Array.isArray(dati.items)) {
                    elencoGiochi = dati.items;
                    tipoFeed = "Formato Alternativo (Items)";
                } else {
                    alert('Struttura dati non riconosciuta. Assicurati di copiare tutto il testo.');
                    return;
                }

                const categorieRaggruppate = {};
                let totaleGiochi = 0;

                elencoGiochi.forEach(gioco => {
                    let imgUrl = '';

                    // --- CORREZIONE: Ora legge anche thumb1 per i feed HTML5 ---
                    if (gioco.thumb1 && typeof gioco.thumb1 === 'string') {
                        imgUrl = gioco.thumb1;
                    } else if (gioco.thumb && typeof gioco.thumb === 'string') {
                        imgUrl = gioco.thumb;
                    } else if (gioco.image) {
                        imgUrl = gioco.image;
                    } else if (gioco.thumbnailUrl) {
                        imgUrl = gioco.thumbnailUrl;
                    } else if (gioco.thumb && typeof gioco.thumb === 'object') {
                        imgUrl = gioco.thumb.url || gioco.thumb.large || '';
                    }

                    // Pulisce le sbarrette storte (\/) se presenti nel link dell'immagine
                    if (imgUrl) {
                        imgUrl = imgUrl.replace(/\\/g, '');
                    }

                    let descrizione = gioco.desc_it || gioco.description || '';
                    if (!descrizione && gioco.instructions) {
                        descrizione = gioco.instructions;
                    }
                    if (!descrizione) {
                        descrizione = 'del gioco completa';
                    }

                    let catNome = gioco.category || 'Altri';
                    catNome = catNome.trim();

                    const datiGioco = {
                        "titolo": gioco.title || gioco.name || 'Gioco senza titolo',
                        "url": gioco.url || gioco.embed_url || '',
                        "img": imgUrl,
                        "desc": descrizione,
                        "categoria": catNome
                    };

                    if (!categorieRaggruppate[catNome]) {
                        categorieRaggruppate[catNome] = [];
                    }
                    categorieRaggruppate[catNome].push(datiGioco);
                    totaleGiochi++;
                });

                document.getElementById('tipoFeedRilevato').textContent = "Sorgente Rilevata: " + tipoFeed;
                document.getElementById('infoConteggioTotale').textContent = 'Conversione riuscita! Rilevati ' + totaleGiochi + ' giochi totali con tag di filtraggio.';

                const areaCategorie = document.getElementById('areaCategorie');
                areaCategorie.innerHTML = '';

                let idContatore = 0;
                for (const [nomeCategoria, listaGiochi] of Object.entries(categorieRaggruppate)) {
                    idContatore++;
                    const idPre = 'codice_cat_' + idContatore;
                    const stringaCodice = "const listaGiochi = " + JSON.stringify(listaGiochi, null, 4) + ";";

                    const divCategoria = document.createElement('div');
                    divCategoria.className = 'categoria-container';
                    divCategoria.innerHTML = `
                        <div class="categoria-titolo">${nomeCategoria} <span class="conteggio-tag">${listaGiochi.length} giochi</span></div>
                        <button class="btn-copia-cat" onclick="window.copiaCodiceSpecifico('${idPre}', '${nomeCategoria}')">Copia codice per "${nomeCategoria}"</button>
                        <pre><code id="${idPre}"></code></pre>
                    `;
                    areaCategorie.appendChild(divCategoria);
                    document.getElementById(idPre).textContent = stringaCodice;
                }

                document.getElementById('risultatoGlobale').style.display = 'block';
                document.getElementById('risultatoGlobale').scrollIntoView({ behavior: 'smooth' });

            } catch (e) {
                alert('Errore di lettura JSON. Controlla il testo incollato.');
            }
        }

        window.copiaCodiceSpecifico = function (idElemento, nomeCat) {
            const codice = document.getElementById(idElemento).textContent;
            navigator.clipboard.writeText(codice);
            alert('Codice della categoria "' + nomeCat + '" copiato negli appunti! Contiene i tag compatibili per i filtri live.');
        }
    </script>
</body>

</html>
  



per eliminare doppioni e creare un array unico di giochi, puoi aggiungere un controllo prima di inserire un gioco nella categoria. Ad esempio, puoi usare un Set per tenere traccia dei titoli già aggiunti:


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎮 Convertitore PlayCombo</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: sans-serif;
            background: linear-gradient(135deg, #1a1a2e, #0077be);
            min-height: 100vh;
            padding: 20px;
            color: white;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #ff9900;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card h2 {
            font-size: 16px;
            margin-bottom: 12px;
            color: #ff9900;
        }

        textarea {
            width: 100%;
            height: 150px;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            color: white;
            padding: 12px;
            font-size: 13px;
            resize: vertical;
            font-family: monospace;
        }

        textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        button {
            background: #ff9900;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            margin: 8px 4px;
            transition: transform 0.2s, background 0.2s;
        }

        button:hover {
            transform: scale(1.05);
            background: #e08800;
        }

        button.secondary {
            background: rgba(255, 255, 255, 0.2);
        }

        button.secondary:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        button.danger {
            background: #cc0000;
        }

        .stats {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .stat {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 14px;
            font-weight: bold;
        }

        .stat span {
            color: #ff9900;
            font-size: 18px;
        }

        .categorie-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .tab {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab.attivo,
        .tab:hover {
            background: #ff9900;
            border-color: #ff9900;
        }

        .griglia-test {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 10px;
            max-height: 400px;
            overflow-y: auto;
            padding: 5px;
        }

        .card-gioco {
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            background: rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
        }

        .card-gioco:hover {
            transform: scale(1.05);
        }

        .card-gioco img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            display: block;
        }

        .card-gioco .stato {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #888;
            border: 2px solid white;
        }

        .card-gioco .stato.ok {
            background: #00cc44;
        }

        .card-gioco .stato.errore {
            background: #cc0000;
        }

        .card-gioco .stato.test {
            background: #ff9900;
        }

        .card-gioco .titolo-gioco {
            font-size: 10px;
            padding: 4px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            background: rgba(0, 0, 0, 0.5);
        }

        .output-box {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 12px;
            padding: 15px;
            font-family: monospace;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 20px;
        }

        .modal.aperto {
            display: flex;
        }

        .modal-header {
            width: 100%;
            max-width: 800px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-header h3 {
            color: #ff9900;
            font-size: 18px;
        }

        .modal iframe {
            width: 100%;
            max-width: 800px;
            height: 60vh;
            border: none;
            border-radius: 12px;
            background: #000;
        }

        .modal-info {
            width: 100%;
            max-width: 800px;
            margin-top: 10px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-elimina-modal {
            background: #cc0000;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
        }

        #barra-progresso {
            width: 100%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            height: 10px;
            margin: 10px 0;
            display: none;
        }

        #barra-fill {
            height: 100%;
            background: #ff9900;
            border-radius: 50px;
            transition: width 0.3s;
            width: 0%;
        }

        .log {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 8px;
            min-height: 20px;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 6px;
        }

        .badge.verde {
            background: #00cc44;
            color: #000;
        }

        .badge.rosso {
            background: #cc0000;
        }

        .badge.grigio {
            background: #888;
        }
    </style>
</head>

<body>

    <h1>🎮 Convertitore PlayCombo</h1>

    <!-- STEP 1: INCOLLA -->
    <div class="card">
        <h2>📥 STEP 1 — Incolla il tuo JSON</h2>
        <textarea id="inputJson" placeholder='Incolla qui il JSON dei giochi...
Esempio:
[
  {
    "titolo": "Nome Gioco",
    "url": "https://...",
    "img": "https://...",
    "desc": "Descrizione...",
    "categoria": "puzzle"
  }
]'></textarea>
        <br>
        <button onclick="elabora()">⚙️ ELABORA</button>
        <button class="secondary" onclick="reset()">🗑️ RESET</button>
        <div class="log" id="log-step1"></div>
    </div>

    <!-- STEP 2: STATISTICHE -->
    <div class="card" id="card-stats" style="display:none;">
        <h2>📊 STEP 2 — Risultati elaborazione</h2>
        <div class="stats">
            <div class="stat">Totale: <span id="tot-totale">0</span></div>
            <div class="stat">✅ Unici: <span id="tot-unici">0</span></div>
            <div class="stat">🗑️ Doppioni rimossi: <span id="tot-doppioni">0</span></div>
        </div>

        <div class="categorie-tabs" id="tabs-categorie"></div>

        <div id="barra-progresso">
            <div id="barra-fill"></div>
        </div>
        <div class="log" id="log-test"></div>

        <div class="griglia-test" id="griglia-giochi"></div>

        <br>
        <button onclick="testaTutti()">🔍 TESTA TUTTI I GIOCHI</button>
        <button class="secondary" onclick="eliminaErrori()">🗑️ ELIMINA NON FUNZIONANTI</button>
    </div>

    <!-- STEP 3: EXPORT -->
    <div class="card" id="card-export" style="display:none;">
        <h2>📤 STEP 3 — Esporta per WordPress</h2>
        <div class="categorie-tabs" id="tabs-export"></div>
        <br>
        <button onclick="esporta()">📋 GENERA CODICE</button>
        <button class="secondary" onclick="copiaOutput()">📄 COPIA</button>
        <br><br>
        <div class="output-box" id="output-codice">Il codice apparirà qui...</div>
    </div>

    <!-- MODAL ANTEPRIMA GIOCO -->
    <div class="modal" id="modal-gioco">
        <div class="modal-header">
            <h3 id="modal-titolo">Titolo gioco</h3>
            <div>
                <button class="btn-elimina-modal" onclick="eliminaGiocoCorrente()">🗑️ ELIMINA</button>
                <button class="secondary" onclick="chiudiModal()" style="margin-left:8px;">✕ CHIUDI</button>
            </div>
        </div>
        <iframe id="modal-iframe" src=""></iframe>
        <div class="modal-info" id="modal-info"></div>
    </div>

    <script>
        let giochi = [];
        let giochiPerCategoria = {};
        let categoriaSelezionata = 'tutti';
        let categoriaExport = 'tutti';
        let giocoCorrenteIndex = -1;

        function elabora() {
            const input = document.getElementById('inputJson').value.trim();
            const log = document.getElementById('log-step1');

            if (!input) { log.textContent = '⚠️ Incolla prima il JSON!'; return; }

            let dati;
            try {
                // Prova parsing diretto
                dati = JSON.parse(input);
            } catch (e) {
                // Prova a estrarre array dal testo
                const match = input.match(/\[[\s\S]*\]/);
                if (match) {
                    try { dati = JSON.parse(match[0]); }
                    catch (e2) { log.textContent = '❌ JSON non valido. Controlla il formato.'; return; }
                } else {
                    log.textContent = '❌ JSON non valido. Controlla il formato.'; return;
                }
            }

            if (!Array.isArray(dati)) dati = [dati];

            const totaleOriginale = dati.length;

            // Normalizza categorie (tutto minuscolo)
            dati = dati.map(g => ({
                ...g,
                categoria: (g.categoria || 'altro').toLowerCase().trim()
            }));

            // Rimuovi doppioni per URL
            const urlVisti = new Set();
            const unici = [];
            dati.forEach(g => {
                const key = (g.url || '').trim();
                if (key && !urlVisti.has(key)) {
                    urlVisti.add(key);
                    unici.push(g);
                }
            });

            giochi = unici;
            const doppioni = totaleOriginale - unici.length;

            document.getElementById('tot-totale').textContent = totaleOriginale;
            document.getElementById('tot-unici').textContent = unici.length;
            document.getElementById('tot-doppioni').textContent = doppioni;

            // Categorie
            giochiPerCategoria = { 'tutti': giochi };
            giochi.forEach(g => {
                const cat = g.categoria || 'altro';
                if (!giochiPerCategoria[cat]) giochiPerCategoria[cat] = [];
                giochiPerCategoria[cat].push(g);
            });

            log.textContent = `✅ Elaborato! ${doppioni} doppioni rimossi automaticamente.`;

            document.getElementById('card-stats').style.display = 'block';
            document.getElementById('card-export').style.display = 'block';

            aggiornaTabs();
            aggiornaGriglia();
            aggiornaTabsExport();
        }

        function aggiornaTabs() {
            const container = document.getElementById('tabs-categorie');
            container.innerHTML = '';

            Object.keys(giochiPerCategoria).forEach(cat => {
                const n = giochiPerCategoria[cat].length;
                const btn = document.createElement('button');
                btn.className = 'tab' + (cat === categoriaSelezionata ? ' attivo' : '');
                btn.innerHTML = `${cat} <span class="badge grigio">${n}</span>`;
                btn.onclick = () => {
                    categoriaSelezionata = cat;
                    aggiornaTabs();
                    aggiornaGriglia();
                };
                container.appendChild(btn);
            });
        }

        function aggiornaGriglia() {
            const griglia = document.getElementById('griglia-giochi');
            griglia.innerHTML = '';

            const lista = giochiPerCategoria[categoriaSelezionata] || [];

            lista.forEach((gioco, i) => {
                const idx = giochi.indexOf(gioco);
                const div = document.createElement('div');
                div.className = 'card-gioco';
                div.id = 'gioco-' + idx;

                const stato = document.createElement('div');
                stato.className = 'stato ' + (gioco._stato || '');
                stato.id = 'stato-' + idx;

                const img = document.createElement('img');
                img.src = gioco.img || '';
                img.alt = gioco.titolo || '';
                img.onerror = function () {
                    this.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><rect width="100%" height="100%" fill="%23222"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23666" font-size="12">No Img</text></svg>';
                };

                const titolo = document.createElement('div');
                titolo.className = 'titolo-gioco';
                titolo.textContent = gioco.titolo || 'Senza titolo';

                div.appendChild(stato);
                div.appendChild(img);
                div.appendChild(titolo);
                div.onclick = () => apriModal(idx);

                griglia.appendChild(div);
            });

            document.getElementById('log-test').textContent = `Mostrati ${lista.length} giochi`;
        }

        async function testaTutti() {
            const log = document.getElementById('log-test');
            const barra = document.getElementById('barra-progresso');
            const fill = document.getElementById('barra-fill');

            barra.style.display = 'block';
            let testati = 0;
            let ok = 0;
            let errori = 0;

            const lista = giochiPerCategoria[categoriaSelezionata] || [];

            for (const gioco of lista) {
                const idx = giochi.indexOf(gioco);
                const statoEl = document.getElementById('stato-' + idx);
                if (statoEl) statoEl.className = 'stato test';

                try {
                    const res = await fetch(gioco.url, { method: 'HEAD', mode: 'no-cors', signal: AbortSignal.timeout(5000) });
                    gioco._stato = 'ok';
                    ok++;
                    if (statoEl) statoEl.className = 'stato ok';
                } catch (e) {
                    gioco._stato = 'errore';
                    errori++;
                    if (statoEl) statoEl.className = 'stato errore';
                }

                testati++;
                fill.style.width = (testati / lista.length * 100) + '%';
                log.textContent = `Test: ${testati}/${lista.length} — ✅ ${ok} funzionanti — ❌ ${errori} errori`;
            }

            log.textContent = `✅ Test completato! ${ok} funzionanti, ${errori} non funzionanti.`;
        }

        function eliminaErrori() {
            const prima = giochi.length;
            giochi = giochi.filter(g => g._stato !== 'errore');

            // Ricalcola categorie
            giochiPerCategoria = { 'tutti': giochi };
            giochi.forEach(g => {
                const cat = g.categoria || 'altro';
                if (!giochiPerCategoria[cat]) giochiPerCategoria[cat] = [];
                giochiPerCategoria[cat].push(g);
            });

            document.getElementById('tot-unici').textContent = giochi.length;
            aggiornaTabs();
            aggiornaGriglia();
            aggiornaTabsExport();
            document.getElementById('log-test').textContent = `🗑️ Rimossi ${prima - giochi.length} giochi non funzionanti.`;
        }

        function apriModal(idx) {
            giocoCorrenteIndex = idx;
            const gioco = giochi[idx];
            if (!gioco) return;

            document.getElementById('modal-titolo').textContent = gioco.titolo || 'Senza titolo';
            document.getElementById('modal-iframe').src = gioco.url || '';
            document.getElementById('modal-info').innerHTML = `
        <strong>Categoria:</strong> ${gioco.categoria || 'N/D'}<br>
        <strong>URL:</strong> ${gioco.url || 'N/D'}<br>
        <strong>Desc:</strong> ${(gioco.desc || '').substring(0, 150)}...
    `;
            document.getElementById('modal-gioco').classList.add('aperto');
        }

        function chiudiModal() {
            document.getElementById('modal-iframe').src = '';
            document.getElementById('modal-gioco').classList.remove('aperto');
            giocoCorrenteIndex = -1;
        }

        function eliminaGiocoCorrente() {
            if (giocoCorrenteIndex < 0) return;
            giochi.splice(giocoCorrenteIndex, 1);

            giochiPerCategoria = { 'tutti': giochi };
            giochi.forEach(g => {
                const cat = g.categoria || 'altro';
                if (!giochiPerCategoria[cat]) giochiPerCategoria[cat] = [];
                giochiPerCategoria[cat].push(g);
            });

            document.getElementById('tot-unici').textContent = giochi.length;
            chiudiModal();
            aggiornaTabs();
            aggiornaGriglia();
            aggiornaTabsExport();
        }

        function aggiornaTabsExport() {
            const container = document.getElementById('tabs-export');
            container.innerHTML = '';

            Object.keys(giochiPerCategoria).forEach(cat => {
                const n = giochiPerCategoria[cat].length;
                const btn = document.createElement('button');
                btn.className = 'tab' + (cat === categoriaExport ? ' attivo' : '');
                btn.innerHTML = `${cat} <span class="badge grigio">${n}</span>`;
                btn.onclick = () => {
                    categoriaExport = cat;
                    aggiornaTabsExport();
                };
                container.appendChild(btn);
            });
        }

        function esporta() {
            const lista = giochiPerCategoria[categoriaExport] || [];
            // Rimuovi campo _stato dall'output
            const puliti = lista.map(({ _stato, ...rest }) => rest);

            const json = JSON.stringify(puliti, null, 4);
            const codice = `<script>\nvar listaGiochi = ${json};\n<\/script>`;

            document.getElementById('output-codice').textContent = codice;
        }

        function copiaOutput() {
            const testo = document.getElementById('output-codice').textContent;
            navigator.clipboard.writeText(testo).then(() => {
                alert('✅ Copiato! Incollalo su WordPress.');
            }).catch(() => {
                alert('Seleziona e copia manualmente il testo dal box.');
            });
        }

        function reset() {
            giochi = [];
            giochiPerCategoria = {};
            categoriaSelezionata = 'tutti';
            categoriaExport = 'tutti';
            document.getElementById('inputJson').value = '';
            document.getElementById('log-step1').textContent = '';
            document.getElementById('card-stats').style.display = 'none';
            document.getElementById('card-export').style.display = 'none';
            document.getElementById('output-codice').textContent = 'Il codice apparirà qui...';
        }
    </script>

</body>

</html>