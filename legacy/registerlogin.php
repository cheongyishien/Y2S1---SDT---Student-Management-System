<?php
  include 'header.php';
?>

<div class="container">
<p class="tsxt-success">Registration successful. Please login.</p>
<div class="container">
<form method="POST" action="loginprocess.php">
  <fieldset>
    
    <legend>Registration Form</legend>

    <div>
      <label for="exampleInputPassword1" class="form-label mt-4">User ID</label>
      <input type="text" name="id" class="form-control" id="exampleInputPassword1" placeholder="Enter User ID" autocomplete="off" required>
    </div>

    <div>
      <label for="exampleInputPassword1" class="form-label mt-4">Password</label>
      <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter password" autocomplete="off" required>
    </div>

    <br><br><br>
    <button type="submit" class="btn btn-primary">Login</button><br><br><br>
    </fieldset>
</form>

<?php
  include 'footer.php';
?>
