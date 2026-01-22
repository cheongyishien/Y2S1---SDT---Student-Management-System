<?php
include 'includes/db.php';
include 'includes/auth.php';
checkSession();
checkRole(['03']);
include 'headerstudent.php';

$student_id = $_SESSION['u_id'];
$student_programme = $_SESSION['u_programme'];

// Get student's faculty
$sql_faculty = "SELECT p.p_faculty FROM tb_program p WHERE p.p_id = ?";
$stmt = mysqli_prepare($con, $sql_faculty);
mysqli_stmt_bind_param($stmt, "s", $student_programme);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$faculty_row = mysqli_fetch_assoc($result);
$student_faculty = $faculty_row['p_faculty'] ?? '';

// Get available courses for student's programme
// Only show courses that are open to student's programme or have no restrictions
$sql = "SELECT DISTINCT c.c_code, c.c_name, c.c_credit, c.c_programmes, c.c_semester_id,
        s.sem_name, s.sem_year,
        (SELECT COUNT(*) FROM tb_course WHERE c_code = c.c_code) as section_count
        FROM tb_course c
        LEFT JOIN tb_semester s ON c.c_semester_id = s.sem_id
        WHERE (c.c_programmes IS NULL OR c.c_programmes = '' OR FIND_IN_SET(?, c.c_programmes) > 0)
        AND s.sem_status = 'Active'
        GROUP BY c.c_code
        ORDER BY c.c_code";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $student_programme);
mysqli_stmt_execute($stmt);
$courses_result = mysqli_stmt_get_result($stmt);
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Courses Registration</h2><br>
        
        <?php if (mysqli_num_rows($courses_result) == 0): ?>
            <div class="alert alert-info">No courses available for your programme at this time.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Credit</th>
                            <th>Semester</th>
                            <th>Sections Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['c_code']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_credit']); ?></td>
                            <td><?php echo htmlspecialchars($course['sem_year'] . ' ' . $course['sem_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['section_count']); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" 
                                        onclick="showSections('<?php echo htmlspecialchars($course['c_code']); ?>', '<?php echo htmlspecialchars($course['c_name']); ?>')">
                                    View Sections
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Section Selection Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" aria-labelledby="sectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sectionModalLabel">Select Section</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="sectionModalBody">
        <div class="text-center">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function showSections(courseCode, courseName) {
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('sectionModal'));
    document.getElementById('sectionModalLabel').textContent = 'Select Section for ' + courseCode + ' - ' + courseName;
    modal.show();
    
    // Load sections via AJAX
    fetch('student_get_sections.php?course_code=' + encodeURIComponent(courseCode))
        .then(response => response.json())
        .then(data => {
            let html = '';
            
            if (data.error) {
                html = '<div class="alert alert-danger">' + data.error + '</div>';
            } else if (data.sections.length === 0) {
                html = '<div class="alert alert-info">No sections available for this course.</div>';
            } else {
                html = '<div class="table-responsive"><table class="table table-striped">';
                html += '<thead><tr><th>Section</th><th>Lecturer</th><th>Enrolled</th><th>Max</th><th>Status</th><th>Action</th></tr></thead>';
                html += '<tbody>';
                
                data.sections.forEach(section => {
                    const isFull = section.c_current_students >= section.c_max_students;
                    const isRegistered = section.is_registered;
                    
                    html += '<tr>';
                    html += '<td>' + section.c_section + '</td>';
                    html += '<td>' + (section.lecturer_name || 'TBA') + '</td>';
                    html += '<td>' + section.c_current_students + '</td>';
                    html += '<td>' + section.c_max_students + '</td>';
                    html += '<td>';
                    
                    if (isRegistered) {
                        html += '<span class="badge bg-success">Registered</span>';
                    } else if (isFull) {
                        html += '<span class="badge bg-danger">Full</span>';
                    } else {
                        html += '<span class="badge bg-success">Available</span>';
                    }
                    
                    html += '</td>';
                    html += '<td>';
                    
                    if (isRegistered) {
                        html += '<button class="btn btn-secondary btn-sm" disabled>Already Registered</button>';
                    } else if (isFull) {
                        html += '<button class="btn btn-secondary btn-sm" disabled>Full</button>';
                    } else {
                        html += '<button class="btn btn-primary btn-sm" onclick="registerSection(\'' + courseCode + '\', \'' + section.c_section + '\', \'' + section.c_semester + '\')">Register</button>';
                    }
                    
                    html += '</td>';
                    html += '</tr>';
                });
                
                html += '</tbody></table></div>';
            }
            
            document.getElementById('sectionModalBody').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('sectionModalBody').innerHTML = 
                '<div class="alert alert-danger">Error loading sections: ' + error + '</div>';
        });
}

function registerSection(courseCode, section, semester) {
    if (!confirm('Register for ' + courseCode + ' Section ' + section + '?')) {
        return;
    }
    
    // Submit registration via form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'student_register_process.php';
    
    const courseInput = document.createElement('input');
    courseInput.type = 'hidden';
    courseInput.name = 'course_code';
    courseInput.value = courseCode;
    form.appendChild(courseInput);
    
    const sectionInput = document.createElement('input');
    sectionInput.type = 'hidden';
    sectionInput.name = 'section';
    sectionInput.value = section;
    form.appendChild(sectionInput);
    
    const semesterInput = document.createElement('input');
    semesterInput.type = 'hidden';
    semesterInput.name = 'semester';
    semesterInput.value = semester;
    form.appendChild(semesterInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php include 'footer.php'; ?>
