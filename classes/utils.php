<?php

namespace local_eabccoursegrades;

use context_course;
use stdClass;
use grade_item;
use grade_plugin_return;
use grade_report_overview;
use moodle_url;

/**
 * 
 */
class utils
{

    /**
     * Permite obtener las notas de un curso
     * @param int $courseid
     * @param string $params
     */
    public static function get_course_grades()
    {
        global $DB;

        /**Campos personalizados */
        // $where1 = "visible > 0";
        // $fieldstofill = $DB->get_records_select('user_info_field', $where1, null, '', 'id');

        // $emptyfieldclause = "data IS NOT NULL";
        // if (count($fieldstofill) > 0) {
        //     // Get desired fields IDs.
        //     $ftfids = array();
        //     foreach ($fieldstofill as $ftf) {
        //         $ftfids[] = $ftf->id;
        //     }
        //     // Check if those fields are filled in the current user's profile.
        //     $where2 = "userid = 2 AND $emptyfieldclause AND fieldid IN(" . implode(',', $ftfids) . ")";
        //     $nbfilledfields = $DB->count_records_select('user_info_data', $where2, null, 'COUNT(*)');
        //     // Compare results
        //     if ($nbfilledfields < count($ftfids)) {
        //         $profileiscomplete = false;
        //     }
        // }

        $sql = "SELECT 
        (SELECT ud.data
        FROM prefix_user_info_data AS ud 
        WHERE ud.fieldid=3
        AND ud.userid=u.id
        ) AS Sede,
        (SELECT ud.data
        FROM prefix_user_info_data AS ud 
        WHERE ud.fieldid=1
        AND ud.userid=u.id
        ) AS Carrera,
        #co.id,
        co.fullname AS Curso,
        g.name AS grupo,
        u.username AS Usuario,
        u.lastname AS Apellido,
        u.firstname AS Nombre,
        
        CASE 
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 1 THEN 'Ausente - Nunca Cursó'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 2 THEN 'Dejó la Cursada'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 3 THEN '2 - Reprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 4 THEN '3 - Reprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 5 THEN '4 - Aprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 6 THEN '5 - Aprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 7 THEN '6 - Aprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 8 THEN '7 - Aprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 9 THEN '8 - Aprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 10 THEN '9 - Aprobado'
            WHEN (SELECT ag.grade
        FROM prefix_assign AS assign
        RIGHT JOIN prefix_assign_grades AS ag ON ag.assignment=assign.id
        LEFT JOIN prefix_grade_items AS gi ON gi.iteminstance=assign.id
        WHERE gi.scaleid=19
        AND assign.course=co.id AND ag.userid=u.id) = 11 THEN '10 - Aprobado'
            ELSE ''
            END as Nota_Final
        
        FROM
        prefix_role_assignments ra
        JOIN prefix_context con ON con.id=ra.contextid
        JOIN prefix_course AS co ON co.id=con.instanceid
        JOIN prefix_user AS u ON u.id=ra.userid
        JOIN prefix_groups_members AS gm ON gm.userid=u.id
        JOIN prefix_groups AS g ON g.id=gm.groupid AND g.courseid=co.id
        JOIN prefix_grade_items AS gi ON gi.courseid=co.id
        
        WHERE con.contextlevel=50
        AND ra.roleid=5
        AND gi.scaleid=19
        #AND co.id=200
        #%%FILTER_COURSES:co.id%%
        ORDER BY sede,carrera,curso,grupo,usuario,apellido,nombre,nota_final";

        //$users_db = $DB->get_records_sql($sql, ['']);

        // $gradeitemparamscourse = [
        //     'itemtype' => 'course',
        //     'courseid' => 2 //$courseid,
        // ];
        // $grade_course = \grade_item::fetch($gradeitemparamscourse);

        // $grades_user = \grade_grade::fetch_users_grades($grade_course, array(3), false);



        $users_db = $DB->get_records('user');
        // echo '<pre>' . print_r($users_db, 1) . '</pre>';
        // exit();

        //error_log(print_r($users_db, 1));
        $grades = [];
        // foreach ($users_db as $row) {
        //     $grade = new stdClass();
        //     $grade->name = $row->firstname;
        //     $grade->username = $row->username;
        //     $grades[] = $grade;
        // }

        return $users_db;
    }

    /**
     * Obtiene el nombre del profesor
     */
    public static function get_docente($course)
    {
        global $DB;
        $context = context_course::instance($course->id);
        $courseteachersEdit = [];
        $teacherEdit = get_role_users($DB->get_record('role', array('shortname' => 'editingteacher'))->id, $context, false, 'u.id, u.firstname,u.lastname,u.email, u.picture,u.imagealt,u.firstnamephonetic');
        foreach ($teacherEdit as $staffEdit) {
            $staffEdit->lastnamephonetic = '';
            $staffEdit->middlename = '';
            $staffEdit->alternatename = '';
            $courseteachersEdit[] = array(
                'name' => $staffEdit->firstname . ' ' . $staffEdit->lastname,
                'email' => $staffEdit->email,
            );
        }

        if ($courseteachersEdit) {
            $profesor['name'] = $courseteachersEdit[0]['name'];
            return $profesor;
        } else
            return '';
    }

    /**
     * Obtiene el nombre del profesor
     */
    public static function get_profesor($course)
    {
        global $DB;
        $context = context_course::instance($course->id);
        $courseteachersEdit = [];
        $teacherEdit = get_role_users($DB->get_record('role', array('shortname' => 'editingteacher'))->id, $context, false, 'u.id, u.firstname,u.lastname,u.email, u.picture,u.imagealt,u.firstnamephonetic');
        if (!empty($teacherEdit)) {
            foreach ($teacherEdit as $teacher) {
                return $teacher;
            }
        }
        return $teacherEdit;
    }

    /**
     * Obtiene la lista de cursos para el select
     */
    public static function get_courses()
    {
        global $DB;
        $cursos = $DB->get_records('course');
        $result = [];
        $result[""] = get_string("choose");
        foreach ($cursos as $curso) {
            $result[$curso->id] = $curso->fullname;
        }
        return $result;
    }

    /**
     * Obtiene una fila para la tabla de la lista de usuarios
     */
    public static function get_row($i, $user, $courseid)
    {
        global $DB;
        $course_completion = $DB->get_record('course_completions', array('userid' => $user->id, 'course' => $courseid));

        $nota = '-';
        // if (!$course_completion) {
        //     $nota = 'Sin completar';
        // } else {
        //     $nota = self::get_grade($courseid, $user->id);
        // }

        $eabcgroups = groups_get_all_groups($courseid, $user->id);
        $grupo = '';

        foreach ($eabcgroups as $key => $value) {
            $grupo = $grupo . $value->name . '.';
        }


        $fields = self::get_user_fields_with_data($user->id);

        $tr = '<tr style="text-align:center">
        <td>' . $i . '</td>
        <td>' . $fields->legajo . '</td>
        <td>' . $user->lastname . ' ' . $user->firstname . '</td>
        <td>Observación</td>
        <td>' . $nota . '</td>
        <td>' . $fields->carrera . '</td>
        <td>' . $fields->sede . '</td>
        <td>' . $grupo . '</td>
        </tr>';

        return $tr;
    }

    /**
     * Obtiene una fila para la tabla de la lista de usuarios
     */
    public static function get_row_course_list($course, $form)
    {
        global $DB;

        $url =  new moodle_url('/local/eabccoursegrades/course_grades_pdf.php', ['courseid' => $course->id]);
        $button_pdf = '<a target="_blank" href="' . $url . '" class="btn btn-primary">PDF </a>';

        $url =  new moodle_url('/local/eabccoursegrades/course_grades_excel.php', ['courseid' => $course->id]);
        $button_exel = '<a target="_blank" href="' . $url . '" class="btn btn-primary">Excel </a>';

        // $plan = $form->plan;
        // $turno = $form->turno;
        // $cuatrimestre = $$form->cuatrimestre;

        // if (condition) {
        //     # code...
        // }
        $tr = '<tr>
            <td>'
            . $course->fullname .
            '</td>
            <td>'
            . utils::get_value_field_select($course->id, 'plan') .
            '</td>
            <td>'
            . utils::get_value_field_select($course->id, 'turno') .
            '</td>
            <td>'
            . utils::get_value_field_select($course->id, 'cuatrimestre') .
            '</td>
            <td>'
            . $button_pdf . $button_exel .
            '</td>
        </tr>';

        return $tr;
    }

    /**
     * Obtiene una fila para la tabla de la lista de usuarios
     */
    public static function get_data($key, $user, $courseid)
    {
        global $DB;
        $course_completion = $DB->get_record('course_completions', array('userid' => $user->id, 'course' => $courseid));

        if (!$course_completion) {
            $nota = 'Sin completar';
        } else {
            $nota = self::get_grade($courseid, $user->id);
        }

        $eabcgroups = groups_get_all_groups($courseid, $user->id);
        $grupo = '';

        foreach ($eabcgroups as $key => $value) {
            $grupo = $grupo . $value->name . '.';
        }
        $fields = self::get_user_fields_with_data($user->id);

        $data = new stdClass();
        $data->id = $key;
        $data->idnumber = $fields->legajo;
        $data->name = $user->lastname . ' ' . $user->firstname;
        $data->nota = $nota;
        $data->carrera = $fields->carrera;
        $data->sede = $fields->sede;
        $data->obs = '';
        $data->grupo = $grupo;

        return $data;
    }

    /**
     * Obtiene el valor de un campo personalizado de curso
     */
    public static function get_value_field($course, $field)
    {
        global $DB;
        $field = $DB->get_record('customfield_field', ['shortname' => $field]);
        if ($field) {
            $datos = $DB->get_record('customfield_data', ['fieldid' => $field->id, 'instanceid' => $course->id]);
            if ($datos) {
                if ($datos->value) {
                    return $datos->value;
                }
            }
        }
    }

    /**
     * Obtiene el valor de un campo personalizado de curso
     */
    public static function get_group_name($courseid)
    {
        global $DB;
        $field = $DB->get_record('groups', ['courseid' => $courseid]);
        if ($field) {
            $datos = $DB->get_record('customfield_data', ['fieldid' => $field->id, 'instanceid' => $course->id]);
            if ($datos) {
                if ($datos->value) {
                    return $datos->value;
                }
            }
        }
    }

    /**
     * Returns an array of all custom field records with any defined data (or empty data), for the specified user id.
     * @param int $userid
     * @param $user_info_field
     * @param $user_info_category
     * @param $user_info_data
     * @return stdClass
     * @throws dml_exception
     */
    public static function get_user_fields_with_data($userid)
    {
        global $DB;
        $sql = 'SELECT uif.id, uif.shortname, uind.data ';
        $sql .= 'FROM {user_info_field} uif ';
        $sql .= 'LEFT JOIN {user_info_category} uic ON uif.categoryid = uic.id ';
        if ($userid > 0) {
            $sql .= 'LEFT JOIN {user_info_data} uind ON uif.id = uind.fieldid AND uind.userid = :userid ';
        }
        $sql .= 'ORDER BY uic.sortorder ASC, uif.sortorder ASC ';
        $fields = $DB->get_records_sql($sql, ['userid' => $userid]);
        $data = [];
        foreach ($fields as $field) {
            $fieldobject = new Campo($field->shortname, $field->data);
            $data[] = $fieldobject;
        }
        $usercustomfields = new stdClass();
        foreach ($data as $formfield) {
            $usercustomfields->{$formfield->shortname} = $formfield->data;
        }

        return $usercustomfields;
    }

    /**
     * Get nota final
     */
    public static function get_grade($courseid, $userid)
    {
        global $CFG;
        require_once($CFG->libdir . '/grade/grade_item.php');
        require_once($CFG->libdir . '/grade/grade_grade.php');
        $gradeitemparamscourse = [
            'itemtype' => 'course',
            'courseid' => $courseid,
        ];
        $grade_course = \grade_item::fetch($gradeitemparamscourse);
        if ($grade_course) {
            $grades_user = \grade_grade::fetch_users_grades($grade_course, array($userid), false);
            if ($grades_user) {
                $finalgradeuser = $grades_user[key($grades_user)]->finalgrade;
                return $finalgradeuser;
            }
        }
        // require_once($CFG->libdir . '/grade/querylib.php');
        // grade_get_course_grade($userid,[$courseid]);
        // grade_get_course_grade($userid, [$courseid]);
        self::get_grades($courseid, $userid);

        return 'Sin completar';
    }

    public static function get_grades($course, $userid)
    {
        $context = context_course::instance($course->id);

        // Force a regrade if required.
        grade_regrade_final_grades_if_required($course);
        // Get the course final grades now.
        $gpr = new grade_plugin_return(array(
            'type' => 'report', 'plugin' => 'overview', 'courseid' => $course->id,
            'userid' => $userid
        ));
        $report = new grade_report_overview($userid, $gpr, $context);
        $coursesgrades = $report->setup_courses_data(true);

        $grades = array();
        foreach ($coursesgrades as $coursegrade) {
            $gradeinfo = array(
                'courseid' => $coursegrade['course']->id,
                'grade' => grade_format_gradevalue($coursegrade['finalgrade'], $coursegrade['courseitem'], true),
                'rawgrade' => $coursegrade['finalgrade'],
            );
            if (isset($coursegrade['rank'])) {
                $gradeinfo['rank'] = $coursegrade['rank'];
            }
            $grades[] = $gradeinfo;
        }

        $result = array();
        $result['grades'] = $grades;
        return $result;
    }

    /**
     * Obtiene los valores de un campo personalizado de curso tipo select
     */
    public static function get_options($param)
    {
        global $DB;
        $field = $DB->get_record('customfield_field', ['shortname' => $param]);
        if (!$field) {
            return [];
        }
        $options = json_decode($field->configdata)->options;
        $optionslist = preg_split('/\n|\r\n?/', $options);

        if (!empty($optionslist)) {
            //return $optionslist;
            $list = [];
            foreach ($optionslist as $key => $op) {
                $pos_value = $key + 1;
                $clave = $field->id . '-' . $pos_value;
                $list[$clave] = $op;
            }
            return $list;
        }
        return [];
    }
    /**
     * Obtiene el valor en nombre del grupo dado un filtro
     */
    public static function get_group_with_filter($course, $filter)
    {
        global $DB;
        $data = groups_get_course_data($course->id);
        foreach ($data->groups as $group) {
            $pos = strpos($group->name, $filter);
            if ($pos !== false) {
                $grupo = $group->name;
            }
        }
    }

    /**
     * get value of select field
     */
    public static function get_value_field_select($courseid, $customfield)
    {
        global $DB;
        $field = $DB->get_record('customfield_field', ['shortname' => $customfield]);
        if (!$field) {
            return '';
        }
        $options = json_decode($field->configdata)->options;
        $optionslist = preg_split('/\n|\r\n?/', $options);

        $data = $DB->get_record('customfield_data', ['fieldid' => $field->id, 'instanceid' => $courseid]);
        if (!$data) {
            return '';
        }
        if ($data->value == 0) {
            return '';
        }
        foreach ($optionslist as $key => $op) {
            if ($data->value == $key + 1) {
                return $op;
            }
        }
    }

    /**
     * get value_id of select field
     */
    public static function get_valueid_field_select($courseid, $customfield)
    {
        global $DB;
        $field = $DB->get_record('customfield_field', ['shortname' => $customfield]);
        if (!$field) {
            return '';
        }
        $options = json_decode($field->configdata)->options;
        $optionslist = preg_split('/\n|\r\n?/', $options);

        $data = $DB->get_record('customfield_data', ['fieldid' => $field->id, 'instanceid' => $courseid]);
        if (!$data) {
            return '';
        }
        if ($data->value == 0) {
            return '';
        }
        foreach ($optionslist as $key => $op) {
            if ($data->value == $key + 1) {
                return $data->value;
            }
        }
    }
}

/**
 * Clase para crear objeto de campo personalizado
 */
class Campo
{
    public $shortname;
    public $data;
    function __construct($shortname, $data)
    {
        $this->shortname = $shortname;
        $this->data = $data;
    }
}
