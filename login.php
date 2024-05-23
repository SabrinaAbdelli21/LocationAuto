<?php
session_start();

include 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['connexion'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT N_client FROM Clients WHERE nom_utilisateur = ? AND mdp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['N_client'] = $row['N_client'];
        header("Location: acceuil.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_permis = $_POST['date_permis'];
    $age = $_POST['age'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql_check_user = "SELECT N_client FROM Clients WHERE nom_utilisateur = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param('s', $username);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    if ($result_check_user->num_rows > 0) {
        $error = "Ce nom d'utilisateur est déjà utilisé.";
    } else {
        $sql_insert_user = "INSERT INTO Clients (nom, prenom, date_permis, age, nom_utilisateur, mdp) 
                            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->bind_param('ssssss', $nom, $prenom, $date_permis, $age, $username, $password);
        
        if ($stmt_insert_user->execute()) {
            $user_id = $stmt_insert_user->insert_id;

            $_SESSION['N_client'] = $user_id;
            header("Location: acceuil.php");
            exit();
        } else {
            $error = "Erreur lors de l'inscription: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div id="login-container">
            <form id="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h2>Connexion</h2>
                <?php if (isset($error)) echo "<p>$error</p>"; ?>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="hidden" name="connexion" value="true">
                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore inscrit ? <a href="#" id="show-signup">Inscrivez-vous ici</a></p>
        </div>
        
        <div id="signup-container" style="display: none;">
            <form id="signup-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h2>Inscription</h2>
                <?php if (isset($error)) echo "<p>$error</p>"; ?>
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="date" name="date_permis" placeholder="Date de délivrance du permis" required>
                <input type="number" name="age" placeholder="Âge" required>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="hidden" name="inscription" value="true">
                <button type="submit">S'inscrire</button>
            </form>
            <p>Déjà inscrit ? <a href="#" id="show-login">Connectez-vous ici</a></p>
        </div>
    </div>

    <script>
        document.getElementById('show-signup').addEventListener('click', function() {
            document.getElementById('login-container').style.display = 'none';
            document.getElementById('signup-container').style.display = 'block';
        });

        document.getElementById('show-login').addEventListener('click', function() {
            document.getElementById('login-container').style.display = 'block';
            document.getElementById('signup-container').style.display = 'none';
        });
    </script>
</body>
</html>
