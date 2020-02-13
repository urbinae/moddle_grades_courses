<?php

use local_eabccoursegrades\utils;

global $PAGE;

require(__DIR__ . '/../../config.php');
require_once('request.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/eabccoursegrades/index.php'));

echo $OUTPUT->header();

echo '<h1> Consultar cursada</h1>';

$mform = new request_form();

if ($mform->is_cancelled()) {
} else if ($fromform = $mform->get_data()) {
    $course = get_course($fromform->courseid);

    $profesor = utils::get_docente($course);
    $docente = '';
    if ($profesor) {
        $profesor = $profesor['name'];
        $docente = $profesor;
    }

    $turno = '';
    $turno = utils::get_value_field_select($course->id, 'turno');
    

    $plan = '';
    $plan = utils::get_value_field_select($course->id, 'plan');

    $cuatrimestre = '';
    $cuatrimestre = utils::get_value_field_select($course->id, 'cuatrimestre');

    $url =  new moodle_url('/local/eabccoursegrades/course_grades_pdf.php', ['courseid' => $course->id, 'plan' => $plan, 'cuatrimestre' => $cuatrimestre, 'turno' => $turno]);
    echo '<br><div class="row"><br><a target="_blank" href="' . $url . '" class="btn btn-primary">PDF </a>';
    $url =  new moodle_url('/local/eabccoursegrades/course_grades_excel.php', ['courseid' => $course->id]);
    echo ' | <a target="_blank" href="' . $url . '" class="btn btn-primary">Excel </a>';
    $url =  new moodle_url('/local/eabccoursegrades/index.php');
    echo ' | <a href="' . $url . '" class="btn btn-primary">Volver </a></div>';

    $users = get_enrolled_users(\context_course::instance($course->id));
    $trs = '';
    foreach ($users as $key => $value) {
        $trs = $trs . utils::get_row($key, $value, $course->id);
    }
    $report = '';
    $header = '     <p style="text-align:right">Docente: <b>' . $docente . '</b></p>
                    <p style="text-align:right">Fecha de impresión: <b>' . date('d/m/Y', time()) . '</b></p>
                    <p style="text-align:left">Materia: <b>' . $course->fullname . '</b></p>
                    <table class="table">
                        <tr>
                        <td>Plan: <b>' . $plan . '</b> </td>
                        <td>Cuatrimestre: <b>' . $cuatrimestre . '</b></td>
                        <td>Turno: <b>' . $turno . '</b></td>
                    </tr>
                    </table>
                    <table class="table">        
                        <thead>
                            <tr>
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
    $body = '               <tbody>';
    

    $body = $body . $trs . '                           
                        </tbody>
                    </table>
            ';
    $footer = '';
    $report = $header . $body . $footer;
    echo $report;
} else {
    $mform->display();
}

echo $OUTPUT->footer();
