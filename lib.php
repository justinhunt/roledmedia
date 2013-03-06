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

defined('MOODLE_INTERNAL') || die();

/**
 * Plugin for Moodle media (audio/video) insertion dialog.
 *
 * @package   tinymce_roledmedia
 * @copyright 2013 Justin Hunt {@link http://www.poodll.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tinymce_roledmedia extends editor_tinymce_plugin {
    /** @var array list of buttons defined by this plugin */
    protected $buttons = array('roledmedia');

    protected function update_init_params(array &$params, context $context,
            array $options = null) {
			
		global $USER;
            
            
        		//use tinymce/roledmedia:filechooser capability
		if($this->get_config('role_eval')=='0'){
			//If they don't have permission
			if(!has_capability('tinymce/roledmedia:accessible', $context) ){
				return;
			 }

		//use checkboxes from settings page
		}else{
			$roles = get_user_roles($context, $USER->id, true);
			$allowed = false;
			foreach($roles as $r){
				switch($r->shortname){
					case 'guest': $allowed = $this->get_config('allow_guest'); break;
					case 'user': $allowed = $this->get_config('allow_authuser'); break;
					case 'frontpage': $allowed = $this->get_config('allow_frontpage'); break;
					case 'student': $allowed = $this->get_config('allow_student'); break;
					case 'teacher': $allowed = $this->get_config('allow_noneditteacher'); break;
					case 'editingteacher': $allowed = $this->get_config('allow_teacher'); break;
					case 'coursecreator': $allowed = $this->get_config('allow_coursecreator'); break;
					case 'manager': $allowed = $this->get_config('allow_manager'); break;
					case 'guest': $allowed = $this->get_config('allow_guest'); break;
				}
				if($allowed){break;}
			}
			//if we still are not authenticated, check if we have the admin role
			//if not we deny access
			if(!$allowed){
				if(!($this->get_config('allow_admin') && has_capability('moodle/site:config', $context))) {return;}
			}
		}//end of if us capabilities
            

        // Add file picker callback.
        if (empty($options['legacy'])) {
            if (isset($options['maxfiles']) and $options['maxfiles'] != 0) {
                $params['file_browser_callback'] = "M.editor_tinymce.filepicker";
            }
        }

        // Add button after emoticon button in advancedbuttons3.
        $added = $this->add_button_after($params, 3, 'roledmedia', 'moodleemoticon', false);

        // Note: We know that the emoticon button has already been added, if it
        // exists, because I set the sort order higher for this. So, if no
        // emoticon, add after 'image'.
        if (!$added) {
            $this->add_button_after($params, 3, 'roledmedia', 'image');
        }

        // Add JS file, which uses default name.
        $this->add_js_plugin($params);
    }

    protected function get_sort_order() {
        return 110;
    }
}
