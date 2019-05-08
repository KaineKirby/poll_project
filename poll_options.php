<?php

include 'vars.php'; //vars.php defines database and begins a new session each time the browser restarts
if(isset($_GET['currPoll'])) {
  $poll = $_GET['currPoll'];
  $databaseQuery = $db->query("SELECT poll_question, option_id, curr_poll, option_content FROM poll_options, poll_question WHERE curr_poll = poll_id AND curr_poll = $poll");
  //querying the database for each poll id and its corresponding poll question
  while($row = $databaseQuery->fetchObject()) { //saving each poll id and its question to a poll array
    $poll_options[] = $row;
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
<!-- the html below is for setting the navigation bar for this page-->
<html>
  <head>
    <title>Kirby's Polls</title>
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
        <a class="nav-link" href="create_poll.html">Create a poll</a> <!-- setting links for the nav bar-->
      </li>
      <li class="nav-item">
        <a class="nav-link" href="current_polls.php">Take a poll</a>
      </li>
    </ul>
  </div>
</nav>
</header>

    <div class="poll">
      <div class="poll_question">
        <?php echo $poll_options[0]->poll_question;?> <!-- getting the questions for the current poll from the object array with values from the query above-->
      </div>
      <form action="survey.php" method="post">
          <div class="poll_options">
              <div class="poll_option btn-group-vertical btn-group-toggle" data-toggle="buttons">
              <?php foreach($poll_options as $index => $poll_option): ?> <!-- this loop effectively displays all of the options for the current poll -->
                    <label class="btn btn-secondary <?php if($index==0){echo "active";}?>" for=<?php echo $index; ?>> <!-- setting the first option as selected so that the user can't neglect to make a choice-->
                    <input type="radio" name="option_Selection" value="<?php echo $poll_option->option_id; ?>" id="<?php echo $index; ?>"<?php if($index==0){echo "checked";}?>>
                    <?php echo $poll_option->option_content; ?></label>
                <?php endforeach; ?>
              </div>
          </div>

          <input class="btn btn-secondary btn-sm" type="submit" value="Submit Selection">
          <input type="hidden" name="poll_Number" value= "<?php echo $poll?>">
      </form>
    </div>
  </body>

</html>
