<?php

class block_dashboard extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_dashboard');
    }

    public function applicable_formats() {
        return array(
            'all' => false,
            'site' => true
        );
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function get_content() {
        global $OUTPUT, $USER;
        if ($this->content !== NULL) {
            return $this->content;
        }
        $table = new html_table();
        $row = new html_table_row();
        $strtimetable = get_string('timetable', 'block_dashboard');
        $strattendance = get_string('attendance', 'block_dashboard');
        $strexams = get_string('exams', 'block_dashboard');
        $strgrades = get_string('grades', 'block_dashboard');
        $strreview = get_string('review', 'block_dashboard');
        $strhandbook = get_string('handbook', 'block_dashboard');

        $timetableicon = $OUTPUT->pix_icon('timetable', $strtimetable, 'block_dashboard');
        $attendanceicon = $OUTPUT->pix_icon('attendance', $strattendance, 'block_dashboard');
        $examsicon = $OUTPUT->pix_icon('exams', $strexams, 'block_dashboard');
        $gradesicon = $OUTPUT->pix_icon('grades', $strgrades, 'block_dashboard');
        $reviewicon = $OUTPUT->pix_icon('review', $strreview, 'block_dashboard');
        $handbookicon = $OUTPUT->pix_icon('handbook', $strhandbook, 'block_dashboard');

        $timetablelabel = $OUTPUT->container($strtimetable, 'dashlabel');
        $attendancelabel = $OUTPUT->container($strattendance, 'dashlabel');
        $examslabel = $OUTPUT->container($strexams, 'dashlabel');
        $gradeslabel = $OUTPUT->container($strgrades, 'dashlabel');
        $reviewlabel = $OUTPUT->container($strreview, 'dashlabel');
        $handbooklabel = $OUTPUT->container($strhandbook, 'dashlabel');

        $timetableurl = new moodle_url('/blocks/mrbs/web/userweek.php');
        $attendanceurl = new moodle_url('/local/intranet/user/attendance.php', array('id' => $USER->id));
        $examsurl = new moodle_url('/local/intranet/user/exams.php', array('id' => $USER->id));
        $gradesurl = new moodle_url('/local/intranet/user/grades.php', array('id' => $USER->id));
        $reviewurl = new moodle_url('/local/progressreview/user.php', array('userid' => $USER->id));
        $handbookurl = new moodle_url('/pluginfile.php/88573/block_html/content/Student%20Info%20booklet%202011_2012.pdf');

        $hw = 'html_writer';
        $row->cells[] = $hw::link($timetableurl, $timetableicon.$timetablelabel);
        $row->cells[] = $hw::link($attendanceurl, $attendanceicon.$attendancelabel);
        $row->cells[] = $hw::link($gradesurl, $gradesicon.$gradeslabel);

        $table->data[] = $row;
        $row = new html_table_row();

        $row->cells[] = $hw::link($reviewurl, $reviewicon.$reviewlabel);
        $row->cells[] = $hw::link($examsurl, $examsicon.$examslabel);
        $row->cells[] = $hw::link($handbookurl, $handbookicon.$handbooklabel);

        $table->data[] = $row;

        $this->content->text = $hw::table($table);
    }
}
