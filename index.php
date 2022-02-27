<?php
require_once "./libdb/searchAccounts.php";
require_once "./libdb/searchVideos.php";

$err     = null;
$errUser = false;
$errPass = false;

// userStatus: 0 (sin estado) | 1 (sesión iniciada) | 2 (mail verificado) | 3 (email sin verificar)
if (isset($_COOKIE[session_name()])) {
  session_start();
  if ($_SESSION['userStatusCode'] == 1) {

    //este código es anterior que te redirige al formulario de video 
    //si no hay videos subidos a la plataforma
    //
    /*
    if(tieneVideos($_SESSION['iduser'])){
      header("Location: ./web/mainpage.php");
      exit;
    }
    header("Location: ./web/videoform.php");
    */
    header("Location: ./web/mainpage.php");

    exit;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['user']) && isset($_POST['psw'])) {
    $userPOST = filter_input(INPUT_POST, 'user');
    $passPOST = filter_input(INPUT_POST, 'psw');

    if ($_POST['user'] != '' && $_POST['psw'] != '') {
      $usuari['username'] = $userPOST;
      $usuari['pass'] = $passPOST;

      if (!searchUser($usuari)) {
        $err = true;
        //això és per posarho al primer input
        $user = $userPOST;
      } else {
        if (!isset($_COOKIE[session_name()])) {
          session_start();
        }
        $_SESSION['userStatusCode'] = 1;
        $_SESSION['username'] = $usuari['username'];
        $_SESSION['iduser'] = $usuari['iduser'];
        //Redirecció a la pràgina principal
        header("Location: ./web/mainpage.php");
        exit;
      }
    }
  }
}
?>

<!DOCTYPE html>

<?php include "./includes/indexHead.php" ?>

<body>
  <video autoplay muted loop id="backVideo">
    <source src="./media/friends.mp4" type="video/mp4">
  </video>

  <div class="col-4 lateral-panel">
    <a href="#" class="link">
      <h1 class="logo">Cinetics</h1>
    </a>

    <?php
      if ($err) {
        echo '<p class="text-warning bg-dark text-center" style="font-weight: bold;">Incorrect username, email or password</p>';
      } else if (isset($_COOKIE[session_name()])) {
        if ($_SESSION['userStatusCode'] == 3) {
          echo '<p class="text-warning bg-dark text-center" style="font-weight: bold;">Account not verified, check your mailbox</p>';
        } else if ($_SESSION['userStatusCode'] == 2) {
          echo '<p class="text-success bg-dark text-center" style="font-weight: bold;">The email has been verified, Welcome!</p>';
          $_SESSION['userStatusCode'] = 0;
        }
      }
    ?>
    <form id="login-form" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <div class="mb-3">
        <label for="iemail" class="form-label">User or Email</label>
        <input type="text" class="form-control " id="iemail" name="user" required aria-describedby="emailHelp">

      </div>
      <div class="mb-3">
        <label for="ipassword" class="form-label">Password</label>
        <input type="password" class="form-control " id="ipassword" name="psw" required>

      </div>
      <div class="flex-container">
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember-me">
          <label for="remember-me" class="form-check-label">Remember me</label>
        </div>
        <div class="check-forgot">
          <a href="./web/forgotpsw.php">Forgot password?</a>
        </div>
      </div>
      <button type="submit" class="btn submit-button" id="login-submit">Log in</button>
    </form>
    <div class="sign-up-help">
      <h4 class="mb-3">Not yet a memeber? No worry!</h4>
      <a href="./web/signup.php">Sign up</a>
    </div>

  </div>
</body>

<!-- <script type="text/javascript" src="./js/index.js"></script> -->