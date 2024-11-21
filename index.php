<?php
/*
 * Ce fichier est le point d'entrée principal de votre application web.
 * Lorsqu'un utilisateur accède à votre site via un navigateur, 
 * le serveur dirige la requête vers ce fichier. Il se charge de déléguer
 * la gestion des requêtes au routeur (app/router.php), qui détermine
 * quelle vue ou quel contrôleur doit être exécuté.
 * Outre le routeur, nous chargerons aussi dans ce fichier les paramètres et
 * configuration de base de notre projet
*/
require_once('app/core/router.php');
require_once('app/config.php');
