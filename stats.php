<?php
// stats.php

// --- CONFIGURATION ---
$fichier_csv = 'stats_campagne.csv';
$mot_de_passe_admin = "habib"; 

// --- SECURITE SIMPLE ---
// On demande le mot de passe si on n'est pas connecté
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_PW'] !== $mot_de_passe_admin) {
    header('WWW-Authenticate: Basic realm="Zone Admin Sensibilisation"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Accès refusé. Mot de passe incorrect.';
    exit;
}

// --- TRAITEMENT DES DONNEES ---
$lignes = [];
$total_clics = 0;

if (file_exists($fichier_csv)) {
    // Lire le fichier dans un tableau, on ignore les lignes vides
    $raw_lines = file($fichier_csv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Inverser pour voir les derniers clics en premier
    $raw_lines = array_reverse($raw_lines);
    
    $total_clics = count($raw_lines);
    
    foreach ($raw_lines as $line) {
        // On sépare les données par le point-virgule (format défini dans login.php)
        $lignes[] = explode(';', $line);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats Campagne Phishing - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-radius: 5px; }
        
        h1 { color: #333; border-bottom: 2px solid #ff6600; padding-bottom: 10px; }
        
        .compteur-box {
            background-color: #ff6600;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .compteur-chiffre { font-size: 3em; font-weight: bold; }
        .compteur-texte { font-size: 1.2em; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #333; color: white; }
        tr:hover { background-color: #f1f1f1; }
        
        .tag-mobile { background: #e7f3fe; color: #3182ce; padding: 2px 6px; border-radius: 4px; font-size: 0.8em; }
        .tag-pc { background: #e6fffa; color: #38b2ac; padding: 2px 6px; border-radius: 4px; font-size: 0.8em; }
    </style>
</head>
<body>

<div class="container">
    <h1>📊 Tableau de bord - Campagne SIC</h1>

    <div class="compteur-box">
        <div class="compteur-chiffre"><?php echo $total_clics; ?></div>
        <div class="compteur-texte">Personnes piégées (Clics)</div>
    </div>

    <h3>Détail des connexions (du plus récent au plus ancien)</h3>
    
    <?php if ($total_clics > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date & Heure</th>
                    <th>Adresse IP</th>
                    <th>Identifiant Saisi</th>
                    <th>Appareil (User Agent)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $data): ?>
                    <?php 
                        // Sécurisation basique à l'affichage
                        $date = htmlspecialchars($data[0] ?? '?');
                        $ip = htmlspecialchars($data[1] ?? '?');
                        $login = htmlspecialchars($data[2] ?? '?');
                        $ua = htmlspecialchars($data[3] ?? '');
                        
                        // Détection simple Mobile vs PC pour l'affichage
                        $type_device = (stripos($ua, 'Mobile') !== false) ? '<span class="tag-mobile">Mobile</span>' : '<span class="tag-pc">PC/Mac</span>';
                    ?>
                    <tr>
                        <td><?php echo $date; ?></td>
                        <td><?php echo $ip; ?></td>
                        <td style="font-weight:bold; color:#d32f2f;"><?php echo $login; ?></td>
                        <td>
                            <?php echo $type_device; ?> <br>
                            <small style="color:#999; font-size:0.8em;"><?php echo substr($ua, 0, 50); ?>...</small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; padding: 20px; color: #666;">Aucune donnée enregistrée pour le moment.</p>
    <?php endif; ?>

</div>

</body>
</html>