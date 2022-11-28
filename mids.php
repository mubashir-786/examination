<?php

    include("vendor/autoload.php");
    include 'config.php';

    if($_POST) {
    
        $data = array(
            'courseInstructor' => $_POST['courseInstructor'],
            'programSemester'  => $_POST['programSemester'],
            'session'          => $_POST['session'],
            'courseName'       => $_POST['courseName'],
            'courseCode'       => $_POST['courseCode'],
            'date'             => $_POST['date'],
            'questions'        => $_POST['questions'],
            'totalMarks'       => $_POST['totalMarks'],
            'title'            => 'Mid Term',
        );

        if(isset($_POST['paperID']) && isset($_POST['paperID']) != ""){
            
            $sqlPaper = "UPDATE `papers` SET `paperType`= 0,
                    `courseInstructor`= '".$data['courseInstructor']."',`session`= '".$data['session']."',
                    `programSemester`= '".$data['programSemester']."',`courseName`= '".$data['courseName']."',`courseCode`= '".$data['courseCode']."',
                    `date`= '".date('y-m-d h:i:s',strtotime($data['date']))."',`status`= 0,`date_updated`='".date('y-m-d h:i:s')."' 
                    WHERE paperID = '".$_POST['paperID']."' ";

            $query = mysqli_query($conn, $sqlPaper);

            $sqlUploadPaper = "UPDATE `uploadpapers` SET `last_status`= 3,`status`= 0,`date_updated`='".date('y-m-d h:i:s')."' 
            WHERE `paperID` = '".$_POST['paperID']."'";
            $query = mysqli_query($conn, $sqlUploadPaper);

            $sqlDeleteQuestion = "DELETE FROM `questions` WHERE `fk_paperID` = '".$_POST['paperID']."' ";
            $query = mysqli_query($conn, $sqlDeleteQuestion);

            $sqlDeleteSubQuestion = "DELETE FROM `subquestions` WHERE `fk_paperID` = '".$_POST['paperID']."' ";
            $query = mysqli_query($conn, $sqlDeleteSubQuestion);

            $paperID = $_POST['paperID'];

        }
        else{
            $sqlPaper = "INSERT INTO `papers`(`paperType`, `courseInstructor`, `session`, `programSemester`, `courseName`, `courseCode`, `date`, `status`, `date_updated`) 
                    VALUES (0,'".$data['courseInstructor']."','".$data['session']."','".$data['programSemester']."','".$data['courseName']."','".$data['courseCode']."',
                    '".date('y-m-d h:i:s',strtotime($data['date']))."','0','".date('y-m-d h:i:s')."')";
    
            $query = mysqli_query($conn, $sqlPaper);
            $paperID = $conn->insert_id;
        }
       
        if(isset($_POST['questions'])){
            foreach($_POST['questions'] as $key => $question){

                $sqlQuestion = "INSERT INTO `questions`(`fk_paperID`, `question`, `questionMarks`) 
                VALUES ('".$paperID."','".$question['question']."','".$question['questionMarks']."')";
                
                $query = mysqli_query($conn, $sqlQuestion);
                $questionID = $conn->insert_id;

                if(isset($question['subQuestions'])){
                    
                    foreach($question['subQuestions'] as $key => $subQuestion){
                        $sqlSubQuestion = "INSERT INTO `subquestions`(`fk_questionID`, `fk_paperID`, `subQuestion`, `subQuestionMarks`) 
                        VALUES ('".$questionID."','".$paperID."','".$subQuestion['subQuestion']."','".$subQuestion['subQuestionMarks']."')";
                        $query = mysqli_query($conn, $sqlSubQuestion);    
                    }
                }
            }
        }
        
        $mpdf = new \Mpdf\Mpdf();
        
        $i = 1;
        $is = 1;
        $questionsHtml = '';
        
        if(isset($data['questions'])){
            foreach($data['questions'] as $key => $question){ 
        
            $questionsHtml .='<tr class="Question">
                                <td>'.$i.')'.'</td>
                                <td>'.$question['question'].'</td>
                                <td>'.$question['questionMarks'].'</td>
                            </tr>';

                if(isset($question['subQuestions'])){
                    foreach($question['subQuestions'] as $key => $subQuestion){
                        
                $questionsHtml .='<tr class="subQuestion" style="margin-left:20px !important;">
                                    <td>'.numberToRomanRepresentation($is).')'.'</td>
                                    <td>'.$subQuestion['subQuestion'].'</td>
                                    <td>'.$subQuestion['subQuestionMarks'].'</td>
                                </tr>';
                    $is++;
                    }
                }
            $i++;
            }
        }

        $html = '<!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta http-equiv="X-UA-Compatible" content="IE=edge">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>'.$data['title'].'</title>
                            <link rel="stylesheet" href="style.css">
                        </head>
                        <body>
                            <div class="container">
                                <img src="bukc.jpg" class="imagee" width="85" height="95" style="float: left">
                                <div class="heading">
                                <b>BAHRIA UNIVERSITY (KARACHI CAMPUS)</b><p>MIDTERM TERM EXAMINATION - SUMMER SEMESTER-2022 <br> <br> <b>(COURSE TITLE: '.$data['courseName'].' | COURSE CODE: '.$data['courseCode'].')</b></p>
                            </div> <br>
                            <div class="info">
                                <table style="width:100%">
                                    <tr>
                                        <td>Class: '.$data['programSemester'].' </td>
                                        <td>(Morning/Evening/Weekend)</td>
                                    </tr>
                                    <tr>
                                        <td>Course Instructor: '.$data['courseInstructor'].' </td>
                                        <td>Time Allowed: 90 Minutes</td>
                                    </tr>
                                    <tr>
                                        <td>Date: '.$data['date'].' Session: '.$data['session'].' </td>
                                        <td>Max Marks: 20 | Generator Code : '.$paperID.' </td>
                                    </tr>
                                    <tr>
                                        <td>Student Name: ____________________________</td>
                                        <td>Reg. No: ____________</td>
                                    </tr>
                                </table> <br>

                            <b> Note: </b> (If Any) <br> <br>

                            Kindly mention time limit for MCQs and seperate it from the subjective part.
                            </div>
                            </div> <br>

                            <div class="content">
                                <div class="questions" name="add">
                                    <table class="table table-bordered dataTable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <td width="15%">Sr.No</td>
                                                <td width="60%">Questions</td>
                                                <td width="25%">Marks</td>
                                            </tr>
                                        </thead>
                                        <tbody>'.$questionsHtml.'</tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" style="text-algin:right !important;">Total Marks</td>
                                                <td class="totalMarks">'.$data['totalMarks'].'</td>
                                            </tr>
                                        </tfoot>
                                    </table> 
                                    <br>
                                </div>
                            </div>
                        </body>
                    </html>';
        
        //echo $html; exit;
        $mpdf->writeHTML($html);
        $mpdf->output();
    }  

    $paper = array();
    if(isset($_GET['paperID'])){
        
        $sqlString = "SELECT * FROM `papers` WHERE `paperID` = '".base64_decode($_GET['paperID'])."' ";
        
        $query = mysqli_query($conn, $sqlString);
        $paper = mysqli_fetch_assoc($query);

        $sqlString = "SELECT * FROM `questions` WHERE `fk_paperID` = '".base64_decode($_GET['paperID'])."' ";
        $queryQuestion = mysqli_query($conn, $sqlString);

        $i = 0;
        $questions = array();
        while($question = mysqli_fetch_assoc($queryQuestion)){
            
            $paper['questions'][$i] = $question;

            $sqlString = "SELECT * FROM `subquestions` WHERE `fk_questionID` = '".$question['questionID']."' ";
            $querySubQuestion = mysqli_query($conn, $sqlString);
            
            while($subQuestion = mysqli_fetch_assoc($querySubQuestion)){
                $paper['questions'][$i]['subQuestions'][] = $subQuestion;

            }

            $i++;
        }
        
        !empty($paper) ? extract($paper) : 0 ;
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
        <title>Mids - Bahria University</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
        <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
        <style type="text/css">
            .validation-invalid-label{
                color : red !important;
            }
        </style>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light">Format Mid Paper</h3></div>
                                    <div class="card-body">

                                        <form class="form formate-Term-paper" method="POST" action="mids.php" id="format-paper">

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <?php if(isset($_GET['paperID']) && $_GET['paperID'] != ""){ ?>
                                                            <input type="hidden" name="paperID" value="<?=base64_decode($_GET['paperID']);?>">
                                                        <?php } ?>
                                                        <input type="hidden" name="paperType" value="0" id="hiddenInputField">
                                                        <input type="hidden" name="totalMarks" value="" id="totalMarks">
                                                        <input class="form-control" id="courseInstructor" type="text" name="courseInstructor" value="<?=@$courseInstructor;?>" placeholder="Course Instructor" required/>
                                                        <label for="courseInstructor">Course Instructor *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input class="form-control" id="programSemester" type="text" name="programSemester" value="<?=@$programSemester;?>" placeholder="Program and Semester" required/>
                                                        <label for="programSemester">Program and Semester *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="session" type="text" name="session" value="<?=@$session;?>" placeholder="Session" required/>
                                                        <label for="session">Session *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input class="form-control" id="courseName" type="text" name="courseName" value="<?=@$courseName;?>" placeholder="Course Name" required/>
                                                        <label for="courseName">Course Name *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input class="form-control" id="courseCode" type="text" name="courseCode" value="<?=@$courseCode;?>" placeholder="Course Code" required/>
                                                        <label for="courseCode">Course Code *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <input class="form-control" id="date" type="date" name="date" placeholder="Date" required/>
                                                        <label for="date">Date *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                                <?php 

                                                    if(isset($_GET['paperID']) && !empty($paper['questions'])){ 
                                                       
                                                        foreach($paper['questions'] as $key => $question){
                                                            
                                                            $questionID = $key;
                                                            $subQuestionID = isset($question['subQuestions']) ? count($question['subQuestions']) : 0 ; 
                                                            
                                                ?>
                     

                                                <div class="row mb-3">
                                                            
                                                    <div class="col-md-8" style="width:65.86666667% !important;">
                                                        <div class="form-floating mb-3">
                                                            <input class="form-control" id="question" type="text" name="questions[<?=$key;?>][question]" value="<?=$question['question'];?>" placeholder="Question" required/>
                                                            <label for="question">Question *</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-floating">
                                                            <input class="form-control questionMarks" id="questionMarks" type="number" name="questions[<?=$key;?>][questionMarks]" value="<?=$question['questionMarks'];?>" placeholder="Marks" required style="width: 93%"/>
                                                            <label for="questionMarks">Marks *</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-floating m-2">
                                                            <button type="button" class="btn btn-success addSubQuestion" data-questionID="<?=$key;?>" data-subquestionID="<?=$subQuestionID;?>" >+Add</button>
                                                            <a href="javascript:void(0);" class="removeQuestionRow"><i class="fa fa-2x fa-trash-alt" style="margin-left:12px;vertical-align:middle !important;color:red;"></i></a>
                                                        </div>
                                                    </div>

                                                    <?php
                                                        if(isset($question['subQuestions']) && !empty($question['subQuestions'])){
                                                            foreach($question['subQuestions'] as $key => $subQuestions){
                                                    ?>
                                                               
                                                            <div class="row mb-3">
                                                                <div class="col-md-8" style="padding-right:4px !important;">
                                                                    <div class="form-floating mb-3">
                                                                    <input class="form-control" id="subQuestion_<?=$key;?>" type="text" name="questions[<?=$questionID;?>][subQuestions][<?=$key;?>][subQuestion]" value="<?=$subQuestions['subQuestion'];?>" placeholder="Sub Question *" required/> 
                                                                    <label for="subQuestion_<?=$key;?>">Sub Question *</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2" style="padding-right:unset !important;padding-left:20px !important;">
                                                                    <div class="form-floating">
                                                                        <input class="form-control subQuestionMarks" id="subQuestionMarks_<?=$key;?>" type="number" name="questions[<?=$questionID;?>][subQuestions][<?=$key;?>][subQuestionMarks]" value="<?=$subQuestions['subQuestionMarks'];?>" data-questionID="<?=$questionID;?>" placeholder="Marks *" required style="width: 93%"/>
                                                                        <label for="subQuestionMarks_<?=$key;?>">Marks *</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2" style="padding-left:30px !important;">
                                                                    <div class="form-floating">
                                                                        <a href="javascript:void(0);" class="removeSubQuestionRow"><i class="fa fa-2x fa-trash-alt" style="margin-top:12px;color:red;"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                               
                                                               <?php
                                                            }
                                                        }
                                                    ?>    
                                                </div>
                                                <?php  }  }else{ ?>

                                                <div class="row mb-3">    
                                                    <div class="col-md-8" style="width:65.86666667% !important;">
                                                        <div class="form-floating mb-3">
                                                            <input class="form-control" id="question" type="text" name="questions[0][question]" placeholder="Question" required/>
                                                            <label for="question">Question *</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-floating">
                                                            <input class="form-control questionMarks" id="questionMarks" type="number" name="questions[0][questionMarks]" placeholder="Marks" required style="width: 93%"/>
                                                            <label for="questionMarks">Marks *</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-floating m-2">
                                                            <button type="button" class="btn btn-success addSubQuestion" data-questionID="0" data-subquestionID="0" >+Add</button>
                                                            <a href="javascript:void(0);" class="removeQuestionRow"><i class="fa fa-2x fa-trash-alt" style="margin-left:12px;vertical-align:middle !important;color:red;"></i></a>
                                                        </div>
                                                    </div>
                                                    <!-- QuestionGoesHere -->
                                                </div>

                                            <?php } ?>

                                        </form>

                                        <div class="col-lg-12 mt-3 mb-0" style="display:inline-flex;justify-content:space-between;padding-right: 5px !important;">
                                            <button type="button" class="btn btn-success add_item_btn" id="addQuestion" data-questionID="0" onclick="addQuestionrow(this)">+Add Question</button>
                                            <button class="btn btn-primary btn-block" name="format" form="format-paper" style="margin-left: 400px; padding: 10px; width: 15%">Format</button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.js"></script>
        <script type="text/javascript" src="assets/plugins/form-validate/jquery.validate.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="script.js"></script>
    </body>
</html>
