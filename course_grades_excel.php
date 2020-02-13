<?php

use local_eabccoursegrades\utils;

require(__DIR__ . '/../../config.php');
/** Incluir la libreria PHPExcel */
require_once($CFG->libdir . '/phpexcel/PHPExcel.php');
require_once($CFG->libdir . '/excellib.class.php');

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

/// Calculate file name

$downloadfilename = clean_filename('Notas-' . $course->fullname . date('d-m-Y', time()) . ".xls");

/// Creating a workbook

$workbook = new MoodleExcelWorkbook("-");

/// Sending HTTP headers

$workbook->send($downloadfilename);

/// Adding the worksheet

$myxls = $workbook->add_worksheet('notas');

/// Print cellls
$row = 0;

$myxls->write_string($row++, 0, 'PLANILLA DE NOTAS DE CURSADA');

$row++;
$myxls->write_string($row, 0, 'Materia');
$myxls->write_string($row, 1, $course->fullname);

$row++;
$myxls->write_string($row, 0, 'Docente');
$myxls->write_string($row, 1, $docente);

$row++;
$myxls->write_string($row, 0, 'Fecha');
$myxls->write_string($row, 1, date('d/m/Y', time()));

$row++;
$row++;
$myxls->write_string($row, 0, 'Plan');
$myxls->write_string($row, 1, $plan);
$myxls->write_string($row, 2, 'Cuatrimestre');
$myxls->write_string($row, 3, $cuatrimestre);
$myxls->write_string($row, 4, 'Turno');
$myxls->write_string($row, 5, $turno);

$row++;
$row++;
$myxls->write_string($row, 0, "Ord");
$myxls->write_string($row, 1, "Legajo");
$myxls->write_string($row, 2, "Apellido y Nombre");
$myxls->write_string($row, 3, "Observaciones");
$myxls->write_string($row, 4, "Nota final");
$myxls->write_string($row, 5, "Carrera");
$myxls->write_string($row, 6, "Sede");
$myxls->write_string($row, 7, "Curso");

//Datos
$users = get_enrolled_users(\context_course::instance($course->id));
foreach ($users as $key => $value) {
    $data_user = utils::get_data($key, $value, $course->id);
    
    $row++;
    $myxls->write_string($row, 0, $data_user->id);
    $myxls->write_string($row, 1, $data_user->idnumber);
    $myxls->write_string($row, 2, $data_user->name);
    $myxls->write_string($row, 3, $data_user->obs);
    $myxls->write_string($row, 4, $data_user->nota);
    $myxls->write_string($row, 5, $data_user->carrera);
    $myxls->write_string($row, 6, $data_user->sede);
    $myxls->write_string($row, 7, $data_user->grupo);
}

/// Close the workbook

$workbook->close();
