<?php
/*
* 2025 MySpace sous licence MIT
* (c) Pornic Natation toutes personne utilisant ce code se doit de retirer toutes mentions faisant
* référence au nom de l'association en question
*
* --------------Informations du fichier--------------
* Planning/index.php
* Page principale pour afficher les événements du planning
* Inclus actions.php
* Utilise les feuilles de styles ../asset/icons.css, ../asset/main.css et ../asset/nav.css
* Utilise la session pour l'autentification 'user'
* Possible redirection vers ../login.php si non autentifié
* /!\ Aucunes informations sensible est exposée et aucune ne doit l'être
* --------------Historique des versions--------------
* v1.0 - 29/11/2025 Création du fichier et des fonctions de base
* v1.1 - 06/12/2025 Ajout des liens .ics pour chaque événement
 */ 
    require_once('actions.php');
    if (!isset($_SESSION['user'])) {
        header('Location: ../login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/icons.css">
    <link rel="stylesheet" href="asset/plan.css">
    <link rel="stylesheet" href="../asset/main.css">
    <link rel="stylesheet" href="../asset/nav.css">

    <title>Planning</title>
</head>
<body>
    <header>
        <span class="h1"><i class="icon1"></i> Planning</span>
        <span class="space"></span>
        <span><a href="../logout.php" class="icon1" aria-label="Se déconnecter"></a></span>
    </header>
        <nav>
            <ul>
                <li><a href="../"><i class="icon1"></i> <span>Accueil</span></a></li>
                <li><a href="../Perso"><i class="icon1"></i> <span>Adhésion</span></a></li>
                <li><a href="" class="active"><i class="icon1"></i> <span>Planning</span></a></li>
                <li><a href="../Sondage/"><i class="icon1"></i> <span>Sondages</span></a></li>
                <li><a href="../Boutique"><i class="icon1"></i> <span>Boutique</span></a></li>
            </ul>
        </nav>
        <div class="core">
            <?php if (getUpcomingEvents()==null): ?>
                Aucun événement à venir.
            <?php endif; ?>
            <?php foreach (getUpcomingEvents() as $event): ?>
            <div class="planningElement">
                <div class="attach1"></div>
                <div class="attach2"></div>
                <span class="head"><?= htmlspecialchars(date('d/m/y',$event['datep'])) ?></span>
                <span class="title"><?= htmlspecialchars($event['label']) ?></span>
                <span class="loc"><i class="icon1"></i> <?= htmlspecialchars($event['location']) ?></span>
                <span class="precise">
                    <span class="heure"><i class="icon1"></i> <?= htmlspecialchars(date('H:i',$event['datep'])) ?></span><br>
                    <span><a href="javascript:void(0)" id="link-<?= htmlspecialchars(intval($event['id'])) ?>" onclick="showDetails(<?= htmlspecialchars(intval($event['id'])) ?>)">Afficher les détails</a></span>
                    <span id="<?= htmlspecialchars(intval($event['id'])) ?>" style="display:none;"><?= nl2br(htmlspecialchars($event['note_private'])) ?>
                </span><br>
                <?php
                // Génération du lien .ics
                $start = date('Ymd\THis', $event['datep']);
                $end = date('Ymd\THis', $event['datep'] + 3600); // +1h par défaut
                $title = htmlspecialchars($event['label']);
                $desc = htmlspecialchars($event['note_private']);
                $location = htmlspecialchars($event['location']);
                $ics = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nBEGIN:VEVENT\r\nSUMMARY:$title\r\nDESCRIPTION:$desc\r\nLOCATION:$location\r\nDTSTART:$start\r\nDTEND:$end\r\nEND:VEVENT\r\nEND:VCALENDAR";
                $ics_url = 'data:text/calendar;charset=utf-8,' . rawurlencode($ics);
                ?>
                <a href="<?= $ics_url ?>" download="event-<?= intval($event['id']) ?>.ics"><i class="icon1"></i> Ajouter à mon calendrier</a>
            </div>
            
            <?php endforeach; ?>
        </div>
</body>
<script>
    function showDetails(id){
        const desc = document.getElementById(id);
        const link = document.getElementById('link-' + id);
        if (desc && desc.style && link) {
            const isHidden = desc.style.display === 'none';
            desc.style.display = isHidden ? 'block' : 'none';
            link.textContent = isHidden ? 'Masquer les détails' : 'Afficher les détails';
        } else {
            console.warn('Element ou style non trouvé pour id:', id);
        }
    }
</script>
</html>