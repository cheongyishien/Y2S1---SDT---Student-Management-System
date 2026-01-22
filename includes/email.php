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
    
    // Always log for development/debugging
    logEmail($to, $subject, $message);
    
    // Attempt real mail
    return @mail($to, $subject, $message, $headers);
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
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; }
            .content { background-color: #f8f9fa; padding: 20px; margin-top: 20px; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
            .reason-box { background-color: #fff; border-left: 4px solid #dc3545; padding: 15px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Course Registration Rejected</h2>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($studentName) . ",</p>
                
                <p>We regret to inform you that your registration for the following course has been rejected:</p>
                
                <ul>
                    <li><strong>Course Code:</strong> " . htmlspecialchars($courseCode) . "</li>
                    <li><strong>Course Name:</strong> " . htmlspecialchars($courseName) . "</li>
                    <li><strong>Section:</strong> " . htmlspecialchars($section) . "</li>
                </ul>
                
                <div class='reason-box'>
                    <strong>Reason for Rejection:</strong><br>
                    " . nl2br(htmlspecialchars($reason)) . "
                </div>
                
                <p>If you have any questions or would like to discuss this decision, please contact your academic advisor or the administration office.</p>
                
                <p>You may re-register for this course or choose an alternative course through the student portal.</p>
                
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
