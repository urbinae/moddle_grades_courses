<?php
include_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
include_once('AnswerPdf.php');
global $CFG, $OUTPUT, $PAGE;;
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/local/eabccoursegrades/test.php'));
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();

//generate_pfd();
echo '<div id="gradesreport"></div>';

$PAGE->requires->js_call_amd('local_eabccoursegrades/grades', 'init', array('courseid' => 1));

echo $OUTPUT->footer();






function generate_pfd()
{
    // require_once($CFG->libdir . '/pdflib.php');
    // $pdf = new pdf();
    // $pdf->AddPage();
    // $pdf->WriteHTML('<p>This is an eabccoursegrades</p>');
    // $pdf->Output('mypdf.pdf', 'D');

    $pdf = new AnswerPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->Output('file.pdf');

    // $dataformat = optional_param('dataformat', '', PARAM_CLEANFILE);
    // $columns = array(
    //     'idnumber' => get_string('idnumber'),
    // );
    // //$sql = '';
    // //$rs = $DB->get_recordset_sql($sql);
    // $rs = $DB->get_records('user', []);
    // download_as_dataformat('myfilename', $dataformat, $columns, $rs);
    // $rs->close();
}

//https://docs.moodle.org/dev/File_API
function convert_between_file_formats()
{
    $fs = get_file_storage();

    // Prepare file record object
    $fileinfo = array(
        'component' => 'mod_mymodule',
        'filearea' => 'myarea',     // usually = table name
        'itemid' => 0,               // usually = ID of row in table
        'contextid' => 0, // ID of context
        'filepath' => '/',           // any path beginning and ending in /
        'filename' => 'myfile.txt'
    ); // any filename

    // Get file
    $file = $fs->get_file(
        $fileinfo['contextid'],
        $fileinfo['component'],
        $fileinfo['filearea'],
        $fileinfo['itemid'],
        $fileinfo['filepath'],
        $fileinfo['filename']
    );

    // Try and convert it if it exists
    if ($file) {
        $convertedfile = $fs->get_converted_document($file, 'pdf');
    }
}
