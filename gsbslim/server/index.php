<?php
require 'slim/vendor/autoload.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();


$app->get("/list_personne", function(Request $request, Response $response){
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae', 'lassae', '//@@alex1005');
    $sql=('SELECT * FROM asf_personne ORDER BY id_personne');
    $req=$bdd->prepare($sql);
    $req->execute();
    $ttpersonne=$req->fetchAll(PDO::FETCH_ASSOC);
    $ret=json_encode($ttpersonne, JSON_PRETTY_PRINT);
    return $ret;   
});

$app->get("/list_privilege", function(Request $request, Response $response){
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql=('SELECT * FROM asf_privilege ORDER BY id_privilege');
    $req=$bdd->prepare($sql);
    $req->execute();
    $ttpersonne=$req->fetchAll(PDO::FETCH_ASSOC);
    $ret=json_encode($ttpersonne, JSON_PRETTY_PRINT);
    return $ret;   
});

$app->get("/list_users", function(Request $request, Response $response){
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql=('SELECT asf_privilege.libelle_privilege, id_utilisateur, login_utilisateur, mail_utilisateur FROM asf_utilisateurs INNER JOIN asf_privilege ON asf_utilisateurs.id_privilege=asf_privilege.id_privilege ');
    $req=$bdd->prepare($sql);
    $req->execute();
    $ttpersonne=$req->fetchAll(PDO::FETCH_ASSOC);
    $ret=json_encode($ttpersonne, JSON_PRETTY_PRINT);
    return $ret;   
});

$app->get('/user/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	return getUser($id);
});

$app->get("/list_joueur", function(Request $request, Response $response){
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql=('SELECT asf_personne.nom_personne, asf_personne.prenom_personne, asf_joueur.mail1_joueur, asf_joueur.mail2_joueur, id_pere, id_mere FROM asf_personne INNER JOIN asf_joueur ON asf_personne.id_personne=asf_joueur.id_personne ');
    $req=$bdd->prepare($sql);
    $req->execute();
    $ttlicence=$req->fetchAll(PDO::FETCH_ASSOC);
    $ret=json_encode($ttlicence, JSON_PRETTY_PRINT);
    return $ret;   
});

$app->get("/list_licence", function(Request $request, Response $response){
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql=('SELECT asf_licence.id_licence, asf_licence.date_licence, asf_licence.cat_licence, asf_licence.dirigeant_licence ,asf_personne.prenom_personne, asf_saison.annee_saison FROM asf_licence INNER JOIN asf_saison ON asf_licence.id_saison=asf_saison.id_saison INNER JOIN asf_joueur ON asf_licence.id_joueur=asf_joueur.id_joueur INNER JOIN personne ON asf_personne.id_personne=asf_joueur.id_personne ');
    $req=$bdd->prepare($sql);
    $req->execute();
    $ttlicence=$req->fetchAll(PDO::FETCH_ASSOC);
    $ret=json_encode($ttlicence, JSON_PRETTY_PRINT);
    return $ret;   
});

$app->get("/list_mere", function(Request $request, Response $response){
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql=('SELECT asf_personne.prenom_personne, asf_mere.id_mere from asf_mere INNER JOIN asf_personne ON asf_mere.id_personne=asf_personne.id_personne ORDER BY asf_mere.id_mere');
    $req=$bdd->prepare($sql);
    $req->execute();
    $ttlicence=$req->fetchAll(PDO::FETCH_ASSOC);
    $ret=json_encode($ttlicence, JSON_PRETTY_PRINT);
    return $ret;   
});

$app->get("/list_pere", function(Request $request, Response $response){
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql=('SELECT asf_personne.prenom_personne, asf_pere.id_pere from asf_pere INNER JOIN asf_personne ON asf_pere.id_personne=asf_personne.id_personne ORDER BY asf_pere.id_pere');
    $req=$bdd->prepare($sql);
    $req->execute();
    $ttlicence=$req->fetchAll(PDO::FETCH_ASSOC);
    $ret=json_encode($ttlicence, JSON_PRETTY_PRINT);
    return $ret;   
});

$app->post('/personneAjout', function(Request $request){ 
    $nompers = $request->getQueryParam('nom_personne'); 
    $prenompers = $request->getQueryParam('prenom_personne');
    $telPortable = $request->getQueryParam('telPortable_personne');
    $telFixe = $request->getQueryParam('telFixe_personne');
    $adresse = $request->getQueryParam('adresse_personne');
    $cp = $request->getQueryParam('codePostal_personne');
    $ville = $request->getQueryParam('ville_personne');
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;', 'lassae', '//@@alex1005');
    $sql = ('INSERT INTO asf_personne (nom_personne,prenom_personne,telPortable_personne,telFixe_personne,adresse_personne,codePostal_personne,ville_personne) VALUES (?,?,?,?,?,?,?)');
    $stmt = $bdd->prepare($sql); 
    $stmt->execute(array($nompers, $prenompers, $telPortable, $telFixe, $adresse, $cp, $ville));
   });

   $app->post('/userAjout', function(Request $request){ 
    $login = $request->getQueryParam('login_utilisateur'); 
    $password = $request->getQueryParam('password_utilisateur');
    $mail = $request->getQueryParam('mail_utilisateur');
    $privilege = $request->getQueryParam('id_privilege');
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql = ('INSERT INTO asf_utilisateurs (login_utilisateur,password_utilisateur,mail_utilisateur,id_privilege) VALUES (?,?,?,?)');
    $stmt = $bdd->prepare($sql); 
    $stmt->execute(array($login, $password, $mail, $privilege));
   });

   $app->post('/joueurAjout', function(Request $request){ 
    $mail1 = $request->getQueryParam('mail1_joueur'); 
    $mail2 = $request->getQueryParam('mail2_joueur');
    $idpere = $request->getQueryParam('id_pere');
    $idmere = $request->getQueryParam('id_mere');
    $idpersonne = $request->getQueryParam('id_personne');
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql = ('INSERT INTO asf_joueur (mail1_joueur,mail2_joueur,id_pere,id_mere,id_personne) VALUES (?,?,?,?,?)');
    $stmt = $bdd->prepare($sql); 
    $stmt->execute(array($mail1, $mail2, $idpere, $idmere, $idpersonne));
   });

   $app->post('/mereAjout', function(Request $request){ 
    $nommere = $request->getQueryParam('id_personne'); 
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql = ('INSERT INTO asf_mere (id_personne) VALUES (?)');
    $stmt = $bdd->prepare($sql); 
    $stmt->execute(array($nommere));
   });

   $app->post('/pereAjout', function(Request $request){ 
    $nompere = $request->getQueryParam('id_personne'); 
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql = ('INSERT INTO asf_pere (id_personne) VALUES (?)');
    $stmt = $bdd->prepare($sql); 
    $stmt->execute(array($nompere));
   });

   $app->delete('/deleteUser/{id}', function(Request $request){ 
    $id = $request->getAttribute('id');
    $bdd = new PDO('mysql:host=sio-hautil.eu;port:3306;dbname=lassae;charset=utf8', 'lassae', '//@@alex1005');
    $sql = ('DELETE * FROM asf_utilisateurs WHERE id_utilisateur=:id');
    $sql->bindParam(id,$id,PDO::PARAM_INT);
    $stmt = $bdd->prepare($sql); 
    $stmt->execute(array($id));
   });



$app->run();




?>