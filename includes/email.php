<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php'; 

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

function sendEmail($to, $subject, $message, $from = 'cheongyishien@graduate.utm.my') {
    $mail = new PHPMailer(true);

    // Always log the email
    if (!is_dir(__DIR__ . '/../logs')) {
        mkdir(__DIR__ . '/../logs', 0777, true);
    }
    $logFile = __DIR__ . '/../logs/emails.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] TO: $to | SUBJECT: $subject\nBODY: " . strip_tags($message) . "\n" . str_repeat("-", 50) . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    try {
        // SMTP Settings for Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'cheongyishien@graduate.utm.my';
        $mail->Password   = 'uobibenlqlkjkhcx';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email Content
        $mail->setFrom('cheongyishien@graduate.utm.my', 'Student Management System');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        file_put_contents($logFile, "Mailer Error: {$mail->ErrorInfo}\n", FILE_APPEND);
        return false;
    }
}
function sendRejectionEmail($studentEmail, $studentName, $courseCode, $courseName, $section, $reason) {
    $subject = "Course Registration Rejected - " . $courseCode;
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    
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
                
                <a href='$baseUrl/login.php' class='btn'>Go to Student Portal Space</a>
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
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 20px auto; border: 1px solid #e1e1e1; border-radius: 8px; overflow: hidden; }
            .header { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white; padding: 30px; text-align: center; }
            .content { padding: 30px; background-color: #ffffff; }
            .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #e1e1e1; }
            .course-info { background-color: #f2fdf4; border: 1px solid #c3e6cb; border-radius: 6px; padding: 20px; margin: 20px 0; }
            .success-msg { color: #155724; font-weight: bold; font-size: 18px; margin-bottom: 20px; }
            .btn { display: inline-block; padding: 10px 20px; background-color: #28a745; color: white !important; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            h2 { margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Registration Approved</h2>
            </div>
            <div class='content'>
                <p>Hello <strong>" . htmlspecialchars($studentName) . "</strong>,</p>
                
                <div class='success-msg'>
                    Congratulations! Your course registration request has been approved.
                </div>
                
                <p>Details of your approved registration:</p>
                
                <div class='course-info'>
                    <table style='width: 100%;'>
                        <tr><td><strong>Course Code:</strong></td><td>" . htmlspecialchars($courseCode) . "</td></tr>
                        <tr><td><strong>Course Name:</strong></td><td>" . htmlspecialchars($courseName) . "</td></tr>
                        <tr><td><strong>Section:</strong></td><td>" . htmlspecialchars($section) . "</td></tr>
                    </table>
                </div>
                
                <p>You can now view this course in your schedule and access any related materials through the student portal.</p>
                
                <p>We wish you a successful semester ahead!</p>
                
                <a href='$baseUrl/login.php' class='btn'>Go to Student Portal Space</a>
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
?>
