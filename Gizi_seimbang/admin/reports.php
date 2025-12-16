<?php
// admin/reports.php
require_once '../inc/config.php';
require_once '../inc/functions.php';
require_once '../inc/admin_auth.php';
require_admin_login();

// Simple PDF Export Logic (Mockup if dompdf not installed, but structure is here)
if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    // Check if dompdf is available
    if (file_exists('../vendor/autoload.php')) {
        require_once '../vendor/autoload.php';
        // use Dompdf\Dompdf;
        // $dompdf = new Dompdf();
        // $dompdf->loadHtml('<h1>Report</h1>...');
        // $dompdf->render();
        // $dompdf->stream();
        echo "Dompdf library found. Generating PDF... (Implementation requires actual library files)";
    } else {
        echo "Dompdf not installed. Please run 'composer require dompdf/dompdf'.";
    }
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Reports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Reports</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h5>Export Data</h5>
                <p>Generate PDF reports for system usage.</p>
                <a href="?export=pdf" class="btn btn-danger">Export to PDF</a>
            </div>
        </div>

        <div class="alert alert-info">
            Note: PDF generation requires <code>composer require dompdf/dompdf</code> to be run in the project root.
        </div>
    </div>
</body>

</html>