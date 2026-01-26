<?php
include 'includes/db.php';
include 'header.php';
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
              <label for="ffaculty" class="form-label mt-4">Faculty</label>
              <select class="form-select" name="ffaculty" id="ffaculty" required>
                <option value="">-- Select Faculty --</option>
                <?php
                  $sql = "SELECT * FROM tb_faculty WHERE f_id != ''";
                  $result = mysqli_query($con, $sql);
                  while($row = mysqli_fetch_assoc($result)) {
                      echo "<option value='".$row['f_id']."'>".$row['f_name']."</option>";
                  }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label for="fprogramme" class="form-label mt-4">Programme</label>
              <select class="form-select" name="fprogramme" id="fprogramme" required>
                <option value="">-- Select Faculty First --</option>
                <?php
                  // Load all programs for JavaScript filtering
                  $sql = "SELECT * FROM tb_program";
                  $result = mysqli_query($con, $sql);
                  while($row = mysqli_fetch_assoc($result)) {
                      echo "<option value='".$row['p_id']."' data-faculty='".$row['p_faculty']."'>".$row['p_name']."</option>";
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

<script>
// Filter programmes based on selected faculty
document.getElementById('ffaculty').addEventListener('change', function() {
    const selectedFaculty = this.value;
    const programmeSelect = document.getElementById('fprogramme');
    const allOptions = programmeSelect.querySelectorAll('option');
    
    // Reset programme dropdown
    programmeSelect.value = '';
    
    // Show/hide options based on faculty
    allOptions.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            option.textContent = selectedFaculty ? '-- Select Programme --' : '-- Select Faculty First --';
        } else {
            const optionFaculty = option.getAttribute('data-faculty');
            if (selectedFaculty === '' || optionFaculty === selectedFaculty) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        }
    });
});

// Initialize on page load
document.getElementById('ffaculty').dispatchEvent(new Event('change'));
</script>

<?php
include 'footer.php';
?>
