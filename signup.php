<?php
require_once "./libdb/userRegister.php";

// userStatus: 0 (sesión no iniciada) | 1 (sesión iniciada) | 2 (mail verificado) | 3 (email sin verificar)
if (isset($_COOKIE[session_name()])) {
    session_start();
    if ($_SESSION['userStatusCode'] == 1) {
        header("Location: index.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (count($_POST) == 6) {
        $userPOST = filter_input(INPUT_POST, 'username');
        $emailPOST = filter_input(INPUT_POST, 'email');
        $fnamePOST = filter_input(INPUT_POST, 'firstname');
        $snamePOST = filter_input(INPUT_POST, 'lastname');
        $passPOST = filter_input(INPUT_POST, 'psw');
        $confirmPassPOST = filter_input(INPUT_POST, 'confirm_password');

        $usuari['username'] = $userPOST;
        $usuari['email'] = $emailPOST;
        $usuari['fname'] = $fnamePOST;
        $usuari['sname'] = $snamePOST;
        $usuari['pss'] = $passPOST;

        if (registroUsuario($usuari, $emailDuplicat, $usuariDuplicat)) {
            header("Location: ./web/newmember.html");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<!-- TODO: cambiar cuando se relocalizen-->
<?php include "./includes/indexHead.php" ?>
<body >
  <div class="signup-body">
    <video autoplay muted loop id="backVideo">
      <source src="./media/waiting.mp4" type="video/mp4">
    </video>
    <div class="col-10 central-panel">
      <a href="index.php" class="link">
        <h1 class="logo">Cinetics</h1>
      </a>

          <form id="signup-form" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
              <div class="flex-container-signup">

                <div id="div-left">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username (required)</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                    <?php
                        if (isset($usuariDuplicat) && $usuariDuplicat) {
                            echo '<p class="text-warning bg-dark" style="color:red">&nbsp This username is already in use</p>';
                        }
                        ?>
                  </div>
                  <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstname" id="firstname">
                  </div>
                  <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastname" id="lastname">
                  </div>
                </div>

                <div id="div-right">
                  <div class="mb-3">
                    <label for="semail" class="form-label">Email (required)</label>
                    <input type="email" class="form-control" id="semail" name="email" aria-describedby="emailHelp" required>
                    <?php
                        if (isset($emailDuplicat) && $emailDuplicat) {
                            echo '<p class="text-warning bg-dark" style="color:red">&nbsp This email is already in use</p>';
                        }
                        ?>
                  </div>
                  <div class="mb-3">
                    <label for="psw" class="form-label">Password (required)</label>
                    <input type="password" class="form-control" name="psw" id="psw" required>
                  </div>
                  <div class="mb-3">
                    <label for="psw2" class="form-label">Repeat password (required)</label>
                    <input type="password" class="form-control" name="confirm_password" id="psw2" onkeyup='check();' required>
                    <span id='message'></span>
                  </div>
                </div>

              </div>
              <div class="text-center">
                <button type="submit" class="btn signup-button" id="signup-submit">Sign up</button>
              </div>

          </form>
    </div>
  </div>
  <script  type = "text/javascript">
    function check() {

      var valuePsw = document.getElementById('psw').value;
      var valuePsw2 = document.getElementById('psw2').value;

      if (valuePsw == valuePsw2) {  
        document.getElementById('message').style.color = 'green';
        document.getElementById('psw').style.borderColor = 'white';
        document.getElementById('psw2').style.borderColor = 'white';
        document.getElementById('message').innerHTML = 'Matching passwords';
      } else if(valuePsw != valuePsw2) {
        document.getElementById('message').style.color = 'red';
        document.getElementById('psw').style.borderColor = 'red';
        document.getElementById('psw2').style.borderColor = 'red';
        document.getElementById('message').innerHTML = 'Not matching passwords';
      }
    }
  </script>

</body>