<?php

/**
 * Content filter global settings controller.
 *
 * @category   apps
 * @package    content-filter
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/content_filter/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Content filter global settings controller.
 *
 * @category   apps
 * @package    content-filter
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/content_filter/
 */

class Settings extends ClearOS_Controller
{
    /**
     * Content filter settings controller
     *
     * @return view
     */

    function index()
    {
        $this->view();
    }

    /**
     * Content filter settings edit view.
     *
     * @return view
     */

    function edit()
    {
        $this->_common('edit');
    }

    /**
     * Content filter settings view view.
     *
     * @return view
     */

    function view()
    {
        $this->_common('view');
    }

    /**
     * Content filter common form handler.
     *
     * @param string $form_type form type
     *
     * @return view
     */

    function _common($form_type)
    {
        // Load dependencies
        //------------------

        $this->lang->load('content_filter');
        $this->load->library('base/Tuning');
        $this->load->library('content_filter/DansGuardian');

        // Set validation rules
        //---------------------
         
        $this->form_validation->set_policy('reverse', 'content_filter/DansGuardian', 'validate_reverse_lookups');
        $form_ok = $this->form_validation->run();

        // Handle form submit
        //-------------------

        if (($this->input->post('submit') && $form_ok)) {
            try {
                $this->dansguardian->set_reverse_lookups($this->input->post('reverse'));
                $this->dansguardian->auto_tune();

                $this->page->set_status_updated();

                redirect('/content_filter/settings');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load view data
        //---------------

        try {
            $data['form_type'] = $form_type;
            $data['reverse'] = $this->dansguardian->get_reverse_lookups();

            $data['levels'] = $this->tuning->get_levels();
            $tuning = $this->dansguardian->get_tuning();
            $data['level'] = $tuning['level'];
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('content_filter/settings', $data, lang('content_filter_global_settings'));
    }
}
