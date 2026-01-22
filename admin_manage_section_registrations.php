<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['01']);
include 'headeradmin.php';

$course_code = $_GET['cid'] ?? '';
$section = $_GET['section'] ?? '';

if (empty($course_code) || empty($section)) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get course info
$sql_course = "SELECT c.*, s.sem_year, s.sem_name, u.u_name as lecturer_name
               FROM tb_course c
               LEFT JOIN tb_semester s ON c.c_semester_id = s.sem_id
               LEFT JOIN tb_user u ON c.c_lecturer_id = u.u_id
               WHERE c.c_code = ? AND c.c_section = ?";
$stmt = mysqli_prepare($con, $sql_course);
mysqli_stmt_bind_param($stmt, "ss", $course_code, $section);
mysqli_stmt_execute($stmt);
$course_result = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($course_result);

if (!$course) {
    header("Location: admin_dashboard.php");
    exit;
}

// Get registrations for this section
$sql_registrations = "SELECT r.*, u.u_name, u.u_email, p.p_name as programme_name
                      FROM tb_registration r
                      INNER JOIN tb_user u ON r.r_student_id = u.u_id
                      LEFT JOIN tb_program p ON u.u_programme = p.p_id
                      WHERE r.r_course_code = ? AND r.r_section = ?
                      ORDER BY r.r_status, u.u_name";
$stmt = mysqli_prepare($con, $sql_registrations);
mysqli_stmt_bind_param($stmt, "ss", $course_code, $section);
mysqli_stmt_execute($stmt);
$registrations_result = mysqli_stmt_get_result($stmt);
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Manage Registrations</h2>
        <h4><?php echo htmlspecialchars($course['c_code'] . ' - ' . $course['c_name']); ?></h4>
        <?php 
        $total_enrolled = 0;
        $temp_registrations = [];
        while ($r = mysqli_fetch_assoc($registrations_result)) {
            if ($r['r_status'] == 'Approved') $total_enrolled++;
            $temp_registrations[] = $r;
        }
        ?>
        <p class="text-muted">
            Section <?php echo htmlspecialchars($section); ?> | 
            Lecturer: <?php echo htmlspecialchars($course['lecturer_name'] ?? 'TBA'); ?> |
            Semester: <?php echo htmlspecialchars($course['sem_year'] . ' ' . $course['sem_name']); ?> |
            <strong>Enrolled: <?php echo $total_enrolled; ?> / <?php echo $course['c_max_students']; ?></strong>
        </p>
        
        <?php if (empty($temp_registrations)): ?>
            <div class="alert alert-info">No registrations found for this section.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Programme</th>
                            <th>Status</th>
                            <th>Registered On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($temp_registrations as $reg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reg['u_name']); ?></td>
                            <td><?php echo htmlspecialchars($reg['u_email']); ?></td>
                            <td><?php echo htmlspecialchars($reg['programme_name'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge 
                                    <?php 
                                    echo $reg['r_status'] == 'Approved' ? 'bg-success' : 
                                         ($reg['r_status'] == 'Rejected' ? 'bg-danger' : 'bg-warning'); 
                                    ?>">
                                    <?php echo htmlspecialchars($reg['r_status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($reg['r_timestamp'])); ?></td>
                            <td>
                                <?php if ($reg['r_status'] == 'Registered' || $reg['r_status'] == 'Approved'): ?>
                                    <?php if ($reg['r_status'] == 'Registered'): ?>
                                    <button class="btn btn-success btn-sm" onclick="approveRegistration(<?php echo $reg['r_id']; ?>)">
                                        Approve
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm" onclick="showRejectModal(<?php echo $reg['r_id']; ?>, '<?php echo addslashes($reg['u_name']); ?>', '<?php echo addslashes($reg['u_email']); ?>')">
                                        Reject
                                    </button>
                                <?php elseif ($reg['r_status'] == 'Rejected'): ?>
                                    <small class="text-muted">
                                        Reason: <?php echo htmlspecialchars($reg['r_rejection_reason'] ?? 'N/A'); ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reject Registration</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="admin_registration_reject.php">
        <div class="modal-body">
          <input type="hidden" name="r_id" id="reject_r_id">
          <input type="hidden" name="student_email" id="reject_student_email">
          <input type="hidden" name="course_code" value="<?php echo htmlspecialchars($course_code); ?>">
          <input type="hidden" name="course_name" value="<?php echo htmlspecialchars($course['c_name']); ?>">
          <input type="hidden" name="section" value="<?php echo htmlspecialchars($section); ?>">
          
          <p>Rejecting registration for: <strong id="reject_student_name"></strong></p>
          
          <div class="mb-3">
            <label for="rejection_reason" class="form-label">Reason for Rejection *</label>
            <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="3" required></textarea>
          </div>
          
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="send_email" id="send_email" checked>
            <label class="form-check-label" for="send_email">
              Send email notification to student
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Reject Registration</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function approveRegistration(rId) {
    if (!confirm('Approve this registration?')) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'admin_registration_approve.php';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'r_id';
    input.value = rId;
    form.appendChild(input);
    
    document.body.appendChild(form);
    form.submit();
}

function showRejectModal(rId, studentName, studentEmail) {
    document.getElementById('reject_r_id').value = rId;
    document.getElementById('reject_student_name').textContent = studentName;
    document.getElementById('reject_student_email').value = studentEmail;
    
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>

<?php include 'footer.php'; ?>
