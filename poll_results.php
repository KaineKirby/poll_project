<?php
$poll = $_POST['poll_Number'];
$option = $_POST['option_Selection']; //uses values from the survey file to create variables and to determine the voting results
$user_id = $_SESSION['user_id'];

$resultsQuery = $db->query("SELECT poll_options.option_content, COUNT(poll_options.option_id) * 100 / (SELECT COUNT(*) FROM poll_options WHERE poll_options.curr_poll=$poll) AS percentage FROM poll_options LEFT JOIN poll_result ON poll_options.option_id = poll_result.option WHERE poll_options.curr_poll = $poll GROUP BY poll_options.option_id");
//this query functions to get the results of each poll and determine percentages
print_r($resultsQuery);
?>
