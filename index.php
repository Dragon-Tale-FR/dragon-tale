<?php
// Configuration de la connexion à la base de données
$serverName = "DIMEH-C4\SQLEXPRESS"; // Remplacez par votre serveur
$connectionInfo = [
    "Database" => "DR2_Member",
    "UID" => "sa",
    "PWD" => "123456",
];

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Traitement du formulaire si soumis
$message = ''; // Variable pour afficher les messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        $message = "<p style='color: red;'>Les mots de passe ne correspondent pas.</p>";
    } else {
        // Préparer et exécuter la commande SQL
        $sql = "DECLARE @return_value int;
                EXEC @return_value = [dbo].[up_CreateMemberAccount]
                    @UID = 0,
                    @ID = ?,
                    @PW = ?,
                    @Gender = 0,
                    @Birthday = '2011-07-10 16:25:45.670';
                SELECT 'Return Value' = @return_value;";

        $params = [$pseudo, $password];
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            $message = "<p style='color: red;'>Erreur lors de l'inscription : " . print_r(sqlsrv_errors(), true) . "</p>";
        } else {
            $message = "<p style='color: green;'>Inscription réussie !</p>";
        }

        sqlsrv_free_stmt($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        /* Ajouter un fond d'image */
        body {
            background-image: url('http://www.localhost/img/background.jpeg'); /* Remplacez 'background.jpg' par le chemin vers votre image */
            background-size: cover; /* L'image couvre tout le fond */
            background-position: center; /* Centrer l'image */
            background-attachment: fixed; /* Fixer l'image lors du défilement */
            color: white; /* Changer la couleur du texte pour qu'il soit lisible sur un fond d'image */
            font-family: Arial, sans-serif; /* Police agréable pour le texte */
            display: flex;
            justify-content: center; /* Centrer horizontalement */
            align-items: flex-start; /* Aligner au top de la page */
            padding-top:20px; /* Ajouter un peu d'espace au-dessus */
            margin: 0;
            min-height: 100vh; /* S'assurer que la page prend la hauteur de l'écran */
        }

        /* Container de l'état du serveur */
        .status-container {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            flex-direction: column; /* Pour empiler les deux éléments */
            gap: 20px; /* Ajoute un espacement entre les deux */
        }

        .server-status {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
        }

        .server-status img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            border-radius: 50%; /* Rendre l'image ronde */
            object-fit: cover; /* S'assure que l'image s'ajuste bien dans le cercle */
        }

        /* Container de l'évènement */
        .event-status {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column; /* Aligner le texte verticalement */
            text-align: center; /* Centrer horizontalement le texte */
        }

        .event-status strong {
            margin-top: 5px; /* Ajouter un petit espace sous "Evènement en cours :" */
        }

        /* Conteneur principal centré */
        .container {
            text-align: center;
            width: 100%;
            max-width: 600px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            margin-top: 30px; /* Ajouter un peu d'espace au-dessus du formulaire */
        }

        h1 {
            margin-bottom: 20px;
        }

        /* Style des boutons en haut */
        .nav-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .nav-buttons button {
            background-color: rgba(76, 175, 80, 0.7); /* Vert #4CAF50 avec 70% de transparence */
            color: white; /* Texte blanc */
            border: none;
            padding: 12px 20px;
            margin: 0 10px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 8px; /* Arrondir les bords du bouton */
            transition: background-color 0.3s ease; /* Transition douce lors du changement de couleur */
        }

        .nav-buttons button:hover {
            background-color: rgba(76, 175, 80, 1); /* Vert plus intense lors du survol */
        }

        /* Bannière image */
        .banner {
            margin-bottom:1cm; /* Ajouter un écart d'1 cm sous la bannière */
        }

        .banner img {
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
        }

        /* Formulaire */
        form {
            margin-bottom: 20px;
            width: 100%; /* Assurez-vous que le formulaire utilise toute la largeur disponible */
        }

        label, input, button {
            width: 100%;  /* Fixe la largeur des éléments à 100% du conteneur */
            margin-bottom: 10px;
            display: block;
        }

        input, button {
            padding: 10px;
            font-size: 16px;
            box-sizing: border-box; /* Inclut le padding dans la largeur totale */
            border-radius: 8px; /* Arrondir les bords des champs de saisie et du bouton */
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Cadre des news */
        .news-box {
            margin-top: 20px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Message de succès ou d'erreur */
        .message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .message.success {
            background-color: #4CAF50;
            color: white;
        }
        .message.error {
            background-color: #F44336;
            color: white;
        }

        /* Alignement de la date et nom */
        .news-post .author {
            text-align: left;
            font-size: 14px;
            margin-bottom: 5px;
        }

        /* Classe pour le texte "Nouvelle" */
        .new-title {
            text-align: left;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: normal; /* Enlever le gras sur tout le texte */
        }

        /* Appliquer le gras seulement à "Nouvelle" */
        .new-title strong {
            font-weight: bold; /* Appliquer le gras uniquement à "Nouvelle :" */
        }

        /* Ajout de l'arrondi pour l'image de l'événement */
        .event-status img {
            border-radius: 15px; /* Arrondir les bords de l'image */
        }
    </style>
</head>
<body>
    <!-- Container de l'état du serveur -->
    <div class="status-container">
        <div class="server-status">
            <img src="http://www.localhost/img/server.jpg" alt="Serveur Icon"> <!-- Remplacez l'URL de l'icône par la vôtre -->
            Etat du serveur : <strong> ON</strong>
        </div>

        <!-- Container de l'évènement -->
        <div class="event-status">
            <strong>( Evènement en cours )</strong>
            <!-- Ajouter une image ici -->
            <img src="http://www.localhost/img/hiver.jpg" alt="Evènement Icon" style="width: 190px; height: 120px; margin: 10px 0;">
            Arrivée de la bêta !
        </div>
    </div>

    <div class="container"> 
        <!-- Image de bannière --> 
        <div class="banner"> 
            <img src="http://www.localhost/img/zzz.png" alt="Dragon-Tale Banner"> 
        </div> 
        <!-- Boutons de navigation --> 
        <div class="nav-buttons"> 
            <button onclick="window.location.href='https://download2280.mediafire.com/zo7e1klq4vtgyvz1O8yQCnni4u2CRIOvylYojZ2ad3guJ-hOMLYn0laiQI5I9OlkDHTv9M6izmNJlw0DKsvel-N8FqhsYFkFI1L6pPWxl75zlGx46AiZS27nndtZSPLEOO2SYq37p5LUZMtYQMcj6k4iit-c41U40EbJkxGQjwOa5A/4qcu3hrsj64ml0y/Dragon-Tale+%28FR%29.rar'">Téléchargements</button> 
            <button onclick="window.location.href='http://www.localhost/index.php'">Inscriptions</button> 
            <button onclick="window.location.href='http://www.localhost/index.php'">Boutique</button> 
        </div> 
        <?php if ($message): ?> 
        <div class="message <?php echo strpos($message, 'Erreur') === false ? 'success' : 'error'; ?>"> 
            <?php echo $message; ?> 
        </div> 
        <?php endif; ?> 
        <form method="post" action=""> 
            <label for="pseudo">Pseudo :</label> 
            <input type="text" id="pseudo" name="pseudo" required><br> 
            <label for="password">Mot de passe :</label> 
            <input type="password" id="password" name="password" required><br> 
            <label for="confirm_password">Confirmer le mot de passe :</label> 
            <input type="password" id="confirm_password" name="confirm_password" required><br> 
            <button type="submit">S'inscrire</button> 
        </form> 
        <!-- Cadre des News --> 
        <div class="news-box"> 
            <h3>News | La bêta est arrivée !</h3> 
            <div class="news-post"> 
                <img src="http://www.localhost/img/brahim.png" alt="Profile Photo" style="width: 50px; height: 50px; border-radius: 50%; float: left; margin-right: 10px;"> 
                <div class="author"><strong>Brahim</strong> - 23/12/2024</div> 
                <div class="new-title"><strong>Nouvelle :</strong> L'ouverture du jeu en version beta est arrivée ! Découvrez les nouvelles fonctionnalités et corrections de bugs.</div> 
                <img src="http://www.localhost/img/hiver.jpg" alt="News Image" style="width: 100%; max-width: 400px; margin-top: 10px; border-radius:10px;">
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Fermeture de la connexion
sqlsrv_close($conn);
?>