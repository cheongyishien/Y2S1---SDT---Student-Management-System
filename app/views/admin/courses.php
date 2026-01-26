<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?controller=admin&action=index">Dashboard</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($semester['sem_year'] . ' ' . $semester['sem_name']); ?></li>
            </ol>
        </nav>
        
        <h2><?php echo htmlspecialchars($semester['sem_year'] . ' - ' . $semester['sem_name']); ?></h2>
        <p class="text-muted"><?php echo htmlspecialchars($semester['f_name']); ?></p>
        
        <div class="mb-3">
            <button class="btn btn-success" onclick="window.location.href='index.php?controller=admin&action=add_course&sid=<?php echo $semester['sem_id']; ?>'">
                <i class="bi bi-plus-circle"></i> Add New Course
            </button>
        </div>
        
        <?php if (mysqli_num_rows($courses) == 0): ?>
            <div class="alert alert-info">No courses found for this semester.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Section</th>
                            <th>Credit</th>
                            <th>Lecturer</th>
                            <th>Enrolled / Max</th>
                            <th>Programmes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['c_code']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_section']); ?></td>
                            <td><?php echo htmlspecialchars($course['c_credit']); ?></td>
                            <td><?php echo htmlspecialchars($course['lecturer_name'] ?? 'TBA'); ?></td>
                            <td>
                                <span class="<?php echo $course['enrolled_count'] >= $course['c_max_students'] ? 'text-danger' : 'text-success'; ?>">
                                    <?php echo $course['enrolled_count']; ?> / <?php echo $course['c_max_students']; ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                if (empty($course['c_programmes'])) {
                                    echo '<span class="badge bg-secondary">All</span>';
                                } else {
                                    $progs = explode(',', $course['c_programmes']);
                                    foreach ($progs as $prog) {
                                        echo '<span class="badge bg-info me-1">' . htmlspecialchars(trim($prog)) . '</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-primary" onclick="window.location.href='index.php?controller=admin&action=edit_course&cid=<?php echo urlencode($course['c_code']); ?>&section=<?php echo urlencode($course['c_section']); ?>&sid=<?php echo $semester['sem_id']; ?>'">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger" onclick="if(confirm('Are you sure?')) window.location.href='index.php?controller=admin&action=delete_course&cid=<?php echo urlencode($course['c_code']); ?>&section=<?php echo urlencode($course['c_section']); ?>&sid=<?php echo $semester['sem_id']; ?>'">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
