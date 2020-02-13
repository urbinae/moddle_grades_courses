<?php

use local_eabccoursegrades\utils;

global $PAGE;

require(__DIR__ . '/../../config.php');
require_once('filter_form.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/eabccoursegrades/filter_courses.php'));

echo $OUTPUT->header();

echo '<h1> Lista de materias </h1>';

$mform = new filter_form();

if ($mform->is_cancelled()) {
} else if ($fromform = $mform->get_data()) {
    $url =  new moodle_url('/local/eabccoursegrades/filter_courses.php');
    echo '<a href="' . $url . '" class="btn btn-primary">Volver </a></div>';
    $courses_list = [];
    $courses_aux = [];

    $fields_id = [];
    $fields_values = [];

    $field_obj = new stdClass();
    $field_list = [];
    if ($fromform->plan) {
        $datos = explode('-', $fromform->plan);
        $fields_id[] = $datos[0];
        $fields_values[] = $datos[1];

        $field_obj = new stdClass();
        $field_obj->id = $datos[0];
        $field_obj->value = $datos[1];
        $field_list[] = $field_obj;
    }
    if ($fromform->cuatrimestre) {
        $datos = explode('-', $fromform->cuatrimestre);
        $fields_id[] = $datos[0];
        $fields_values[] = $datos[1];

        $field_obj = new stdClass();
        $field_obj->id = $datos[0];
        $field_obj->value = $datos[1];
        $field_list[] = $field_obj;
    }
    if ($fromform->turno) {
        $datos = explode('-', $fromform->turno);
        $fields_id[] = $datos[0];
        $fields_values[] = $datos[1];

        $field_obj = new stdClass();
        $field_obj->id = $datos[0];
        $field_obj->value = $datos[1];
        $field_list[] = $field_obj;
    }

    if (!empty($field_list)) {
        foreach (get_courses() as $key => $course) {
            if ($course->id == 1) {
                continue;
            }
            $flag = 0;
            foreach ($field_list as $key => $f) {
                $sql = "SELECT cfd.id, cfd.fieldid, cfd.intvalue, cfd.instanceid FROM mdl_customfield_data cfd
                JOIN mdl_customfield_field cf on cf.id = cfd.fieldid
                JOIN mdl_course as co on co.id = cfd.instanceid
                WHERE cfd.intvalue = $f->value
                AND cfd.fieldid = $f->id
                AND cfd.instanceid = $course->id";

                $custom_data = $DB->get_records_sql($sql);

                if (!empty($custom_data) || count($custom_data) > 0){
                    $flag = 1;
                }else{
                    $flag = 0;
                    break;
                }
                    
            }
            if ($flag == 1) {
                if (!in_array($course->id, $courses_aux)) {
                    $courses_aux[] = $course->id;
                }
            }
        }

        foreach ($courses_aux as $key => $id) {
            $courses_list[] = get_course($id);
        }
    } else {
        $courses = get_courses();
        $courses_list = $courses;
    }

    $trs = '';
    // $courses_def = [];
    // if ($fromform->sede) {
    //     $courses_aux = [];
    //     foreach ($courses_list as $key => $co) {
    //         $users_enroled = get_enrolled_users(\context_course::instance($co->id));
    //         if (!empty($users_enroled)) {
    //             foreach ($users_enroled as $key => $user) {
    //                 $fields = utils::get_user_fields_with_data($user->id);
    //                 if (!empty($fields)) {
    //                     if ($fields->sede == $fromform->sede) {
    //                         if (!in_array($co->shortname, $courses_aux)) {
    //                             echo '<br>';
    //                             var_dump($fields->sede);
    //                             echo '<br>' . $user->firstname . ' ' . $user->lastname;
    //                             $courses_aux[] = $co->shortname;
    //                             //var_dump($fields->sede);
    //                             $courses_def[] = $co;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // if (!empty($courses_def)) {
    //     echo 'No vacio';
    //     $courses_list = $courses_def;
    // }
    foreach ($courses_list as $key => $course) {
        if ($course->id == 1) {
            continue;
        }
        $trs .= utils::get_row_course_list($course, $fromform);
    }
    $table = '<table class="table">        
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>Plan</th>
                            <th>Turno</th>
                            <th>Cuatrimestre</th>
                        </tr>
                    </thead>';
    $body = '       <tbody>';


    $body = $body . $trs . '                           
                        </tbody>
                    </table>';
    $table .= $body;
    echo $table;
} else {
    $mform->display();
}

echo $OUTPUT->footer();
