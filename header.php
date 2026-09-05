<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
    // Protezione da caratteri speciali per evitare bug grafici e SEO
    $titolo_sicuro = htmlspecialchars($titolo_gioco, ENT_QUOTES, 'UTF-8');
    $categoria_sicura = htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8');
    ?>

    <title>Gioca a <?php echo $titolo_sicuro; ?> Gratis Online | Play Combo</title>
    
    <meta name="description" content="Divertiti con <?php echo $titolo_sicuro; ?> su Play Combo. Gioca subito gratis al miglior gioco online della categoria <?php echo $categoria_sicura; ?> senza registrazione!">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #121214;
            color: #ad2c2c;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #1e1e24;
            border-bottom: 2px solid #ffcc00;
        }
        .logo img {
            max-height: 40px; /* Altezza del logo per non sballare l'header */
            display: block;
        }
        nav a {
            color: #df1b1b;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #ffcc00;
        }
        @media (max-width: 600px) {
            header {
                flex-direction: column;
                gap: 10px;
            }
            nav a {
                margin: 0 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php">
            <img src="https://www.playcombo.it/wp-content/uploads/2026/05/cropped-cropped-android-chrome-512x512-1.png" alt="Play Combo Logo">
        </a>
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="puzzle.php">Puzzle</a>
        <a href="quiz.php">Quiz</a>
        <a href="girls.php">Girls</a>
    </nav>
</header>

      vecchio
      // IL MOTORE DI GIOCO - PLAYCOMBO (www.playcombo.it)
const GIOCHI_PER_PAGINA = 50;
let paginaCorrente = 1;
let giochiFiltrati = [];
let categoriaAttuale = 'Tutti';

window.inizializzaGiochi = function() {
    if (typeof listaGiochi !== 'undefined') {
        giochiFiltrati = [].concat(listaGiochi);
        window.mostraPagina(paginaCorrente);
    }
};

window.mostraPagina = function (pagina) {
    const griglia = document.querySelector('.griglia-giochi-spc');
    if (!griglia) return;
    griglia.innerHTML = '';

    const inizio = (pagina - 1) * GIOCHI_PER_PAGINA;
    const fine = inizio + GIOCHI_PER_PAGINA;
    const giochiDaMostrare = giochiFiltrati.slice(inizio, fine);

    const fragment = document.createDocumentFragment();

    const observerOpzioni = { root: null, rootMargin: '150px 0px', threshold: 0.01 };
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                observer.unobserve(img);
            }
        });
    }, observerOpzioni);

    giochiDaMostrare.forEach((gioco, indice) => {
        const div = document.createElement('div');
        div.className = 'scheda-gioco-figura';

        const titoloSicuro = gioco.titolo ? gioco.titolo.replace(/"/g, '&quot;') : 'Gioco';
        const tipoCaricamento = (indice < 15) ? 'eager' : 'lazy';

        const img = document.createElement('img');
        img.alt = titoloSicuro;
        img.decoding = 'async';
        img.style.contentVisibility = 'auto';

        if (tipoCaricamento === 'eager') {
            img.src = gioco.img || '';
            img.setAttribute('data-no-lazy', '1');
            img.className = 'no-lazy';
        } else {
            img.dataset.src = gioco.img || '';
            imageObserver.observe(img);
        }

        img.onerror = function() {
            this.onerror = null;
            this.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100%" height="100%" fill="%23222"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23666" font-family="sans-serif" font-size="12">No Img</text></svg>';
        };

        div.appendChild(img);
        div.onclick = function () { window.apriAnteprima(gioco); };
        fragment.appendChild(div);
    });

    griglia.appendChild(fragment);

    const totalePagine = Math.ceil(giochiFiltrati.length / GIOCHI_PER_PAGINA) || 1;
    const infoPagina = document.getElementById('info-pagina');

    if (infoPagina) {
        let htmlNumeri = '';
        for (let i = 1; i <= totalePagine; i++) {
            if (i === 1 || i === totalePagine || (i >= pagina - 2 && i <= pagina + 2)) {
                if (i === pagina) {
                    htmlNumeri += '<strong>' + i + '</strong>';
                } else {
                    htmlNumeri += '<span onclick="window.cambiaPaginaDiretta(' + i + ')">' + i + '</span>';
                }
            } else if (i === pagina - 3 || i === pagina + 3) {
                htmlNumeri += '<span class="puntini">...</span>';
            }
        }
        infoPagina.innerHTML = htmlNumeri;
    }

    const btnPrec = document.getElementById('btn-precedente');
    const btnSucc = document.getElementById('btn-successiva');
    if (btnPrec) btnPrec.disabled = (pagina === 1);
    if (btnSucc) btnSucc.disabled = (pagina === totalePagine);
};

let searchTimeout;
window.filtraGiochi = function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const campo = document.getElementById('campoRicerca');
        const testoCercato = campo ? campo.value.toLowerCase().trim() : '';

        giochiFiltrati = listaGiochi.filter(gioco => {
            const matchTitolo = gioco.titolo ? gioco.titolo.toLowerCase().includes(testoCercato) : false;
            const catGioco = gioco.categoria ? gioco.categoria.toLowerCase() : '';
            const matchCategoria = (categoriaAttuale === 'Tutti' || catGioco === categoriaAttuale.toLowerCase());
            return matchTitolo && matchCategoria;
        });

        paginaCorrente = 1;
        window.mostraPagina(paginaCorrente);
    }, 200);
};

window.filtraCategoria = function (categoria, bottone) {
    categoriaAttuale = categoria;
    document.querySelectorAll('.btn-categoria-spc').forEach(btn => btn.classList.remove('attivo'));
    if (bottone) bottone.classList.add('attivo');
    window.filtraGiochi();
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

window.cambiaPagina = function (direzione) {
    paginaCorrente += direzione;
    window.mostraPagina(paginaCorrente);
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

window.cambiaPaginaDiretta = function (numeroPagina) {
    paginaCorrente = numeroPagina;
    window.mostraPagina(paginaCorrente);
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

let currentUrl = '';
window.apriAnteprima = function (gioco) {
    currentUrl = gioco.url || '';
    let titolo = gioco.titolo || 'Gioco';
    let descrizioneCompleta = gioco.desc || 'Gioca gratis!';
    let contenutoHtml = '<h3>' + titolo + '</h3><div id="box-descrizione">' + descrizioneCompleta + '</div>';

    const spazioInfo = document.getElementById('spazio-info');
    if (spazioInfo) spazioInfo.innerHTML = contenutoHtml;

    const btnAvvia = document.getElementById('btn-avvia');
    if (btnAvvia) btnAvvia.style.display = 'block';

    const spazioGioco = document.getElementById('spazio-gioco');
    if (spazioGioco) spazioGioco.style.display = 'none';

    const areaContenuto = document.getElementById('area-contenuto');
    if (areaContenuto) areaContenuto.style.display = 'flex';

    const corniceGioco = document.getElementById('cornice-gioco');
    if (corniceGioco) corniceGioco.style.display = 'flex';
};

window.avviaGioco = function () {
    const areaContenuto = document.getElementById('area-contenuto');
    if (areaContenuto) areaContenuto.style.display = 'none';

    const gameDiv = document.getElementById('spazio-gioco');
    if (gameDiv) {
        gameDiv.innerHTML = '<iframe src="' + currentUrl + '" width="100%" height="100%" frameborder="0" allowfullscreen loading="lazy"></iframe>';
        gameDiv.style.display = 'block';
    }
};

window.chiudiGiocatore = function () {
    const corniceGioco = document.getElementById('cornice-gioco');
    if (corniceGioco) corniceGioco.style.display = 'none';

    const gameDiv = document.getElementById('spazio-gioco');
    if (gameDiv) gameDiv.innerHTML = '';
};

if (typeof listaGiochi !== 'undefined' && listaGiochi.length > 0) {
    window.inizializzaGiochi();
}