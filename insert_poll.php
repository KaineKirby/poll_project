<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

<?php
include 'vars.php'; //vars.php defines database and begins a new session each time the browser restarts

$question = $_POST['pollQuestion'];
$option1 = $_POST['pollAnswerOption1'];
$option2 = $_POST['pollAnswerOption2'];
$option3 = $_POST['pollAnswerOption3']; //retrieving the items that the user input into the form
$option4 = $_POST['pollAnswerOption4'];
$option5 = $_POST['pollAnswerOption5'];
$option6 = $_POST['pollAnswerOption6'];

$pollQuery = $db->query("SELECT MAX(poll_id) FROM poll_question");
$maxPollId = $pollQuery->fetch(PDO::FETCH_ASSOC);
$newPollId = $maxPollId["MAX(poll_id)"] + 1; //Finding the id of the last poll so that the new one will have the next id

$optionQuery = $db->query("SELECT MAX(option_id) FROM poll_options");
$maxOptionId = $optionQuery->fetch(PDO::FETCH_ASSOC);
$newOptionId = $maxOptionId["MAX(option_id)"] + 1; //Finding the id of the last option id so that the new options will have the next id

$questionQuery = $db->prepare("INSERT INTO poll_question(poll_id, poll_question) VALUES (?, ?)");
$questionQuery->bindParam(1, $newPollId, PDO::PARAM_INT);
$questionQuery->bindParam(2, $question, PDO::PARAM_STR);
$questionQuery->execute();


$option1Query = $db->prepare("INSERT INTO poll_options(option_id, curr_poll, option_content) VALUES (? , ?, ?)");
$option1Query->bindParam(1, $newOptionId, PDO::PARAM_INT);
$option1Query->bindParam(2, $newPollId, PDO::PARAM_INT);
$option1Query->bindParam(3, $option1, PDO::PARAM_STR);
$option1Query->execute();

$option2Query = $db->prepare("INSERT INTO poll_options(option_id, curr_poll, option_content) VALUES (? + 1, ?, ?)");
$optionCount = 2; //The form requires that at least one question and two options for that question are included
$option2Query->bindParam(1, $newOptionId, PDO::PARAM_INT);
$option2Query->bindParam(2, $newPollId, PDO::PARAM_INT);
$option2Query->bindParam(3, $option2, PDO::PARAM_STR);
$option2Query->execute();

if($option3 != "") {//if option 3 isnt empty insert it (so that there is no empty space in the database)
  $option3Query = $db->prepare("INSERT INTO poll_options(option_id, curr_poll, option_content) VALUES (? + $optionCount, ?, ?)");
  $optionCount++;
  $option3Query->bindParam(1, $newOptionId, PDO::PARAM_INT);
  $option3Query->bindParam(2, $newPollId, PDO::PARAM_INT);
  $option3Query->bindParam(3, $option3, PDO::PARAM_STR);
  $option3Query->execute();
}
if($option4 != "") {//if option 4 isnt empty insert it (so that there is no empty space in the database)
  $option4Query = $db->prepare("INSERT INTO poll_options(option_id, curr_poll, option_content) VALUES (? + $optionCount, ?, ?)");
  $optionCount++;
  $option4Query->bindParam(1, $newOptionId, PDO::PARAM_INT);
  $option4Query->bindParam(2, $newPollId, PDO::PARAM_INT);
  $option4Query->bindParam(3, $option4, PDO::PARAM_STR);
  $option4Query->execute();
}
if($option5 != "") {//if option 5 isnt empty insert it (so that there is no empty space in the database)
  $option5Query = $db->prepare("INSERT INTO poll_options(option_id, curr_poll, option_content) VALUES (? + $optionCount, ?, ?)");
  $optionCount++;
  $option5Query->bindParam(1, $newOptionId, PDO::PARAM_INT);
  $option5Query->bindParam(2, $newPollId, PDO::PARAM_INT);
  $option5Query->bindParam(3, $option5, PDO::PARAM_STR);
  $option5Query->execute();
}
if($option6 != "") {//if option 6 isnt empty insert it (so that there is no empty space in the database)
  $option6Query = $db->prepare("INSERT INTO poll_options(option_id, curr_poll, option_content) VALUES (? + $optionCount, ?, ?)");
  $optionCount++;
  $option6Query->bindParam(1, $newOptionId, PDO::PARAM_INT);
  $option6Query->bindParam(2, $newPollId, PDO::PARAM_INT);
  $option6Query->bindParam(3, $option6, PDO::PARAM_STR);
  $option6Query->execute();
}
//This script handles the modal popup, depending on what the user clicks in the modal they will be redirected
//This script prevents the user from clicking the background to close the modal, they must be redirected.
echo "<script>
$(function(){
$('#previousVoteMsg').modal({backdrop: 'static', keyboard: false});

$('#xButton').click(function(){
 window.location.href= 'current_polls.php';
});
$('#closeVoteButton').click(function(){
 window.location.href= 'current_polls.php';
});

});
  </script>";
?>

<!-- Modal -->
 <div class="modal fade" id="previousVoteMsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="previousVoteMsgTitle">Success!</h5>
         <button type="button" class="close" id= "xButton" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body">
         <p id="previousVoteMsg-content">Thank you, your poll has been added to the list!</p>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-secondary" id= "closeVoteButton" data-dismiss="modal">Return to polls</button>
       </div>
     </div>
   </div>
 </div>
