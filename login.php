<?php
// Fichier : login.php

// 1. On définit où on stocke les stats
$fichier_csv = 'stats_campagne.csv';

// 2. On récupère les infos
$date = date('d/m/Y H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
// On récupère le login envoyé par le formulaire (name="login")
$login = isset($_POST['login']) ? $_POST['login'] : 'Inconnu';
$user_agent = $_SERVER['HTTP_USER_AGENT']; // Type d'appareil (PC/Mobile)

// 3. On prépare la ligne à écrire (Format CSV : Date;IP;Login;Appareil)
// Note : On n'enregistre PAS le mot de passe par éthique et sécurité.
$ligne = "$date;$ip;$login;$user_agent\n";

// 4. On écrit dans le fichier
// FILE_APPEND : On ajoute à la fin du fichier sans effacer le reste
file_put_contents($fichier_csv, $ligne, FILE_APPEND | LOCK_EX);

// 5. ETAPE CRUCIALE : La redirection vers la sensibilisation
header("Location: sensibilisation.html");
exit();
?>