<?php
  include 'mysession.php';
  if(!session_id())
  {
    session_start();
  }
  include 'headeradmin.php';
?>

Staff Page

<?php
  include 'footer.php';
?>
