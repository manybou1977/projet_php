<?php require_once 'inc/header.php';


if (!isset($_SESSION['utilisateur']) || (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role']!=='ROLE_ADMIN')){
    header('location:./');
    exit();

}
//

// nécessaire au fonctionnement d'un formulaire:
// attribut action sur la balise form inutile à renseigner si le traitement à lieu dans la même page
// attribut method="post" toujours sur la balise form pour remplir la superglobale $_POST sinon par défaut le formulaire transiterait en méthode get et remplirait donc la superglobale $_GET
// si le formulaire contient un input type file, l'attribut enctype="multipart/form-data" afin de remplir la superglobale $_FILES
// un name renseigné est obligatoire sur tout les champs de formulaire (input, textarea, select )
// enfin il faut impérativement un button type="submit" pour déclencher le chargement de nos superglobales

// condition impérative pour le traitement d'un formulaire
if (!empty($_POST)){ // on utilise !empty plutôt que isset() car $_POST existe toujours mais par contre ne sera rempli de données qu'une fois le bouton submit pressé.

//die(var_dump($_FILES));

if (!empty($_FILES['photo']['name'])){

    $photo_bdd=date_format(new DateTime(),'YmdHis').'-'.uniqid().$_FILES['photo']['name'];

    if (!file_exists('upload')){
        mkdir('upload', 777);
    }
        copy($_FILES['photo']['tmp_name'], 'upload/' . $photo_bdd);

    $requete=executeRequete("INSERT INTO produit(prix, titre, description, photo) VALUES (:prix, :titre, :description, :photo)", array(
            ':prix'=>$_POST['prix'],
            ':titre'=>$_POST['titre'],
            ':description'=>$_POST['description'],
            ':photo'=>$photo_bdd

    ));

    $_SESSION['messages']['success'][]='produit ajouté avec succès';

    header('location:./gestionProduit.php');
    exit();
}










}// fin de condition de soumission du formulaire









?>

<form action=""method="post" enctype="multipart/form-data">
    <fieldset>

        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label mt-4">TITRE</label>
            <input name="titre" type="text" class="form-control" id="exampleInputPassword1" placeholder="TITRE">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label mt-4">TITRE</label>
            <input name="prix" type="number" class="form-control" id="exampleInputPassword1" placeholder="PRIX">
        </div>


        <div class="form-group">
            <label for="exampleTextarea" class="form-label mt-4">DESCRIPTION</label>
            <textarea name="description" class="form-control" id="exampleTextarea" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="formFile" class="form-label mt-4">PHOTO</label>
            <input name="photo" class="form-control" type="file" id="formFile">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
 </fieldset>
</form>





<?php require_once 'inc/footer.php'; ?>

