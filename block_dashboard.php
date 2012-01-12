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

    function specialization() {
        $this->title = isset($this->config->title) ? format_string($this->config->title) : format_string(get_string('pluginname', 'block_dashboard'));
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

        $content = '';
        $hw = 'html_writer';
        $content .= $OUTPUT->container($hw::link($timetableurl, $timetableicon.$timetablelabel), 'dashcell');
        $content .= $OUTPUT->container($hw::link($attendanceurl, $attendanceicon.$attendancelabel), 'dashcell');
        $content .= $OUTPUT->container($hw::link($gradesurl, $gradesicon.$gradeslabel), 'dashcell');
        $content .= $OUTPUT->container($hw::link($reviewurl, $reviewicon.$reviewlabel), 'dashcell');
        $content .= $OUTPUT->container($hw::link($examsurl, $examsicon.$examslabel), 'dashcell');
        $content .= $OUTPUT->container($hw::link($handbookurl, $handbookicon.$handbooklabel), 'dashcell');

        $content .= $hw::tag('div', '', array('class' => 'clearfix'));

        if (isset($this->config->whatshot)) {
            $content .= $OUTPUT->heading(get_string('whatshot', 'block_dashboard'), 2);
            // rewrite url
            $this->config->whatshot['text'] = file_rewrite_pluginfile_urls($this->config->whatshot['text'],
                                                                           'pluginfile.php',
                                                                           $this->context->id,
                                                                           'block_dashboard',
                                                                           'content',
                                                                           NULL);
            // Default to FORMAT_HTML which is what will have been used before the
            // editor was properly implemented for the block.
            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config
            if (isset($this->config->whatshot['format'])) {
                $format = $this->config->whatshot['format'];
            }
            $content .= format_text($this->config->whatshot['text'], $format, array('trusted' => true));
        }

        $this->content->text = $content;
    }
}
