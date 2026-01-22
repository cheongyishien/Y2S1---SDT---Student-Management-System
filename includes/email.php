<?php
/**
 * Email Notification System
 * Handles sending emails for various system events
 */

/**
 * Send email using PHP mail() function
 * For production, consider using PHPMailer or similar library
 */
function logEmail($to, $subject, $message) {
    if (!is_dir(__DIR__ . '/../logs')) {
        mkdir(__DIR__ . '/../logs', 0777, true);
    }
    $logFile = __DIR__ . '/../logs/emails.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] TO: $to | SUBJECT: $subject\nBODY: " . strip_tags($message) . "\n" . str_repeat("-", 50) . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

function sendEmail($to, $subject, $message, $from = 'noreply@sms.edu') {
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Always log for development/debugging
    logEmail($to, $subject, $message);
    
    // Attempt real mail with -f flag for better delivery
    return @mail($to, $subject, $message, $headers, "-f " . $from);
}

/**
 * Send registration rejection email to student
 */
function sendRejectionEmail($studentEmail, $studentName, $courseCode, $courseName, $section, $reason) {
    $subject = "Course Registration Rejected - " . $courseCode;
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 20px auto; border: 1px solid #e1e1e1; border-radius: 8px; overflow: hidden; }
            .header { background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%); color: white; padding: 30px; text-align: center; }
            .content { padding: 30px; background-color: #ffffff; }
            .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #e1e1e1; }
            .course-info { background-color: #fdf2f2; border: 1px solid #f5c6cb; border-radius: 6px; padding: 20px; margin: 20px 0; }
            .reason-box { background-color: #fff; border-left: 4px solid #dc3545; padding: 15px; margin: 15px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
            .btn { display: inline-block; padding: 10px 20px; background-color: #dc3545; color: white !important; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            h2 { margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Registration Rejected</h2>
            </div>
            <div class='content'>
                <p>Hello <strong>" . htmlspecialchars($studentName) . "</strong>,</p>
                
                <p>Your registration request for the following course has been <strong>rejected</strong> by the academic administration:</p>
                
                <div class='course-info'>
                    <table style='width: 100%;'>
                        <tr><td><strong>Course Code:</strong></td><td>" . htmlspecialchars($courseCode) . "</td></tr>
                        <tr><td><strong>Course Name:</strong></td><td>" . htmlspecialchars($courseName) . "</td></tr>
                        <tr><td><strong>Section:</strong></td><td>" . htmlspecialchars($section) . "</td></tr>
                    </table>
                </div>
                
                <div class='reason-box'>
                    <strong>Reason for Rejection:</strong><br>
                    " . nl2br(htmlspecialchars($reason)) . "
                </div>
                
                <p>If you have any questions regarding this decision, please reach out to the Registrar's Office or your Academic Advisor.</p>
                
                <p>You can view other available courses or try registering for a different section via the portal.</p>
                
                <a href='http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php' class='btn'>Go to Student Portal Space</a>
            </div>
            <div class='footer'>
                <p>Student Management System - Academic Administration</p>
                <p>This is an automated notification. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($studentEmail, $subject, $message);
}

/**
 * Send registration approval email to student
 */
function sendApprovalEmail($studentEmail, $studentName, $courseCode, $courseName, $section) {
    $subject = "Course Registration Approved - " . $courseCode;
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #28a745; color: white; padding: 20px; text-align: center; }
            .content { background-color: #f8f9fa; padding: 20px; margin-top: 20px; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Course Registration Approved</h2>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($studentName) . ",</p>
                
                <p>Congratulations! Your registration for the following course has been approved:</p>
                
                <ul>
                    <li><strong>Course Code:</strong> " . htmlspecialchars($courseCode) . "</li>
                    <li><strong>Course Name:</strong> " . htmlspecialchars($courseName) . "</li>
                    <li><strong>Section:</strong> " . htmlspecialchars($section) . "</li>
                </ul>
                
                <p>You can view your registered courses in the student portal.</p>
                
                <p>Best regards,<br>
                Student Management System<br>
                Academic Administration</p>
            </div>
            <div class='footer'>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($studentEmail, $subject, $message);
}
?>
