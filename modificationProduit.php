<?php require_once 'inc/header.php';

if (!isset($_SESSION['utilisateur']) || (isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']['role']!=='ROLE_ADMIN')){
    header('location:./');
    exit();

}

if (!empty($_POST)){

    if (!empty($_FILES['photoModif']['name'])){

        $photo_bdd=date_format(new DateTime(),'YmdHis').'-'.uniqid().$_FILES['photoModif']['name'];


        copy($_FILES['photoModif']['tmp_name'], 'upload/' . $photo_bdd);
        unlink('upload/' .$_POST['photo']);


    }else{

        $photo_bdd=$_POST['photo'];

    }
        $requete = executeRequete("UPDATE produit SET prix=:prix, titre=:titre, description=:description, photo=:photo WHERE id=:id", array(
            ':prix' => $_POST['prix'],
            ':titre' => $_POST['titre'],
            ':description' => $_POST['description'],
            ':photo' => $photo_bdd,
            ':id' =>$_POST ['id']
        ));

   var_dump($_POST);

    $_SESSION['messages']['success'][]='produit modifié avec succès';
    header('location:./gestionProduit.php');
    exit();

}// fin de condition de soumission du formulaire


if (isset($_GET['id'])){// on verifie si il existe un parametre en get passé dans l'url nomé 'id'
    // un passage en get est défini par un formulaire dont l'action ne serait soit non renseignée, soit défini en get. Sinon on utilise pour faire transiter des informations d'une page à une autre via l'url par le biais d'un lien dans lequel on va déclarer notre passage en get avec ? .
    //ainsi l'url aura la forme: https://www.nondedomaine.fr/grdtionProduit?id=2&action=modifier
    // dans cette exemple dans la page gestionProduit.php on aurait à charger.


    $r=executeRequete("SELECT * FROM produit WHERE id=:id", array(
            ':id'=>$_GET['id']

     ));
    $produit=$r->fetch(PDO::FETCH_ASSOC);

    //var_dump($produit);


}






?>

<form action=""  method="post" enctype="multipart/form-data">
    <fieldset>

        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label mt-4">TITRE</label>
            <!-- $produit ['titre'] ?? ''  est utilisable depuis PHP7, il signifie , si $produit ['titre'] n'existe pas, affiche moi comme valeur par défaut '' (du vide)    -->
            <input name="titre" value="<?= $produit['titre'] ?? ''; ?>" type="text" class="form-control" id="exampleInputPassword1" placeholder="TITRE">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1" class="form-label mt-4">PRIX</label>
            <input name="prix" value="<?= $produit['prix'] ?? ''; ?>" type="number" class="form-control" id="exampleInputPassword1" placeholder="PRIX">
        </div>


        <div class="form-group">
            <label for="exampleTextarea" class="form-label mt-4">DESCRIPTION</label>
            <textarea name="description" class="form-control" id="exampleTextarea" rows="3"><?= $produit['description'] ?? ''; ?></textarea>
        </div>

        <input type="hidden" name="id" value="<?=  $produit['id'] ?? 0; ?>">
        <input type="hidden" name="photo" value"<?=  $produit['photo']; ?>">

        <div class="form-group">
            <label for="formFile" class="form-label mt-4">PHOTO</label>
            <input name="photoModif" class="form-control" type="file" id="formFile">
            <img src="<?= 'upload/' .$produit['photo']; ?>" width="250" alt="">
        </div>
        <button type="submit" class="btn btn-primary">VALIDER</button>
    </fieldset>
</form>





<?php

require_once 'inc/footer.php'; ?>


