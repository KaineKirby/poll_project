<?php
include 'vars.php'; //vars.php defines database and begins a new session each time the browser restarts

$databaseQuery = $db->query("SELECT poll_id, poll_question FROM poll_question"); //querying the database for each poll id and its corresponding poll question

while($row = $databaseQuery->fetchObject()) { //saving each poll id and its question to a poll array
  $poll_questions[] = $row; //array contains both the poll's question and its id
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

<html>
  <head>
    <title>Kirby's Polls</title> <!-- creating a title-->
    <link rel="stylesheet" href="stylesheets/style-main.css">
  </head>
  <body>
    <head>
      <title>Kirby's Polls</title> <!-- creating a title for the navbar-->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="stylesheets/style-main.css">
    </head>
    <body>
      <header>
      <nav class="navbar navbar-dark bg-dark">
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
          <a class="nav-link" href="create_poll.html">Create a poll</a> <!-- setting styling and links for the poll page-->
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="current_polls.php">Take a poll</a>
        </li>
      </ul>
    </div>
  </nav>
  </header>
    <div class= "container">
      <h1>Current Polls</h1>
      <div class= "row">
        <div class= "col-sm-12">
    <?php if(!empty($poll_questions)): ?>
      <ul class="list-group"> <!-- if there are questions and ids contained within the array we will start to print them-->
        <?php foreach($poll_questions as $poll_questions): ?>
          <li class= "list-group-item"><a href="poll_options.php?currPoll=<?php echo $poll_questions->poll_id; ?>"><?php echo $poll_questions->poll_question; ?></a></li>
        <?php endforeach; ?> <!-- storing some things in the url such as the currPoll according to the poll that the user is currently in-->
      </ul>
    <?php else: ?>
      <p>No surveys are accessable right now..</p> <!-- if there are no polls available this small message will print-->
    <?php endif; ?>
      </div>
    </div>
  </div>
  </body>

</html>
