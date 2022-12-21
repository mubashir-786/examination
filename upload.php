<?php 

    include("vendor/autoload.php");
    include 'config.php';

    if($_POST){

        $uploadfilePath = '';
        if(isset($_FILES) && $_FILES['paper']['name'] != ""){

            $fileNameRandom = $_FILES['paper']['name'].'_'.rand(0,1000);

            $uploadDir = 'uploads/finalTermPaper/';
            $uploadfile = $uploadDir . basename($fileNameRandom);

            if (move_uploaded_file($_FILES['paper']['tmp_name'], $uploadfile)) {
                
                $uploadfilePath = $uploadfile;
            }
        }elseif(isset($_POST['paper_old']) && $_POST['paper_old'] != ""){
            $uploadfilePath = $_POST['paper_old'];
        }

        if(isset($_POST['paperID']) && $_POST['paperID'] != ""){
           
            $sqlString = "UPDATE `uploadpapers` SET `type`= '".$_POST['type']."',`title`='".$_POST['title']."',`path`='".$uploadfilePath."',
            `numberStudents`='".$_POST['numberStudents']."',`last_status`= 0,`status`= 0,`date_updated`= '".date('y-m-d h:i:s')."' 
            WHERE `paperID` = '".$_POST['paperID']."' ";

            $query = mysqli_query($conn,$sqlString);
        }else{

            $sqlString = "INSERT INTO `uploadpapers`(`paperID`,`type`,`title`, `path`,`numberStudents`,`last_status`,`status`, `date_updated`) 
            VALUES ('".$_POST['generatorCode']."','".$_POST['type']."','".$_POST['title']."','".$uploadfilePath."','".$_POST['numberStudents']."','0','0','".date('y-m-d h:i:s')."')";
            
            $query = mysqli_query($conn,$sqlString);
            $uploaderID = $conn->insert_id;
        }

    }

    $sqlPapersString = "SELECT * FROM `uploadpapers` WHERE `status` = 0 ";
    $queryResultPapers = mysqli_query($conn, $sqlPapersString);

    $papers = array();
    while($paperSingleResult = mysqli_fetch_assoc($queryResultPapers)){
        $papers[] = $paperSingleResult;
    }

    if(isset($_GET['paperID']) && $_GET['paperID'] != ""){
        $sqlPaperString = "SELECT * FROM `uploadpapers` WHERE `status` = 0 AND `paperID` = '".base64_decode($_GET['paperID'])."' ";
        $queryResultSinglePaper = mysqli_query($conn, $sqlPaperString);
        $paper = mysqli_fetch_assoc($queryResultSinglePaper);
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
        <title>Faculty - Bahria University</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
        <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="faculty.php">Bahria University</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <!---<div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>--->
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
                            <!---<div class="sb-sidenav-menu-heading">Core</div>--->
                            <a class="nav-link" href="faculty.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <!---<div class="sb-sidenav-menu-heading">Interface</div>--->
                            <a class="nav-link collapsed" href="personalinfofac.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Personal Information
                                <!----<div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>--->
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
                                Rejected Papers
                            </a>
                            <a class="nav-link" href="">
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
                                        <h4 class="card-title">Upload Paper</h4>
                                    </div>
                                    <div class="card-body">
                                        <form class="form upload-paper" method="POST" action="upload.php" enctype="multipart/form-data" id="upload-paper">

                                            <div class="row mb-3">
                                                <div class="col-lg-6 col-md-8">
                                                    <div class="form-floating">
                                                        <?php if(isset($_GET['paperID']) && $_GET['paperID'] != ""){ ?>
                                                            <input type="hidden" name="paperID" value="<?=base64_decode($_GET['paperID']);?>">
                                                        <?php } ?>
                                                        <input type="text" id="title" name="title" value="<?=@$paper['title'];?>" class="form-control" placeholder="Title" required>
                                                        <label for="title">Title *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-lg-6 col-md-8">
                                                    <div class="form-floating">
                                                        <select name="type" class="form-control">
                                                            <option value="" selected="selected">Select Type</option>
                                                            <option value="0" <?php if(@$paper['type'] == 0){ echo "selected"; } ?> >Mid Term</option>
                                                            <option value="1" <?php if(@$paper['type'] == 1){ echo "selected"; } ?>>Final Term</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-lg-6 col-md-8">
                                                    <div class="form-floating">
                                                        <input type="number" id="numberStudents" name="numberStudents" value="<?=@$paper['numberStudents'];?>" class="form-control" placeholder="Number of Students" required>
                                                        <label for="numberStudents">Number of Students *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-lg-6 col-md-8">
                                                    <div class="form-floating">
                                                        <input type="number" id="generatorCode" name="generatorCode" value="<?=@$paper['paperID'];?>" <?php if(isset($_GET['paperID']) && $_GET['paperID'] != ""){ echo 'disabled'; } ?> class="form-control" placeholder="Paper generator code" required>
                                                        <label for="generatorCode">Generator Code *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-lg-6 col-md-8" style="display:inline-flex;justify-content:space-between;">
                                                    <div class="form-floating">
                                                        <input type="hidden" name="paper_old" value="<?=@$paper['path'];?>">
                                                        <input type="file" id="file" name="paper" class="btn btn-primary btn-sm" required>
                                                    </div>
                                                    <div class="form-floating">
                                                        <button type="submit" class="btn btn-success">Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row py-4">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Manage Papers</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="10%">Sr.No</th>
                                                    <th width="40%">Title</th>
                                                    <th width="30%">Download</th>
                                                    <th width="20%" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $i = 1;
                                                    if($papers){
                                                        foreach($papers as $key => $paper){
                                                ?>
                                                            <tr>
                                                                <th scope="row"><?=$i;?></th>
                                                                <td><?=$paper['title'];?></td>
                                                                <td><a href="<?=$paper['path'];?>" class="" download target="_blank">Download <i class="fa fa-arrow-down" style="width:0.675rem !important;margin-left:8px !important;"></i></a></td>
                                                                <td class="text-center"><a href="<?='upload.php?paperID='.base64_encode($paper['paperID']).'';?>"><i class="fa fa-edit"></i></a></td>
                                                            </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                    }
                                                ?>
                                               
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
