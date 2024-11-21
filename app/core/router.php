<?php
/* Ce fichier est le routeur de votre application.
 * Son objectif est de : 
 * 1- Lire l'URL demandée par l'utilisateur. 
 * 2- Identifier la route correspondante (par exemple, /, /register, /login, etc.).
 * 3- Charger dynamiquement la vue associée à cette route.
*/


/* Lecture de l'adresse de la requête de l'utilisateur
 * Récupération du chemin en séparant le chemin pur de
 * l'URL des éventuels paramètres.
*/
$route = explode('?', $_SERVER['REQUEST_URI'])[0];

/* Définition des routes et leurs controlleurs associés
 * Nos Routes sont définis dans un tableau associatif
 * dans lequel chaque URI correspond a un tableau associatif
 * composé des trois élements suivants trois élements: 
 * path => (string) - assignez comme valeur le chemin vers le fichier de votre controller
 * action => (string) - assignez comme valeur la methode du controller a executer
 * name => (string) - assignez comme valeur le nom de la classe du controller
*/
$url_map = [

    '/' => ['path' => 'app/controllers/home.php', 'action' => 'index', 'name' => 'Home'],
];

if(array_key_exists($route, $url_map))
{
    /* Chargement du controller associé à la route demandée
     * Si la route existe dans le tableau, on charge le controller
     * correspondant et on exécute l'action spécifiée.
    */
    $controller = $url_map[$route]; // récuperation des propriétés controller
    require_once $controller['path']; // importer les fichier
    
    $instance = new $controller['name'](); // création d'une instance du controller
    $action = $controller['action']; // recupéation de l'action à executer
    $instance->$action(); // executer l'action correspondante
}
