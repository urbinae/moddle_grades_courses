<?php

use local_eabccoursegrades\utils;

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/pdflib.php');
//$PAGE->set_context(context_system::instance());
//$PAGE->set_url(new moodle_url('/local/eabccoursegrades/course_grades_pdf.php'));
$courseid = required_param('courseid', PARAM_INT);

$course = get_course($courseid);

$profesor = utils::get_docente($course);
$docente = '';
if ($profesor) {
    $profesor = $profesor['name'];
    $docente = $profesor;
}
$plan = '';
$plan = utils::get_value_field_select($course->id, 'plan');

$turno = '';
$turno = utils::get_value_field_select($course->id, 'turno');

$cuatrimestre = '';
$cuatrimestre = utils::get_value_field_select($course->id, 'cuatrimestre');

$grupo = '';
$data = groups_get_course_data($course->id);
foreach ($data->groups as $g) {
    $group_name = groups_get_group_name($g->id);
    if ($group_name) {
        $grupo = $group_name;
    }
}

$users = get_enrolled_users(\context_course::instance($course->id));

$pdf = new pdf();
$pdf->AddPage();

$report = '';
$header = '                
        <h1 style="text-align:center;">PLANILLA DE NOTAS DE CURSADA</h1>
        <p style="text-align:right">Docente: <b>' . $docente . '</b></p>
        <p style="text-align:right">Fecha de impresión: <b>' . date('d/m/Y', time()) . '</b></p>
        <p style="text-align:left">Materia: <b>' . $course->fullname . '</b></p>
        <table>
            <tr>
                <td style="text-align:left">Plan: <b>' . $plan . '</b> </td>
                <td style="text-align:center">Cuatrimestre: <b>' . $cuatrimestre . '</b></td>
                <td style="text-align:rigth">Turno: <b>' . $turno . '</b></td>
            </tr>
        </table>
        <table border="1">        
            <thead>
                <tr style="text-align:center">
                    <th>Ord</th>
                    <th>Legajo</th>
                    <th>Apellido y Nombre</th>
                    <th>Observaciones</th>
                    <th>Nota final</th>
                    <th>Carrera</th>
                    <th>Sede</th>
                    <th>Grupo</th>
                </tr>
            </thead>';
$body = '   <tbody>';

$trs = '';
$i=0;
foreach ($users as $key => $value) {
    $i++;
    $trs = $trs . utils::get_row($i, $value, $course->id);
}

$report = $header . $body . $trs . '</tbody></table>';

$pdf->WriteHTML($report, true, 0, true, 0);
$pdf->lastPage();
$pdf->Output('Notas-' . $course->fullname . '-' . date('d-m-Y', time()) . '.pdf', 'D');
