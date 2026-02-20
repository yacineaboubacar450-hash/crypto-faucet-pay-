<?php
$sujet = "trending"; 
$apiKey = "AIzaSyCSo627rVKvpJRuvBxdy78ajSAcj_2RAus"; 

$apiUrl = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=20&order=date&type=video&q=".urlencode($sujet)."&key=".$apiKey;

$response = @file_get_contents($apiUrl);
$data = json_decode($response, true);

$mes_videos = [];
if(isset($data['items']) && is_array($data['items'])) {
    foreach($data['items'] as $item) {
        // LIEN CORRIGÉ : On utilise /embed/ pour que YouTube autorise l'affichage
        $mes_videos[] = "https://www.youtube.com/embed/" . $item['id']['videoId'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Y-CASHT">
    <link rel="apple-touch-icon" href="https://votre-site.com/logo.png">
    <meta name="theme-color" content="#000000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Regardez des vidéos et gagnez de l'argent réel sur Empire Y-Casht. Retraits rapides et bonus de parrainage.">
    <meta name="keywords" content="argent, gagner, vidéo, tiktok, cash, empire">
    <meta property="og:image" content="https://votre-site.com/logo-boss.png">
    <title>VIDEOCASH PRO - EMPIRE Y-CASHT ULTRA</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --gold: #FFD700; 
            --tiktok-red: #fe2c55; 
            --bg-dark: #020202;
            --glass: rgba(255, 255, 255, 0.1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background: var(--bg-dark); color: #fff; overflow: hidden; height: 100vh; }

        @keyframes scrollText { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
        @keyframes fadeUp { 0% { opacity: 1; transform: translate(-50%, -50%); } 100% { opacity: 0; transform: translate(-50%, -150%); } }
        @keyframes slideIn { from { transform: translateX(-100%); } to { transform: translateX(0); } }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        @keyframes bounce { 0%, 100% { transform: translate(-50%, 0); } 50% { transform: translate(-50%, -15px); } }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        #progress-container { position: fixed; top: 0; left: 0; width: 100%; height: 5px; background: rgba(255,255,255,0.1); z-index: 1000; }
        #progress-bar { width: 0%; height: 100%; background: linear-gradient(90deg, var(--gold), #fff); box-shadow: 0 0 15px var(--gold); transition: width 0.1s linear; }

        .timer-wheel {
            width: 50px; height: 50px; border: 4px solid rgba(255,255,255,0.1); border-top: 4px solid var(--gold);
            border-radius: 50%; margin-bottom: 15px; display: flex; align-items: center; justify-content: center;
            animation: spin 2s linear infinite; animation-play-state: paused; background: rgba(0,0,0,0.5);
            box-shadow: 0 0 10px rgba(255,215,0,0.3);
        }

        #next-arrow {
            position: fixed; bottom: 150px; left: 50%; transform: translateX(-50%);
            background: var(--gold); color: #000; padding: 10px 20px; border-radius: 30px;
            font-weight: 900; font-size: 14px; display: none; z-index: 999;
            animation: bounce 1.2s infinite; box-shadow: 0 0 20px rgba(255,215,0,0.5);
        }

        .pub-top { position: fixed; top: 70px; width: 100%; z-index: 100; display: flex; justify-content: center; }
        .pub-bottom { position: fixed; bottom: 110px; width: 100%; z-index: 100; display: flex; justify-content: center; }
        .pub-container { background: var(--glass); width: 320px; height: 50px; border: 1px solid var(--gold); border-radius: 10px; backdrop-filter: blur(5px); overflow: hidden; }

        #player-box { height: 100vh; overflow-y: scroll; scroll-snap-type: y mandatory; }
        .video-section { height: 100vh; width: 100vw; scroll-snap-align: start; position: relative; background: #000; overflow: hidden; }
        iframe { width: 100vw; height: 100vh; border: none; }

        .top-header { position: fixed; top: 0; width: 100%; padding: 25px 15px; display: flex; justify-content: space-between; align-items: center; z-index: 500; background: linear-gradient(to bottom, rgba(0,0,0,0.8), transparent); }
        .wallet-card { background: rgba(0,0,0,0.6); padding: 12px 20px; border-radius: 40px; border: 2px solid var(--gold); backdrop-filter: blur(10px); }
        #w-amount { color: var(--gold); font-weight: 900; font-size: 1.4rem; }

        .side-bar { position: absolute; right: 15px; bottom: 180px; display: flex; flex-direction: column; align-items: center; z-index: 200; gap: 20px; }
        .side-icon { text-align: center; color: #fff; cursor: pointer; text-shadow: 0 2px 4px rgba(0,0,0,0.8); }
        .side-icon i { font-size: 35px; }
        .side-icon span { font-size: 12px; font-weight: bold; margin-top: 5px; display: block; }
        .liked { color: var(--tiktok-red) !important; transform: scale(1.2); }

        #side-menu { position: fixed; top: 0; left: -100%; width: 300px; height: 100%; background: #0a0a0a; z-index: 2000; transition: 0.5s; padding: 40px 20px; border-right: 2px solid var(--gold); }
        #side-menu.active { left: 0; }
        .menu-item { padding: 20px; border-bottom: 1px solid #1a1a1a; display: flex; align-items: center; gap: 15px; font-size: 17px; color: #eee; cursor: pointer; }

        #loginModal, #wheelModal, #missionModal { display:none; position:fixed; z-index:3000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.9); align-items:center; justify-content:center; backdrop-filter: blur(15px); }
        .modal-content { background:#111; padding:40px 30px; border-radius:30px; width:90%; max-width:380px; border:2px solid var(--gold); text-align:center; }
        input { width: 100%; padding: 15px; margin: 20px 0; border-radius: 12px; border: none; background: #222; color: #fff; }

        .btn-start-container { position: fixed; bottom: 50px; width: 100%; display: flex; justify-content: center; z-index: 1000; }
        .btn-gold { background: var(--gold); color: #000; border: none; padding: 20px 50px; border-radius: 60px; font-weight: 900; font-size: 1.3rem; cursor: pointer; animation: pulse 2s infinite; }
    </style>

    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>
</head>
<body onload="demarrerMachine()">


<div id="progress-container"><div id="progress-bar"></div></div>

<div style="position:fixed; top:10px; width: 100%; z-index: 1100;">
    <div style="width: 90%; margin: auto; background: #222; height: 10px; border-radius: 10px; border: 1px solid #333;">
        <div id="goal-bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #00ff00, var(--gold)); border-radius: 10px; transition: 0.5s;"></div>
    </div>
    <p style="font-size: 9px; text-align: center; color: #aaa; margin-top: 2px;">Objectif Retrait : <span id="goal-percent">0</span>%</p>
</div><div class="pub-top" style="position: fixed; top: 115px; width: 100%; z-index: 1000; display: flex; justify-content: center;">
    <div style="width: 220px; height: 65px; background: rgba(0,0,0,0.85); border: 2px solid var(--gold); border-radius: 40px; padding: 5px; overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(255,215,0,0.4); backdrop-filter: blur(10px);">
        
        <script async="async" data-cfasync="false" src="https://pl28671409.effectivegatecpm.com/9c7dec0c10065ae2a693e9de9a6b5531/invoke.js"></script>
        <div id="container-9c7dec0c10065ae2a693e9de9a6b5531" style="transform: scale(0.7); transform-origin: center;"></div>
        
    </div>
</div>


<div id="next-arrow"><i class="fas fa-arrow-down"></i> SCROLLEZ POUR GAGNER</div>

<div id="live-notif" style="position: fixed; bottom: 170px; left: 10px; background: rgba(0,0,0,0.8); padding: 8px 15px; border-radius: 20px; border-left: 4px solid var(--gold); font-size: 11px; z-index: 9999; display: none; animation: slideIn 0.5s;">
    <i class="fas fa-check-circle" style="color:var(--gold)"></i> <span id="notif-text"></span>
</div>

<div id="loginModal">
    <div class="modal-content">
        <h2 style="color:var(--gold)">EMPIRE Y-CASHT</h2>
        <p style="font-size:13px; margin-top:10px; opacity:0.8;">Connectez-vous pour encaisser vos gains</p>
        <input type="email" id="userEmailInput" placeholder="votre@email.com">
        <button onclick="saveLogin()" style="width:100%; background:var(--gold); padding:15px; border-radius:15px; font-weight:900; border:none;">ACTIVER MON COMPTE</button>
        <p id="cancelBtn" onclick="document.getElementById('loginModal').style.display='none'" style="margin-top:20px; cursor:pointer; font-size:12px;">Plus tard</p>
    </div>
</div>

<div id="side-menu">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
        <h2 style="color:var(--gold);">MON PANEL</h2>
        <i class="fas fa-times" onclick="toggleMenu()" style="font-size:25px;"></i>
    </div>
    
    <div style="padding: 10px; background: rgba(255, 215, 0, 0.1); border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <div style="width: 40px; height: 40px; background: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: black; font-weight: bold;">
            <i class="fas fa-user-crown"></i>
        </div>
        <div>
            <div style="font-size: 12px; color: white; font-weight: bold;">Boss_Member <i class="fas fa-check-circle" style="color: #1DA1F2; font-size: 10px;"></i></div>
            <div style="font-size: 9px; color: var(--gold);">Compte Vérifié</div>
        </div>
    </div>

    <div class="menu-item" onclick="ouvrirMissions()" style="color: #00e5ff;"><i class="fas fa-tasks"></i> Missions du Boss</div>
    <div class="menu-item" onclick="ouvrirRoue()" style="color: var(--gold);"><i class="fas fa-dharmachakra"></i> Roue de la Fortune</div>
    <div class="menu-item" onclick="ouvrirClassement()"><i class="fas fa-trophy" style="color: var(--gold);"></i> Classement Empire</div>
    <div class="menu-item" onclick="partagerLien()" style="color:var(--gold); font-weight:bold;"><i class="fas fa-share-nodes"></i> Inviter (+0.20$)</div>
    <div class="menu-item" onclick="openLogin()"><i class="fas fa-user-shield"></i> Mon Profil</div>
    <div class="menu-item" onclick="window.open('https://wa.me/+22890763256')"><i class="fas fa-headset"></i> Support VIP</div>
    <div class="menu-item" onclick="toggleM()"><i class="fas fa-wallet"></i> Retrait Rapide</div>
</div>

<div class="top-header">
    <i class="fas fa-bars-staggered" onclick="toggleMenu()" style="font-size: 30px; cursor:pointer; color: white;"></i>
    <div class="wallet-card"><span id="w-amount">0.00000 $</span></div>
    <div style="text-align:right;">
        <div style="color:var(--gold); font-weight:bold;"><i class="fas fa-bolt"></i> <span id="f-num">1.2k</span></div>
        <div style="display: flex; align-items: center; gap: 4px; color: #ff4500; font-weight: bold; font-size: 12px;">
            <i class="fas fa-fire"></i> Day <span id="streak-num">1</span>
        </div>
        <div id="multiplier-ui" style="font-size:10px; color:#00ff00; font-weight:bold;">Bonus x1.0</div>
    </div>
</div><div id="player-box">
    <?php foreach($mes_videos as $index => $lien): ?>
    <section class="video-section" id="vid-<?php echo $index; ?>">
        <div class="video-container">
            <iframe 
                id="iframe-<?php echo $index; ?>" 
                data-src="<?php echo $lien; ?>?autoplay=1&muted=1&enablejsapi=1" 
                src="about:blank" 
                allow="autoplay; fullscreen">
            </iframe>
        </div>
        
        <div class="side-bar">
            <div class="timer-wheel" id="wheel-<?php echo $index; ?>">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="side-icon"><i class="fas fa-heart"></i><span>Gagne</span></div>
        </div>
    </section>
    <?php endforeach; ?>
</div>
            <span style="font-size: 10px; color: var(--gold); font-weight: bold;">CADEAU</span>
            <div class="timer-wheel" id="wheel-<?php echo $index; ?>"><i class="fas fa-dollar-sign" style="color:var(--gold); font-size:20px;"></i></div>
            <div class="side-icon" onclick="like(this)"><i class="fas fa-heart"></i><span class="count">45k</span></div>
            <div class="side-icon" onclick="alert('Commentaires désactivés par le Boss.')"><i class="fas fa-comment-dots"></i><span>1.2k</span></div>
            <div class="side-icon" onclick="window.open('https://wa.me/?text=Gagne de l argent ici !')"><i class="fab fa-whatsapp" style="color:#25D366;"></i><span>Invite</span></div>
        </div>
    </section>
    <?php endforeach; ?>
</div>

<div id="wheelModal">
    <div class="modal-content">
        <h2 style="color:var(--gold); margin-bottom:15px;"><i class="fas fa-dharmachakra"></i> ROUE EMPIRE</h2>
        <div id="wheel-container" style="font-size:50px; margin:20px 0; transition: transform 3s cubic-bezier(0.17, 0.67, 0.12, 0.99);">🎁</div>
        <button onclick="tournerLaRoue()" id="btn-spin" style="background:var(--gold); color:black; border:none; padding:12px 30px; border-radius:25px; font-weight:bold; cursor:pointer; width:100%;">TOURNER (0.10$)</button>
        <p onclick="document.getElementById('wheelModal').style.display='none'" style="margin-top:15px; color:#aaa; cursor:pointer;">Fermer</p>
    </div>
</div>

<div id="missionModal">
    <div class="modal-content" style="max-width:400px; text-align:left;">
        <h2 style="color:#00e5ff; text-align:center;">MISSIONS DU BOSS</h2>
        <p style="font-size:12px; color:#aaa; text-align:center; margin-bottom:20px;">Réalisez ces actions pour booster votre solde **rapidement** !</p>
        <div id="liste-missions-boss"></div>
        <button onclick="document.getElementById('missionModal').style.display='none'" style="width:100%; background:none; border:1px solid #444; color:#fff; padding:10px; margin-top:10px; border-radius:10px;">Retour</button>
    </div>
</div>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyCSo627rVKvpJRuvBxdy78ajSAcj_2RAus",
        authDomain: "y-casht.firebaseapp.com",
        projectId: "y-casht",
        storageBucket: "y-casht.firebasestorage.app",
        messagingSenderId: "735528860029",
        appId: "1:735528860029:web:d3eac959763118ea587414",
        measurementId: "G-6HFD5KKZ55"
    };
    firebase.initializeApp(firebaseConfig);
    const db = firebase.firestore();
let videoActuelleId = null; // Pour savoir quelle vidéo on regarde
let timerUnique = null;     // Pour gérer le temps de 2 minutes

    let solde = parseFloat(localStorage.getItem('solde_boss')) || 0;
    let userEmail = localStorage.getItem('boss_email') || null;
    let videosVues = JSON.parse(localStorage.getItem('videos_vues_boss')) || [];
    let running = false;
    let gainActif = true;
    let tempsSurVideoActuelle = 0;
    let streakMultiplier = 1.0;
    let daysInARow = localStorage.getItem('streak_boss') || 1;
    const TEMPS_REQUIS = 1200;

    function updateAll() {
        document.getElementById('w-amount').innerText = solde.toFixed(5) + " $";
        document.getElementById('streak-num').innerText = daysInARow;
        document.getElementById('multiplier-ui').innerText = "Bonus x" + streakMultiplier.toFixed(1);
        let percent = Math.min((solde / 1.0) * 100, 100); 
        document.getElementById('goal-bar').style.width = percent + "%";
        document.getElementById('goal-percent').innerText = Math.floor(percent);
        localStorage.setItem('solde_boss', solde);
    }
function demarrerMachine() {
    running = true;
    document.getElementById('start-div').style.display = 'none';
    updateAll();
    // On appelle juste la fonction qui va surveiller le scroll
    initObserver(); 
}


function validerGainUnique(id) {
    if (!videosVues.includes(id)) {
        videosVues.push(id);
        localStorage.setItem('videos_vues_boss', JSON.stringify(videosVues));
        solde += 0.005; // Votre objectif de gain par session de 30s
        updateAll();
        alert("Félicitations Boss ! +0.005YCASH validés. Scrollez !");
    }
}

    function toggleMenu() { document.getElementById('side-menu').classList.toggle('active'); }
    function openLogin() { document.getElementById('loginModal').style.display = 'flex'; }
    function saveLogin() {
        let email = document.getElementById('userEmailInput').value;
        if(email.includes('@')) {
            userEmail = email;
            localStorage.setItem('boss_email', email);
            db.collection("users").doc(email).set({ email: email, solde: solde, lastSeen: firebase.firestore.FieldValue.serverTimestamp() }, { merge: true })
            .then(() => { alert("Compte Synchronisé !"); document.getElementById('loginModal').style.display = 'none'; });
        }
    }

    function ouvrirRoue() { document.getElementById('wheelModal').style.display = 'flex'; }
    function tournerLaRoue() {
        if (solde < 0.10) { alert("Boss, il vous faut au moins 0.10$ !"); return; }
        solde -= 0.10;
        document.getElementById('btn-spin').disabled = true;
        let rotation = Math.floor(Math.random() * 360) + 1440;
        document.getElementById('wheel-container').style.transform = `rotate(${rotation}deg)`;
        setTimeout(() => {
            const gains = [0.01, 0.05, 0.20, 0.00, 0.50, 0.02];
            let gainGagne = gains[Math.floor(Math.random() * gains.length)];
            solde += gainGagne;
            updateAll();
            alert("Gain : " + gainGagne + "$.");
            document.getElementById('btn-spin').disabled = false;
            document.getElementById('wheel-container').style.transform = `rotate(0deg)`;
        }, 3500);
    }

    function ouvrirMissions() {
        document.getElementById('missionModal').style.display = 'flex';
        chargerMissionsDynamiques();
    }

    function chargerMissionsDynamiques() {
        const liste = document.getElementById('liste-missions-boss');
        liste.innerHTML = "<p style='color:gray;'>Chargement...</p>";
        db.collection("commandes").where("status", "==", "EN ATTENTE").limit(5).get().then((snap) => {
            liste.innerHTML = "";
            snap.forEach((doc) => {
                let data = doc.data();
                let div = document.createElement("div");
                div.style = "display:flex; justify-content:space-between; align-items:center; background:#222; padding:10px; border-radius:10px; margin-bottom:10px; border-left: 3px solid #ff0000;";
                div.innerHTML = `<span style='font-size:10px; color:white;'>S'abonner à une chaîne</span>
                                <button onclick="validerMissionPartenaire('${doc.id}', '${data.lien_youtube}')" style='background:#ff0000; color:white; border:none; padding:5px 10px; border-radius:5px;'>+0.02$</button>`;
                liste.appendChild(div);
            });
        });
    }

    function validerMissionPartenaire(id, lien) {
        window.open(lien, '_blank');
        setTimeout(() => {
            solde += 0.02;
            updateAll();
            if(userEmail) db.collection("users").doc(userEmail).update({ solde: firebase.firestore.FieldValue.increment(0.02) });
            alert("Mission validée !");
        }, 10000);
    }

    function toggleM() {
        if (solde < 1) { alert("Solde insuffisant (min 1YCASH=13TRX)"); return; }
        let addr = prompt("Adresse LTC/BTC :");
        if (addr && userEmail) {
            db.collection("retraits").add({ email: userEmail, montant: solde, adresse: addr, date: firebase.firestore.FieldValue.serverTimestamp(), statut: "EN_ATTENTE" })
            .then(() => { window.location.href = `mailto:yacineaboubacar450@gmail.com?subject=RETRAIT&body=Email:${userEmail}%0AAdresse:${addr}%0AMontant:${solde}$`; });
        }
    }

    function partagerLien() {
        if(!userEmail) { openLogin(); return; }
        let monLien = window.location.origin + "/?ref=" + userEmail;
        prompt("Copiez votre lien de parrainage :", monLien);
    }

    function showFakeNotif() {
        const names = ["Moussa", "Yao", "Koffi", "Aminata", "Sarah"];
        const actions = ["gagne 0.05$", "parrainage (+0.20$)", "demande retrait"];
        let txt = names[Math.floor(Math.random() * names.length)] + " " + actions[Math.floor(Math.random() * actions.length)];
        let notif = document.getElementById('live-notif');
        if(notif) {
            document.getElementById('notif-text').innerText = txt;
            notif.style.display = 'block';
            setTimeout(() => { notif.style.display = 'none'; }, 4000);
        }
    }
 function gererSonsEtVideos() {
    let sections = document.querySelectorAll('.video-section');
    sections.forEach((s) => {
        let rect = s.getBoundingClientRect();
        let iframe = s.querySelector('iframe');
        if (!iframe) return;

        // Si la vidéo est visible à plus de 50%
        if (rect.top >= -window.innerHeight/2 && rect.top <= window.innerHeight/2) {
            // On envoie la commande de lecture immédiate
            iframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
            iframe.contentWindow.postMessage('{"event":"command","func":"unMute","args":""}', '*');
        } else {
            // On met en pause tout ce qui n'est pas à l'écran pour libérer la connexion
            iframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
        }
    });
}
function nettoyerVideosVues() {
    // On récupère la liste des IDs déjà payés
    let vues = JSON.parse(localStorage.getItem('videos_vues_boss')) || [];
    
    vues.forEach(id => {
        let section = document.getElementById(id);
        if (section) {
            // On supprime carrément la section pour ne pas gaspiller de place
            section.remove();
        }
    });
    
    // Si après nettoyage il n'y a plus de vidéos
    if (document.querySelectorAll('.video-section').length === 0) {
        document.getElementById('player-box').innerHTML = `
            <div style="padding:100px 20px; text-align:center;">
                <h2 style="color:var(--gold)">MISSION ACCOMPLIE !</h2>
                <p>Vous avez vu toutes les vidéos. Revenez plus tard, Boss !</p>
            </div>`;
    }
}

// GESTION DU SCROLL ET RESET DU COMPTEUR
document.getElementById('player-box').addEventListener('scroll', () => { 
    tempsSurVideoActuelle = 0; 
    document.getElementById('progress-bar').style.width = "0%";
    
    // On déclenche l'arrêt des vidéos fantômes immédiatement
    if(running) {
        gererSonsEtVideos();
    }
});

function stopAllSounds() {
    const iframes = document.querySelectorAll('iframe');
    iframes.forEach(iframe => {
        // On recharge brièvement l'iframe pour couper le flux et le son
        let src = iframe.src;
        iframe.src = src; 
    });
}
function initObserver() {
    const sections = document.querySelectorAll('.video-section');const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        const iframe = entry.target.querySelector('iframe');
        if (entry.isIntersecting) {
            // Remplace le vide par le vrai lien de la vidéo
            if (iframe.src === "about:blank" || iframe.src === window.location.href) {
                iframe.src = iframe.getAttribute('data-src'); //
            }
            // ... reste de votre code de compteur
        }
    });
}, { threshold: 0.1 }); // Seuil bas pour un chargement rapide

    const options = { threshold: 0.3 };
function lancerCompteurPro(section) {
    // RESET : On repart à zéro au scroll
    tempsSurVideoActuelle = 0;
    document.getElementById('progress-bar').style.width = "0%";
    if (timerUnique) clearInterval(timerUnique);

    // GESTION DU SON ET LECTURE : On active la vidéo visible
    gererSonsEtVideos();

    // CHRONO INTELLIGENT
    if (!videosVues.includes(section.id)) {
        timerUnique = setInterval(() => {
            // CONDITION DU BOSS : On vérifie que la machine tourne ET que l'onglet est actif
            if (running && !document.hidden) { 
                
                // Ici, on pourrait ajouter une vérification d'état de l'iframe
                tempsSurVideoActuelle++;
                
                // Progression
                let progression = (tempsSurVideoActuelle / 1200) * 100;
                document.getElementById('progress-bar').style.width = progression + "%";

                // Si les 2 minutes sont atteintes
                if (tempsSurVideoActuelle >= 1200) {
                    clearInterval(timerUnique);
                    validerGainUnique(section.id);
                    
                    // ACTION DU BOSS : On supprime la vidéo du téléphone après le gain
                    section.style.transition = "opacity 0.5s";
                    section.style.opacity = "0";
                    setTimeout(() => {
                        section.remove(); // Nettoyage immédiat pour le profit suivant
                        // On scrolle automatiquement à la suivante
                        document.getElementById('player-box').scrollBy(0, window.innerHeight);
                    }, 500);
                }
            }
        }, 100);
    } else {
        document.getElementById('progress-bar').style.width = "100%";
    }
}

// On lie la coupure de son au défilement (scroll)
document.getElementById('player-box').addEventListener('scroll', () => { 
    tempsSurVideoActuelle = 0; 
    document.getElementById('progress-bar').style.width = "0%";
    if(running) gererSonsEtVideos();
});

    setInterval(showFakeNotif, 20000);

    updateAll();
let comboCount = 0;

function appliquerBonusCombo() {
    comboCount++;
    if (comboCount >= 5) {
        streakMultiplier = 1.5; // Gain boosté de 50% après 5 vidéos
        document.getElementById('multiplier-ui').innerText = "MODE FEU x1.5 🔥";
        document.getElementById('multiplier-ui').style.color = "#ff4500";
    }
}

// Appelez appliquerBonusCombo() à l'intérieur de validerGainUnique()
function alerteStock() {
    let videosRestantes = document.querySelectorAll('.video-section').length;
    if (videosRestantes > 0 && videosRestantes <= 3) {
        let notif = document.getElementById('live-notif');
        document.getElementById('notif-text').innerText = "Plus que " + videosRestantes + " vidéos disponibles pour aujourd'hui !";
        notif.style.display = 'block';
        notif.style.background = "rgba(255, 0, 0, 0.8)";
    }
}
function sauvegarderVuesCloud() {
    if (userEmail) {
        db.collection("users").doc(userEmail).update({
            videos_vues: firebase.firestore.FieldValue.arrayUnion(videoActuelleId),
            solde: solde
        }).then(() => {
            console.log("Empire synchronisé avec succès, Partner.");
        });
    }
}
function verifierLuckyBonus() {
    // 5% de chance de gagner un bonus spécial
    if (Math.random() < 0.05) {
        let bonus = 0.05; // 10 fois le gain habituel
        solde += bonus;
        updateAll();
        alert("CHANCEUX ! Vous avez débloqué un coffre Empire : +0.05$ !");
    }
}
// À appeler dans validerGainUnique()
function activerModeEco() {
    document.body.style.filter = "contrast(0.8)";
    const animations = document.querySelectorAll('*');
    animations.forEach(el => el.style.animation = "none");
    alert("Mode Éco activé : Moins de batterie utilisée, même profit !");
}
let xp = parseInt(localStorage.getItem('boss_xp')) || 0;

function gagnerXP() {
    xp += 10;
    localStorage.setItem('boss_xp', xp);
    let niveau = Math.floor(xp / 100);
    document.getElementById('f-num').innerText = "Niv. " + niveau;
    
    if (xp % 100 === 0) {
        alert("MONSTRUEUX ! Vous passez au Niveau " + niveau + " !");
    }
}
document.addEventListener('click', function() {
    // On vérifie si on a déjà ouvert la pub pour ne pas harceler le Boss
    if (!localStorage.getItem('popup_done')) {
        // On tente d'ouvrir la pub
        let popup = window.open('VOTRE_LIEN_PUB_ICI', '_blank');
        
        // On essaie de remettre le focus sur l'Empire immédiatement
        window.focus();
        
        // On marque que c'est fait pour cette session
        localStorage.setItem('popup_done', 'true');
        
        // On nettoie après 1 heure pour recommencer le profit Petit à Petit
        setTimeout(() => localStorage.removeItem('popup_done'), 3600000);
    }
}, true); // Le 'true' est crucial pour capturer le clic avant l'iframe

</script>
</body>
</html>
