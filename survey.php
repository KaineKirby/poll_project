<?php
include 'vars.php'; //vars.php defines database and begins a new session each time the browser restarts
$poll = $_POST['poll_Number'];
$option = $_POST['option_Selection']; //creating variables from the posted values
$user_id = $_SESSION['user_id'];

// print_r($poll);
// print_r($user_id);

function showResults($db, $poll) { //this function handles the results page, including percentages and the bar-graph graphic
  ?>
  <!-- the style portion below specifically handles the positioning of the graph on the page-->
  <style>
  #resultGraph{
    position: absolute;
    text-align: center;
    bottom:0;
    left:0;
    right:0;
    vertical-align: bottom;
  }
  #container{
    box-shadow: 50px 50px 50px;
    border:5px solid gray;
    border-radius: 100px;
    text-align: center;
    position: relative;
    background-color: lightgray;
    height: 500px;
  }
  .graphBar{
    vertical-align: bottom;
    width: 150px;
  }
  .content{
    display:block;
    height: 75px;
  }
  .bar{
    text-align: center;
    display: inline-block;
  }
  </style>
  <header>

  <nav class="navbar navbar-dark bg-dark"><!-- setting titles, links, and styling for the navigation bar-->
<a class="navbar-brand" href="index.html">Kirby's Polls</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarText">
  <ul class="navbar-nav mr-auto">
    <li class="nav-item">
      <a class="nav-link" href="index.html">Home</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="create_poll.html">Create a poll</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="current_polls.php">Take a poll</a>
    </li>
  </ul>
</div>
</nav>
</header>
  <div class= "container">
     <div class= "col-sm-12">
       <h1>Results</h1>
       <table class="table">
         <thead>
           <tr>
             <th scope="col">Option</th>
             <th scope="col">Vote</th>
             <th scope="col">Percentage</th>
           </tr>
         </thead>
         <tbody>
  <?php //this query pulls the total votes and their respective percentages in regard to different questions
  $resultsQuery = $db->query("SELECT poll_question, option_content, poll_result.option, COUNT(*) AS voteCount FROM poll_result, poll_options, poll_question WHERE poll_result.curr_poll = $poll AND option_id = poll_result.option AND poll_result.curr_poll = poll_id GROUP BY poll_result.option
");
    while($row = $resultsQuery->fetchObject()) { //using while loop here to populate the $optionCounts array with counts for the votes
      $optionCounts[] = $row;
    }
  $optionTotal = 0;
  for($i=0; $i < count($optionCounts); $i++) {
    $optionTotal += $optionCounts[$i]->voteCount;
  }
  for($i=0; $i < count($optionCounts); $i++) {
    $optionCounts[$i]->percentage = round($optionCounts[$i]->voteCount/$optionTotal * 100, 2); //adding a new attribute to the optionCounts array that reflects the percentages of votes
  }
  for($i=0; $i < count($optionCounts); $i++) {
    echo "<tr><td>".$optionCounts[$i]->option_content."</td><td>".$optionCounts[$i]->voteCount."</td><td>".$optionCounts[$i]->percentage."%</td></tr>"; //here I am echoing the results for each vote;
  }
  ?>
      </tbody>
    </table>
  </div>
</div>
<div class= "container">
  <div id= "container" class= "col-sm-12"><h1><?php echo $optionCounts[0]->poll_question;?></h1>
    <div id= "resultGraph">

<?php for($i=0; $i < count($optionCounts); $i++) { //Utilizing this for loop to create bars on the graph
  if($i === count($optionCounts)-1) {
    echo "<div class= 'graphBar' style= 'display:inline-block;'><div class= 'bar' style= ' background-color: firebrick; width: 75px; height: ".($optionCounts[$i]->percentage*3.5)."px;'></div><span class= 'content'>".$optionCounts[$i]->option_content." - ".$optionCounts[$i]->percentage."%"."</span></div>";
  }
  else {
    echo "<div class= 'graphBar' style= 'display:inline-block; margin-right: 10px;'><div class= 'bar' style= ' background-color: firebrick; width: 75px; height: ".($optionCounts[$i]->percentage*3.5)."px;'></div><span class= 'content'>".$optionCounts[$i]->option_content." - ".$optionCounts[$i]->percentage."%"."</span></div>";
  }
}
?>
    </div>
  </div>
</div>
<?php
}

function userVoteExists($db, $poll, $user_id) { //this function checks to see if the user has voted
  $voteQuery = $db->query("SELECT curr_user, curr_poll FROM poll_result WHERE curr_user = $user_id AND curr_poll = $poll");
  // $row = $voteQuery->fetchObject();
  if($voteQuery->fetchObject()){
    return true;
  }
  else{
    return false;
  }
}
 ?>

 <!DOCTYPE html>


 <!-- Latest compiled and minified CSS -->
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

 <!-- jQuery library -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

 <!-- Popper JS -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

 <!-- Latest compiled JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>




<?php //if the user has already voted a modal will show, giving them a message that they have already voted.
 if(userVoteExists($db, $poll, $user_id)) {
   echo "<script>
   $(function(){
  $('#previousVoteMsg').modal();
  $('#closeVoteButton').click(function(){
    window.location.href= 'current_polls.php';
  });

  });
     </script>";
  showResults($db, $poll);
 }
 else{ //if the user has not yet voted, they will be redirected to the results for the current poll
   if(isset($_POST['poll_Number'], $_POST['option_Selection'])) {
       $surveyQuery = $db->query("INSERT INTO poll_result (curr_user, curr_poll, option) VALUES ($user_id, $poll, $option )");
       showResults($db, $poll);
   }
 }
?>

 <!-- Modal -->
  <div class="modal fade" id="previousVoteMsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="previousVoteMsgTitle">You've already voted</h5>
          <button type="button" class="close" id= "xButton" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="previousVoteMsg-content">You may view the results of this poll, or return to the poll page.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id= "openResultsButton" data-dismiss="modal">Results</button>
          <button type="button" class="btn btn-secondary" id= "closeVoteButton" data-dismiss="modal">Return to polls</button>
        </div>
      </div>
    </div>
  </div>
