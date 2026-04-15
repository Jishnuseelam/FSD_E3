<?php
session_start();
include 'config/database.php';

$result_id = isset($_GET['result_id']) ? $_GET['result_id'] : 0;

if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'student') {
    $query = "SELECT * FROM quiz_results WHERE id = $result_id AND student_id = {$_SESSION['user_id']}";
} else {
    die("Unauthorized access!");
}

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    die("Result not found!");
}

$quiz_result = mysqli_fetch_assoc($result);

if($quiz_result['percentage'] < 60) {
    die("You need to score at least 60% to get a certificate!");
}

// Update certificate issued status
mysqli_query($conn, "UPDATE quiz_results SET certificate_issued = 1 WHERE id = $result_id");

// Certificate HTML
$certificate_html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Achievement</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
        }
        .certificate {
            width: 800px;
            height: 600px;
            padding: 50px;
            background: white;
            border: 20px solid #f0f0f0;
            text-align: center;
            position: relative;
        }
        .certificate h1 {
            font-size: 48px;
            color: #667eea;
            margin-top: 50px;
        }
        .certificate h2 {
            font-size: 36px;
            margin: 30px 0;
        }
        .certificate .student-name {
            font-size: 32px;
            color: #48bb78;
            margin: 20px 0;
            font-weight: bold;
        }
        .certificate .score {
            font-size: 24px;
            margin: 20px 0;
        }
        .certificate .date {
            position: absolute;
            bottom: 80px;
            right: 50px;
            font-size: 14px;
        }
        .certificate .signature {
            position: absolute;
            bottom: 80px;
            left: 50px;
            font-size: 14px;
        }
        .border {
            border: 1px solid #ddd;
            width: 100%;
            height: 100%;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border">
            <h1>CERTIFICATE OF ACHIEVEMENT</h1>
            <h2>This certificate is proudly presented to</h2>
            <div class="student-name">' . htmlspecialchars($quiz_result['student_name']) . '</div>
            <p>For successfully completing the quiz with a score of</p>
            <div class="score">' . $quiz_result['score'] . '/' . $quiz_result['total_questions'] . ' (' . number_format($quiz_result['percentage'], 2) . '%)</div>
            <p>Demonstrating excellent knowledge and understanding of the subject matter.</p>
            <div class="signature">
                <strong>Quiz System Admin</strong><br>
                Authorized Signatory
            </div>
            <div class="date">
                Date: ' . date('F d, Y', strtotime($quiz_result['quiz_date'])) . '
            </div>
        </div>
    </div>
</body>
</html>
';

// Generate PDF
require_once('vendor/autoload.php'); // If using TCPDF or other library

// For simplicity, we'll use html2pdf.js on client side
// But here we'll output HTML that can be printed as PDF
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Achievement</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            background: #f0f0f0;
        }
        #certificate {
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .controls {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body>
    <div>
        <div id="certificate">
            <?php echo $certificate_html; ?>
        </div>
        <div class="controls">
            <button onclick="downloadPDF()">Download PDF Certificate</button>
            <button onclick="window.print()">Print Certificate</button>
        </div>
    </div>
    
    <script>
        function downloadPDF() {
            const element = document.getElementById('certificate');
            html2pdf()
                .set({
                    margin: [0, 0, 0, 0],
                    filename: 'certificate_<?php echo $quiz_result['student_name']; ?>.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, letterRendering: true },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
                })
                .from(element)
                .save();
        }
    </script>
</body>
</html>