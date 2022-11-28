
console. log(jQuery(). jquery);

$(document).ready(function(){
		
    // Initialize
    var validator = $('.formate-Term-paper').validate({
        
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-invalid-label',
        successClass: 'validation-valid-label',
        validClass: 'validation-valid-label',
        
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        
        // success: function(label) {
        //     label.addClass('validation-valid-label').text('Success.'); // remove to hide Success message
        // },

        // Different components require proper error label placement
        errorPlacement: function(error, element) {

            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo( element.parents('.form-check').parent() );
            }

            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }

            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }

            // Other elements
            else {
                error.insertAfter(element.parent());
            }

        },

        rules: {
        },

        messages: {
        },

        submitHandler: function(form) {
            
            let paperType = $(".formate-Term-paper #hiddenInputField").val();
            let totalMarksLimit = paperType == 0 ? 20 : 50;
            let questionMarksTotal = 0;
	        
            let flag = false;
            $(".questionMarks").each(function(index,value){
                
                let questionMarks = 0;
                let subQuestionMarks = 0;

                questionMarks = parseInt(value.value);
                questionMarksTotal += questionMarks;

                $("input[data-questionID="+index+"]").each(function(index,value){
                    subQuestionMarks +=  parseInt(value.value);
                });
                              
                if(subQuestionMarks > 0 && subQuestionMarks > questionMarks){
                    alert('SubQuestions marks exeeded of Question No '+(parseInt(index)+1)+' marks,Please review your paper.');
                    flag = true;
                    return;	
                }
                else if(subQuestionMarks > 0 &&  subQuestionMarks < questionMarks){
                    alert('SubQuestions marks less than of Question No '+(parseInt(index)+1)+' marks,Please review your paper.');
                    flag = true;
                    return;	
                }
                
            });

	        if(questionMarksTotal > totalMarksLimit){
                alert('Paper total marks limit exceeded by questions marks,Please review your paper.');
                flag = true;
                return;
            }
            else if(questionMarksTotal < totalMarksLimit){
                alert('Paper questions marks are less than total paper marks,Please review your paper.');
                flag = true;
                return;
            }	

            if(!flag){
                //alert(questionMarksTotal);
                $("#totalMarks").val(parseInt(questionMarksTotal));
                form.submit();
            }
            
        }
    });

    $(document).on('click','.addSubQuestion',function(){
        
        var questionID = $(this).attr('data-questionid');
        var subquestionID = $(this).attr('data-subquestionid');
        var subquestionId = parseInt(subquestionID)+1;
        
        $(this).attr("data-subquestionid", subquestionId);

        html ='<div class="row mb-3">'+
                '<div class="col-md-8" style="padding-right:4px !important;">'+
                    '<div class="form-floating mb-3">';

            html += '<input class="form-control" id="subQuestion_'+subquestionID+'" type="text" name="questions['+questionID+'][subQuestions]['+subquestionID+'][subQuestion]" placeholder="Sub Question *" required/>';
            
            html += '<label for="subQuestion_'+subquestionID+'">Sub Question *</label>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2" style="padding-right:unset !important;padding-left:20px !important;">'+
                    '<div class="form-floating">'; 
                html += '<input class="form-control subQuestionMarks" id="subQuestionMarks_'+subquestionID+'" type="number" name="questions['+questionID+'][subQuestions]['+subquestionID+'][subQuestionMarks]" data-questionID="'+questionID+'" placeholder="Marks *" required style="width: 93%"/>';
                html += '<label for="subQuestionMarks_'+subquestionID+'">Marks *</label>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2" style="padding-left:30px !important;">'+
                    '<div class="form-floating">';
                html += '<a href="javascript:void(0);" class="removeSubQuestionRow"><i class="fa fa-2x fa-trash-alt" style="margin-top:12px;color:red;"></i></a>';
                    '</div>'+
                '</div>'+
            '</div>';

        $(this).parent().parent().parent().append(html);
    });

    $(document).on('click','.removeQuestionRow',function(){
        var confirmation = confirm('Are you sure? You want to remove this question.');
        if(confirmation){
            $(this).parent().parent().parent().remove();
        }
    });

    $(document).on('click','.removeSubQuestionRow',function(){
        var confirmation = confirm('Are you sure? You want to remove this subquestion.');
        if(confirmation){
            $(this).parent().parent().parent().remove();
        }
    });
});

function printPDFPaper(path){
    if(printJS({
            printable : path, 
            type : 'pdf', 
            showModal : true
        })){
            alert(path);
        }
}

function changeStatus(status,type,paperID){
    
    var updatedStatus = 0;
    if(type == 1 && status < 2){
        var updatedStatus = parseInt(status)+1;
    }
    else if(type == 0){
        confirm('Are you sure? you want to disapprove this paper.');
        var updatedStatus = 3;
    }

    $.post('ajaxCall.php?action=updatePaperStatus',
    {
        status: updatedStatus,
        changeStatus: status,
        paperID:paperID
    },
    function(){
        window.location.reload();
    });
}

function addQuestionrow(event){
    
    var questionID = event.getAttribute('data-questionID');
    var questionID = parseInt(questionID)+1;
    event.setAttribute('data-questionID',questionID);
    
    html ='<div class="row mb-3">'+
            '<div class="col-md-8" style="width:65.86666667% !important;">'+
                '<div class="form-floating mb-3">';

            html += '<input class="form-control" id="question_'+questionID+'" type="text" name="questions['+questionID+'][question]" placeholder="Question *" required/>';
            
            html += '<label for="question_'+questionID+'">Question *</label>'+
                '</div>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<div class="form-floating">';
            html += '<input class="form-control questionMarks" id="questionMarks_'+questionID+'" type="number" name="questions['+questionID+'][questionMarks]" value="" placeholder="Marks *" required style="width: 93%"/>';
            html += '<label for="questionMarks_'+questionID+'">Marks *</label>'+
                '</div>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<div class="form-floating m-2">';
            html += '<button type="button" class="btn btn-success addSubQuestion" data-questionID="'+questionID+'" data-subquestionID="0">+Add</button>';
            html += '<a href="javascript:void(0);" class="removeQuestionRow"><i class="fa fa-2x fa-trash-alt" style="margin-left:12px;vertical-align:middle !important;color:red;"></i></a>';
            html += '</div>'+
            '</div>'+
        '</div>';

    $("#format-paper").append(html);
}

