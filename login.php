<?php 

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        include 'config.php';
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $sql = "select * from users where username='$username' and 
                password='$password'";
        
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_array($query);
        $num = mysqli_num_rows($query);
    
        if($num >= 1) {
            
            $_SESSION['userRole'] = $result['role'];
            $_SESSION['username'] = $result['username'];

            switch ($result['role']) {
                case "faculty":
                    header("location:faculty.php");
                    break;
                case "clusterhead":
                    header("location:clusterhead.php");
                    break;
                case "hod":
                    header("location:hod.php");                
                    break;
                case "examinationcell":
                    header("location:examinationcell.php");                
                    break;
                default : {
                    header("location:faculty.php");                
                    break;
                }
            }
        }
        else {
            
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Bahria University</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="post" action="#">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" type="text" name="username" placeholder="name@example.com" required />
                                                <label for="inputEmail">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Password" required/>
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <!---<div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>--->
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <!---<a class="small" href="password.html">Forgot Password?</a>-->
                                                <button type="submit" name="login" class="btn btn-primary" style="margin-left: 180px">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!---<div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.html">Need an account? Sign up!</a></div>
                                    </div>--->
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <!---<div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2022</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>--->
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>