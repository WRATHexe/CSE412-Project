<?php
session_start();
session_unset();
session_destroy();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $loggedin = true;
} else {
    $loggedin = false;
}


$showAlert = false;
$showError = false;
$valueCheck = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'includes/dbcon.php';
    include 'includes/signupIC.php';
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $userType = "user";

    $array = array($name, $email, $username, $password, $cpassword);
    $valueCheck = valueCheck($array);

    $usernameEsistsCheck = usernameExists($username, $conn);
    $emailExistsCheck = emailExists($email, $conn);
    $passwordMatched = passwordCheck($password, $cpassword);
    if (!$usernameEsistsCheck and !$emailExistsCheck and $passwordMatched and $valueCheck) {
        $hashedPass = hash('sha256', $password);
        $sql = "INSERT INTO `usertable`( `name`, `email`, `username`, `password`, `userType`) VALUES ('$name','$email','$username','$hashedPass', '$userType')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $showAlert = true;
        }
    } else {
        if ($usernameEsistsCheck)
            $showError = $showError . "| Username already exists |";
        if ($emailExistsCheck)
            $showError = $showError . "| Email already exists |";
        if (!$passwordMatched)
            $showError = $showError . "| Passwords dont match |";
    }
}

?>

<!-- Header -->
<?php include 'includes/header.php'; ?>

<!-- Contact-->
<section class="page-section" id="signup">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="mt-0">SIGN UP!</h2>
                <hr class="divider" />
                <p class="text-muted mb-5"></p>
            </div>
        </div>
        <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
            <div class="col-lg-6">

                <form action="signup.php" method="post">

                    <!-- Error Message-->
                    <?php if ($showError) {
                        echo "<div>
                        <div class='alert alert-danger d-flex align-items-center' role='alert'>
                            <div>
                                $showError
                            </div>
                        </div>
                    </div>";
                    }
                    if (!$valueCheck) {
                        echo "<div>
                        <div class='alert alert-danger d-flex align-items-center' role='alert'>
                            <div>
                                Fill all the fields!
                            </div>
                        </div>
                    </div>";
                    } ?>

                    <!-- Name input-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="name" name="name" type="text"
                            placeholder="Enter your name..." />
                        <label for="name">Full Name</label>
                    </div>
                    <!-- userName input-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="username" name="username" type="text"
                            placeholder="Enter your username..." />
                        <label for="name">Username</label>
                    </div>
                    <!-- Emailinput-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" name="email" type="email"
                            placeholder="Enter your email..." />
                        <label for="email">Email</label>
                    </div>
                    <!-- passwordinput-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="password" name="password" type="password"
                            placeholder="****************" />
                        <label for="password">Password</label>
                    </div>
                    <!-- ConfirmPasswordinput-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="cpassword" name="cpassword" type="password"
                            placeholder="****************" />
                        <label for="password">Confirm Password</label>
                    </div>
                    <!-- Submit Button-->
                    <div class="d-grid"><button class="btn btn-primary btn-xl" id="submitButton"
                            type="submit">Submit</button></div>
                </form>
            </div>

        </div>
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h6 class="mt-0">Have an account?<br><a href="login.php">Login</a></h6>

            </div>
        </div>

    </div>
</section>
<!-- Footer-->
<?php include 'includes/footer.php'; ?>