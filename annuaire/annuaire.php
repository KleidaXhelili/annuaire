<?php
//inclusion de la connexion
require_once('init.php');

//Suppresion (action : del)
if(isset($_GET['action']) && $_GET['action'] == 'del' && !empty($_GET['id']) && is_numeric($_GET['id'])){

    executeRequete("DELETE FROM annuaire WHERE id_annuaire=:id", array('id'=>$_GET['id']));
    header('location:' .$_SERVER['PHP_SELF']);
    exit();
}


//Chargement d'un contact pour édition (action :edit)
if(isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id']) && is_numeric($_GET['id'])){
    //je vais chercher les informations du contact par son id
    $edit = executeRequete("SELECT*FROM annuaire WHERE id_annuaire = :id", array('id' => $_GET['id']));
    // si j'ai bien une ligne en retour
    if($edit->rowCount() == 1){
        //je charge les infos de la table dan un tableau associatif $contact
        $contact = $edit->fetch();
    }
}

//Traitement du POST
if(!empty($_POST)){

    //contrôles
    $erreurs = array();

    //champs vides
    $champsvides = 0;
    foreach($_POST as $valeur){
        if(empty($valeur)) $champsvides++;
    }

    if($champsvides>0){
        $erreurs[] = "Il manque $champsvides information(s)";
    }

    //longueur et nature du numéro de téléphone
    if(iconv_strlen($_POST['telephone']) != 10 || !is_numeric($_POST['telephone'])){
        $erreurs[] = "Numéro de téléphone incorrect : 10 chiffres requis";
    }

    //longueur et nature du code postal
    if(iconv_strlen($_POST['codepostal']) != 5 || !is_numeric($_POST['codepostal'])){
        $erreurs[] = "Code postal : 5 chiffres requis";
    }


    if(empty($erreurs)){

        if(!empty($_GET['id'])){
            //mode update car j'ai un numéro de contact dans l'url
            $requete = "UPDATE annuaire SET nom = :nom, prenom = :prenom, telephone = :telephone, profession = :profession, ville = :ville, codepostal = :codepostal, adresse = :adresse, date_de_naissance = :date_de_naissance, sexe = :sexe, description = :description WHERE id_annuaire = :id";
            $_POST['id'] = $_GET['id'];
        }else{
            //mode insertion
            $requete = "INSERT INTO annuaire VALUES (NULL, :nom, :prenom, :telephone, :profession, :ville, :codepostal, :adresse, :date_de_naissance, :sexe, :description)";
        }
        executeRequete($requete, $_POST);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Annuaire</title>
</head>
<body>
    
    <header class="expand-lg fixed-top bg-dark text-center">
        <h1>ANNUAIRE</h1>
    </header>

    <main>
        <div class="container pt-5 pb-5">
            <h2>Entrez vos coordonnées</h2>
            <?php if (!empty($erreurs)) : ?>
                    <div class="alert alert-danger"><?= implode('<br>', $erreurs) ?></div>
            <?php endif ?>
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="nom">Nom</label><br>
                        <input type="text" name="nom" id="nom" placeholder="Saisissez ici">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="nom">Prénom</label><br>
                        <input type="text" name="prenom" id="prenom" placeholder="Saisissez ici">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="nom">Téléphone</label><br>
                        <input type="number" name="telephone" id="telephone" placeholder="Saisissez ici">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="profession">Profession</label><br>
                        <input type="text" name="profession" id="profession"  placeholder="Saisissez ici">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="adresse">Adresse</label><br>
                        <input type="text" name="adresse" id="adresse" placeholder="Saisissez ici">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="codepostal">Code Postal</label><br>
                        <input type="number" name="codepostal" id="codepostal" placeholder="Saisissez ici">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ville">Ville</label><br>
                        <input type="text" name="ville" id="ville" placeholder="ville">
                    </div>                
                    <div class="form-group col-md-3">
                        <label for="date_de_naissance">Date de naissance</label><br>
                        <input type="date" name="date_de_naissance" id="date_de_naissance" placeholder="Saisissez ici">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="sexe">Sexe</label><br>
                        <select name="sexe">
                            <option value="m">Homme</option>
                            <option value="f">Femme</option>
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="description">Description</label><br>
                        <textarea name="description" id="description" cols="30" rows="10" placeholder="Saisissez ici"></textarea>
                    </div>
                </div>
                <div class="row">
                <button type="submit">Envoyer</button>
                </div>                
            </form>           
        </div>
        <div class="table table-bordered table-striped table-responsive-md">
            <h2>Liste</h2>
            <table>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Profession</th>
                    <th>Ville</th>
                    <th>Code postal</th>
                    <th>Adresse</th>
                    <th>Date de naissance</th>
                    <th>Sexe</th>
                    <th>Description</th>
                    <th colspan="2">Actions</th>
                </tr>

                <?php
                $resultats = executeRequete("SELECT * FROM annuaire");
                while($contact = $resultats->fetch()) :
                ?>
                <tr>
                    <td><?= $contact['id_annuaire'] ?></td>                    
                    <td><?= $contact['nom'] ?></td>                    
                    <td><?= $contact['prenom'] ?></td>                    
                    <td><?= $contact['telephone'] ?></td>                    
                    <td><?= $contact['profession'] ?></td>                    
                    <td><?= $contact['ville'] ?></td>                    
                    <td><?= $contact['codepostal'] ?></td>                    
                    <td><?= $contact['adresse'] ?></td>                    
                    <td><?= $contact['date_de_naissance'] ?></td>                    
                    <td><?= $contact['sexe'] ?></td>                    
                    <td><?= $contact['description'] ?></td>                    
                    <td><a href="?action=del&id=<?= $contact['id_annuaire'] ?>" class="confirm"> &#x1F5D1; </a></td>                    
                    <td><a href="?action=edit&id=<?= $contact['id_annuaire'] ?>">&#x270F;</a></td>                    
                </tr>
                <?php
                endwhile;
                ?>
                
            </table>

        </div>
    </main>
    <footer class="container-fluid bg-dark text-light text-center py-4" >
        <div class="row">
            <div class="col">
                &copy <?= date("Y")?> -Annuaire
            </div>
        </div>
    </footer>
    <!--script bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>