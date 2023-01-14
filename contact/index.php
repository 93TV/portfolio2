<?php

// Show all errors (for educational purposes)
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Constanten (connectie-instellingen databank)
define('DB_HOST', 'localhost');
define('DB_USER', 'thibaultviaene');
define('DB_PASS', 'Azerty123!');
define('DB_NAME', 'thibault_viaene_portfolio');

date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection Error: ' . $e->getMessage();
    exit;
}

$name = isset($_POST['name']) ? (string)$_POST['name'] : '';
$message = isset($_POST['message']) ? (string)$_POST['message'] : '';
$email = isset($_POST['email']) ? (string)$_POST['email'] : '';
$via = isset($_POST['via']) ? (array)$_POST['via'] : '';
$msgName = '';
$msgMessage = '';
$msgEmail = '';
$msgVia = '';

// form is sent: perform formchecking!
if (isset($_POST['btnSubmit'])) {

    $allOk = true;

// name not empty
    if (trim($name) === '') {
        $msgName = 'Fill in your name please';
        $allOk = false;
    }

    if (trim($message) === '') {
        $msgMessage = 'Fill in a message please';
        $allOk = false;
    }

    if (trim($email) === '') {
        $msgEmail = 'Fill in an email address please';
        $allOk = false;
    }

    if ($via === '') {
        $msgVia = 'Please make a choice';
        $allOk = false;
    } else {
        foreach ($via as $option) {
            if ($option === 'friends') {
                $friends = true;
            }
            if ($option === 'family') {
                $family = true;
            }
            if ($option === 'other') {
                $other = true;
            }
        }
    }


// end of form check. If $allOk still is true, then the form was sent in correctly
    if ($allOk) {
        $implVia = implode('||', $via);
// build & execute prepared statement
        $stmt = $db->prepare('INSERT INTO messages2 (Name, Email, Message,Via, Added_on) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(array($name, $email, $message, $implVia, (new DateTime())->format('Y-m-d H:i:s')));

// the query succeeded, redirect to this very same page
        if ($db->lastInsertId() !== 0) {
            header('Location: formchecking_thanks.php?name=' . urlencode($name));
            exit();
        } // the query failed
        else {
            echo 'Databankfout.';
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://unpkg.com/@csstools/normalize.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,300;0,400;0,500;0,700;0,800;1,400;1,800&display=swap"
          rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/profilepicture%20-face.png">
    <title>Contact</title>
</head>
<body>
<header>
    <nav>
        <a href="../">Thibault Viaene</a>
        <ul>
            <li><a title="Home" href="../">Home</a></li>
            <li><a title="About" href="../about">About</a></li>
            <li><a title="Projects" href="../projects">Projects</a></li>
            <li><a title="Blog" href="../blog">Blog</a></li>
            <li><a title="Contact" href="../contact" class="active">Contact</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="containerContact">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h1>Let's get in touch!</h1>
            <div>
                <div class="namemail">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlentities($name); ?>"
                           class="input-text"/>
                    <span class="message_error"><?php echo $msgName; ?></span>
                </div>
                <div class="namemail">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlentities($email); ?>"
                           class="input-text"/>
                    <span class="message_error"><?php echo $msgEmail; ?></span>
                </div>
            </div>
            <div>
                <fieldset>
                    <legend>How did you find me?</legend>
                    <div>
                        <input <?php if (isset($friends)){ ?>checked="checked"<?php } ?> type="checkbox" name="via[0]"
                               id="friends" value="friends">
                        <label for="friends">Friends</label>
                    </div>
                    <div>
                        <input <?php if (isset($family)) { ?> checked="checked" <?php } ?> type="checkbox"
                                                                                           name="via[1]" id="family"
                                                                                           value="family">
                        <label for="family">Family</label>
                    </div>
                    <div>
                        <input <?php if (isset($other)) { ?> checked="checked" <?php } ?> type="checkbox" name="via[2]"
                                                                                          id="other" value="other">
                        <label for="other">Other</label>
                    </div>
                </fieldset>
                <span class="message_error"><?php echo $msgVia; ?></span>
            </div>

            <div class="message">
                <label for="message">Message:</label>
                <textarea name="message" id="message" rows="5"
                          cols="40"><?php echo htmlentities($message); ?></textarea>
                <span class="message_error"><?php echo $msgMessage; ?></span>
            </div>

            <input class="button" type="submit" id="btnSubmit" name="btnSubmit" value="Send"/>

        </form>
    </div>
</main>
<footer>
    <p> &copy; 2022 Thibault Viaene </p>
    <p>Ghent, Belgium</p>
    <a title="GitHub" href="https://github.com/93TV">GitHub</a>
</footer>
</body>
</html>