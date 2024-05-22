<!DOCTYPE html>
<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "locationauto";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 //si on a soummer les informations 
if($_SERVER['REQUEST_METHOD'] == "POST"){
// Récupérer les données du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Requête pour vérifier les informations d'authentification du manager
$sql = "SELECT * FROM Managers WHERE nom_utilisateur='$username' AND mdp='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // L'utilisateur est authentifié, redirigez-le vers la page de gestion "manager.php"
    $_SESSION['username'] = $username;
    header("Location: gestion.php");
} else {
    // Si l'authentification échoue, redirigez l'utilisateur vers la page de connexion avec un message d'erreur
    $error = "Matricule ou mot de passe invalide.";
}
 }
$conn->close();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification Manager</title>
</head>
<body>
    <h2>Authentification Manager</h2>
    <form action="login.php" method="post">
        <label for="username">Nom d'utilisateur :</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Se connecter">
    </form>
    <?php
    // Afficher les messages d'erreur 
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
 <style>
        /* Définir les styles de base */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f2f2f2;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 50px;
            font-size: 3rem;
            color: #808080;
            text-shadow: 0 0 10px rgba(108, 92, 231, 0.6);
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        label {
            display: block;
            margin-bottom: -10px;
            font-size: 1.2rem;
            color: grey;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(108, 92, 231, 0.2);
        }

        input[type="text"],
        input[type="password"] {
            border-radius: 30px;
            border: 2px solid #ccc;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 30px;
            font-size: 1.1rem;
            color: #555;
            transition: border-color 0.3s ease-in-out;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #808080;
        }

        input[type="submit"] {
            background-color: #808080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: background-color 0.3s ease-in-out;
            position: relative;
            z-index: 1;
        }

        input[type="submit"]:hover {
            background-color: #666666;
        }

        p {
            text-align: center;
            margin-top: 30px;
            font-size: 1.1rem;
            color: #6c5ce7;
            text-shadow: 0 0 5px rgba(108, 92, 231, 0.2);
        }

        p.error {
            color: red;
        }

        /* Définir les animations */
        form {
            animation-name: rotate-scale-down;
            animation-duration: 1s;
            animation-timing-function: ease-out;
            animation-fill-mode: forwards;
        }

        @keyframes rotate-scale-down {
            0% {
                opacity: 0;
                transform: rotateX(90deg) scale(0.5);
                transform-origin: center top;
            }

            100% {
                opacity: 1;
                transform: rotateX(0deg) scale(1);
                transform-origin: center top;
            }
        }

        input[type="text"],
        input[type="password"] {
            animation-name: move-in;
            animation-duration: 1s;
            animation-timing-function: ease-out;
            animation-fill-mode: forwards;
        }

        @keyframes move-in {
            0% {
                opacity: 0;
                transform: translate3d(-100%, 0, 0);
            }

            100% {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        input[type="submit"] {
            animation-name: move-up;
            animation-duration: 1s;
            animation-timing-function: ease-out;
            animation-fill-mode: forwards;
        }

        @keyframes move-up {
            0% {
                opacity: 0;
                transform: translate3d(0, 100%, 0);
            }

            100% {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
    </style>
</body>
</html>


