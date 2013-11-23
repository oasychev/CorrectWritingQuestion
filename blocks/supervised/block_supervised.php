<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


class block_supervised extends block_base {

    private function get_active_session(){
        require_once('sessions/sessionstate.php');
        global $DB, $COURSE, $USER;

        // Find Active session.
        $select = "SELECT
        {block_supervised_session}.id,
        {block_supervised_session}.timestart,
        {block_supervised_session}.duration,
        {block_supervised_session}.timeend,
        {block_supervised_session}.courseid,
        {block_supervised_session}.teacherid,
        {block_supervised_session}.state,
        {block_supervised_session}.sessioncomment,
        {block_supervised_session}.classroomid,
        {block_supervised_session}.lessontypeid,
        {block_supervised_session}.teacherid,
        {block_supervised_session}.sendemail,
        {block_supervised_session}.groupid,
        {user}.firstname,
        {user}.lastname,
        {course}.fullname                   AS coursename,
        {block_supervised_lessontype}.name  AS lessontypename

        FROM {block_supervised_session}
            JOIN {block_supervised_classroom}
              ON {block_supervised_session}.classroomid       =   {block_supervised_classroom}.id
            LEFT JOIN {block_supervised_lessontype}
              ON {block_supervised_session}.lessontypeid =   {block_supervised_lessontype}.id
            JOIN {user}
              ON {block_supervised_session}.teacherid    =   {user}.id
            LEFT JOIN {groups}
              ON {block_supervised_session}.groupid      =   {groups}.id
            JOIN {course}
              ON {block_supervised_session}.courseid     =   {course}.id

        WHERE (:time BETWEEN {block_supervised_session}.timestart AND {block_supervised_session}.timeend)
            AND {block_supervised_session}.courseid     = :courseid
            AND {block_supervised_session}.teacherid    = :teacherid
            AND {block_supervised_session}.state        = :stateactive
        ";


        $teacherid  = $USER->id;
        $courseid   = $COURSE->id;
        $params['time']             = time();
        $params['courseid']         = $courseid;
        $params['teacherid']        = $teacherid;
        $params['stateactive']      = StateSession::Active;

        $activesession = $DB->get_record_sql($select, $params);

        return $activesession;
    }

    private function get_planned_session(){
        require_once('sessions/sessionstate.php');
        global $DB, $COURSE, $USER;

        // Find nearest Planned sessions.
        $select = "SELECT
        {block_supervised_session}.id,
        {block_supervised_session}.timestart,
        {block_supervised_session}.duration,
        {block_supervised_session}.timeend,
        {block_supervised_session}.courseid,
        {block_supervised_session}.teacherid,
        {block_supervised_session}.state,
        {block_supervised_session}.sessioncomment,
        {block_supervised_session}.classroomid,
        {block_supervised_session}.lessontypeid,
        {block_supervised_session}.teacherid,
        {block_supervised_session}.sendemail,
        {block_supervised_session}.groupid,
        {user}.firstname,
        {user}.lastname,
        {course}.fullname                   AS coursename

        FROM {block_supervised_session}
            JOIN {block_supervised_classroom}
              ON {block_supervised_session}.classroomid       =   {block_supervised_classroom}.id
            LEFT JOIN {block_supervised_lessontype}
              ON {block_supervised_session}.lessontypeid =   {block_supervised_lessontype}.id
            JOIN {user}
              ON {block_supervised_session}.teacherid    =   {user}.id
            LEFT JOIN {groups}
              ON {block_supervised_session}.groupid      =   {groups}.id
            JOIN {course}
              ON {block_supervised_session}.courseid     =   {course}.id

        WHERE ({block_supervised_session}.timestart BETWEEN :time1 AND :time2)
            AND {block_supervised_session}.courseid     = :courseid
            AND {block_supervised_session}.teacherid    = :teacherid
            AND {block_supervised_session}.state        = :stateplanned
        ";

        $time1      = time() - 20*60;
        $time2      = time() + 20*60;
        $teacherid  = $USER->id;
        $courseid   = $COURSE->id;
        $params['time1']            = $time1;
        $params['time2']            = $time2;
        $params['courseid']         = $courseid;
        $params['teacherid']        = $teacherid;
        //$params['stateactive']      = StateSession::Active;
        $params['stateplanned']     = StateSession::Planned;

        $plannedsession = $DB->get_record_sql($select, $params);

        return $plannedsession;
    }



    public function init() {
        $this->title = get_string('blocktitle', 'block_supervised');
    }

    /**
    */
    public function applicable_formats() {
        return array(
            'all' => false,
            'course-view' => true);
    }

    public function get_content() {
        global $PAGE, $COURSE, $USER, $CFG, $DB;
        require_once('sessions/sessionstate.php');
        if ($this->content !== null) {
            return $this->content;
        }

        $formbody = '';
        // TODO teacher or student?

        // Planned sessions.
        $plannedsession = $this->get_planned_session();
        if( !empty($plannedsession) ){
            // Prepare form.
            $mform = $CFG->dirroot."/blocks/supervised/plannedsession_block_form.php";
            if (file_exists($mform)) {
                require_once($mform);
            } else {
                print_error('noformdesc');
            }
            $mform = new plannedsession_block_form();
            if ($fromform = $mform->get_data()) {
                // TODO Start session.
                // TODO Logging
                //$sessionstitle = get_string('plannedsessiontitle', 'block_supervised');
                // Start session and update fields that user could edit
                $curtime = time();
                $plannedsession->state          = StateSession::Active;
                $plannedsession->classroomid    = $fromform->classroomid;
                $plannedsession->groupid        = $fromform->groupid;
                $plannedsession->lessontypeid   = $fromform->lessontypeid;
                $plannedsession->timestart      = $curtime;
                $plannedsession->duration       = $fromform->duration;
                $plannedsession->timeend        = $curtime + $fromform->duration*60;
                if (!$DB->update_record('block_supervised_session', $plannedsession)) {
                    print_error('insertsessionerror', 'block_supervised');
                }

                //$url = new moodle_url('/course/view.php', array('id' => $COURSE->id));
                //redirect($url);
            } else {
                $sessionstitle = get_string('plannedsessiontitle', 'block_supervised');
                // Display form.
                $toform['id']               = $COURSE->id;

                $strftimedatetime = get_string("strftimerecent");
                $toform['classroomid']      = $plannedsession->classroomid;
                $toform['groupid']          = $plannedsession->groupid;
                $toform['lessontypeid']     = $plannedsession->lessontypeid;
                $toform['duration']         = $plannedsession->duration;
                $toform['timestart']        = userdate($plannedsession->timestart, $strftimedatetime);
                $toform['sessioncomment']   = $plannedsession->sessioncomment;

                $mform->set_data($toform);
                $formbody = $mform->render();
            }
        }



        // Active sessions.
        $activesession = $this->get_active_session();
        if( !empty($activesession) ){
            // Prepare form.
            $mform = $CFG->dirroot."/blocks/supervised/activesession_block_form.php";
            if (file_exists($mform)) {
                require_once($mform);
            } else {
                print_error('noformdesc');
            }
            $mform = new activesession_block_form();
            if($mform->is_cancelled()) {
                // Finish session and update timeend and duration fields
                // TODO Logging
                $curtime = time();
                $activesession->state           = StateSession::Finished;
                $activesession->timeend         = $curtime;
                $activesession->duration        = ($curtime - $activesession->timestart) / 60;

                if (!$DB->update_record('block_supervised_session', $activesession)) {
                    print_error('insertsessionerror', 'block_supervised');
                }

                //$url = new moodle_url('/course/view.php', array('id' => $COURSE->id));
                //redirect($url);
            } else if ($fromform = $mform->get_data()) {
                // Update session
                // TODO Logging
                $sessionstitle = get_string('activesessiontitle', 'block_supervised');

                $activesession->classroomid     = $fromform->classroomid;
                $activesession->groupid         = $fromform->groupid;
                $activesession->duration        = $fromform->duration;
                $activesession->timeend         = $activesession->timestart  + $fromform->duration*60;

                if (!$DB->update_record('block_supervised_session', $activesession)) {
                    print_error('insertsessionerror', 'block_supervised');
                }
                $url = new moodle_url('/course/view.php', array('id' => $COURSE->id));
                redirect($url);

            } else {
                $sessionstitle = get_string('activesessiontitle', 'block_supervised');
                // Display form.
                $toform['id']               = $COURSE->id;

                $strftimedatetime = get_string("strftimerecent");
                $toform['classroomid']      = $activesession->classroomid;
                $toform['groupid']          = $activesession->groupid;
                $toform['lessontypename']   = $activesession->lessontypeid == 0 ? get_string('notspecified', 'block_supervised'): $activesession->lessontypename;
                $toform['duration']         = $activesession->duration;
                $toform['timestart']        = userdate($activesession->timestart, $strftimedatetime);
                $toform['sessioncomment']   = $activesession->sessioncomment;

                $mform->set_data($toform);
                $formbody = $mform->render();
            }
        }


        if($sessionstitle == '')
            $sessionstitle = get_string('nosessionstitle', 'block_supervised');

        // Add block body.
        $this->content         = new stdClass;
        $this->content->text   = $sessionstitle . $formbody;







        // Add footer.
        $classroomsurl = new moodle_url('/blocks/supervised/classrooms/view.php', array('courseid' => $COURSE->id));
        $links[] = html_writer::link($classroomsurl, get_string('classroomsurl', 'block_supervised'));
        $lessontypesurl = new moodle_url('/blocks/supervised/lessontypes/view.php', array('courseid' => $COURSE->id));
        $links[] = html_writer::link($lessontypesurl, get_string('lessontypesurl', 'block_supervised'));
        $sessionsurl = new moodle_url('/blocks/supervised/sessions/view.php', array('courseid' => $COURSE->id));
        $links[] = html_writer::link($sessionsurl, get_string('sessionsurl', 'block_supervised'));

        $this->content->footer = join(' ', $links);

        return $this->content;
    }
}