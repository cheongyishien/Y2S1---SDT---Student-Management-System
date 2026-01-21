<?php
include 'includes/db.php';
include 'views/header.php';
?>

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card border-primary mb-3">
      <div class="card-header">Student Registration</div>
      <div class="card-body">
        <form method="POST" action="registrationprocess.php">
          <fieldset>
            
            <div class="form-group">
              <label for="fname" class="form-label mt-4">Full Name</label>
              <input type="text" name="fname" class="form-control" id="fname" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
              <label for="femail" class="form-label mt-4">Email</label>
              <input type="email" name="femail" class="form-control" id="femail" placeholder="Enter email" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="fpwd" class="form-label mt-4">Password</label>
                      <input type="password" name="fpwd" class="form-control" id="fpwd" placeholder="Password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="fpwd_confirm" class="form-label mt-4">Confirm Password</label>
                      <input type="password" name="fpwd_confirm" class="form-control" id="fpwd_confirm" placeholder="Confirm Password" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="foperator" class="form-label mt-4">Phone Operator</label>
                      <select class="form-select" name="foperator" id="foperator">
                        <option>010</option>
                        <option>011</option>
                        <option>012</option>
                        <option>013</option>
                        <option>014</option>
                        <option>016</option>
                        <option>017</option>
                        <option>018</option>
                        <option>019</option>
                      </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                      <label for="fphone" class="form-label mt-4">Phone Number</label>
                      <input type="text" name="fphone" class="form-control" id="fphone" placeholder="e.g. 1234567" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
              <label for="fgender" class="form-label mt-4">Gender</label>
              <select class="form-select" name="fgender" id="fgender">
                <option value="M">Male</option>
                <option value="F">Female</option>
              </select>
            </div>

            <div class="form-group">
              <label for="fprogramme" class="form-label mt-4">Programme</label>
              <select class="form-select" name="fprogramme" id="fprogramme">
                <?php
                  $sql = "SELECT * FROM tb_program";
                  $result = mysqli_query($con, $sql);
                  while($row = mysqli_fetch_assoc($result)) {
                      echo "<option value='".$row['p_id']."'>".$row['p_name']."</option>";
                  }
                ?>
              </select>
            </div>
            
            <div class="form-group">
              <label for="fcollege" class="form-label mt-4">Residential College</label>
              <select class="form-select" name="fcollege" id="fcollege">
                <?php
                  $sql = "SELECT * FROM tb_residential";
                  $result = mysqli_query($con, $sql);
                  while($row = mysqli_fetch_assoc($result)) {
                      echo "<option value='".$row['r_id']."'>".$row['r_name']."</option>";
                  }
                ?>
              </select>
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Register</button>
            <button type="reset" class="btn btn-warning">Clear</button>
            
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
include 'views/footer.php';
?>
