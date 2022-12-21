<?php 

    include("vendor/autoload.php");
    include 'config.php';

    // $status = 0;
    // switch ($_SESSION['userRole']) {
    //     case "faculty":
    //         $status = 3;
    //         break;
    //     case "clusterhead":
    //         $status = 0;
    //         break;
    //     case "hod":
    //         $status = 1;            
    //         break;
    //     case "examinationcell":
    //         $status = 2;            
    //         break;
    // }

    // //exit;

    // $sqlString = "SELECT * FROM `uploadpapers` WHERE `status` = '".$status."'";
    // $query = mysqli_query($conn, $sqlString);

    // $papers = array();
    // while($paperResult = mysqli_fetch_assoc($query)){
    //     $papers[] = $paperResult;
    // }
  
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title class="text-uppercase"><?=$_SESSION['userRole'];?> - Bahria University</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
        <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="faculty.php">Bahria University</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            </form>

            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="faculty.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link collapsed" href="personalinfofac.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Personal Information
                            </a>
                            <?php if($_SESSION['userRole'] == 'faculty'){ ?>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                    Format Paper
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                        <a class="nav-link collapsed" href="mids.php">
                                            Mids
                                        </a>
                                        <a class="nav-link collapsed" href="finals.php">
                                            Finals
                                        </a>
                                    </nav>
                                </div>
                                <a class="nav-link" href="upload.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                    Upload Paper
                                </a>
                            <?php } ?>
                            <a class="nav-link" href="reviewPapers.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                <?php 
                                    if($_SESSION['userRole'] == 'clusterhead' || $_SESSION['userRole'] == 'hod'){
                                        echo 'Review Papers';
                                    }elseif($_SESSION['userRole'] == 'examinationcell'){
                                        echo 'Received Papers';
                                    }else{
                                        echo 'Rejected Papers';
                                    }
                                ?>
                            </a>
                            <a class="nav-link" href="undertakings.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Undertakings
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Faculty
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4 py-4">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Manage Undertakings</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="10%">Sr.No</th>
                                                    <th width="35%">Title</th>
                                                    <th width="20%">Status</th>
                                                    <th width="15%">Download</th>
                                                    <th width="20%" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td width="10%">01</td>
                                                    <td width="35%">New Paper</td>
                                                    <td width="20%">Approved</td>
                                                    <td width="15%">Download</td>
                                                    <td width="20%" class="text-center">Actions</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
