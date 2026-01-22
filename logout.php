<?php
// Redirect to MVC logout route
header('Location: public/index.php?controller=auth&action=logout');
exit();
