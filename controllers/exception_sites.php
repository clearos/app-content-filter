<?php

/**
 * Content filter exception sites controller.
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
 * Content filter exception sites controller.
 *
 * @category   apps
 * @package    content-filter
 * @subpackage controllers
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/content_filter/
 */

class Exception_Sites extends ClearOS_Controller
{
    /**
     * Content filter exception sites management default controller.
     *
     * @param integer $policy policy ID
     *
     * @return view
     */

    function index($policy = 1)
    {
        $this->edit($policy);
    }

    /**
     * Exception site add view.
     *
     * @param integer $policy policy ID
     *
     * @return view
     */

    function add($policy = 1)
    {
        // Load libraries
        //---------------

        $this->lang->load('content_filter');
        $this->load->library('content_filter/DansGuardian');

        // Set validation rules
        //---------------------

        $this->form_validation->set_policy('site', 'content_filter/DansGuardian', 'validate_site_and_url');
        $form_ok = $this->form_validation->run();

        // Handle form submit
        //-------------------

        if ($this->input->post('submit') && $form_ok) {
            try {
                $this->dansguardian->add_exception_site_and_url($this->input->post('site'), $policy);

                $this->page->set_status_updated();
                redirect('/content_filter/exception_sites/edit/' . $policy);
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        // Load view data
        //---------------

        $data['type'] = 'exception';
        $data['policy'] = $policy;
    
        // Load views
        //-----------

        $this->page->view_form('content_filter/policy/site', $data, lang('content_filter_exception_sites'));
    }

    /**
     * Exception sites edit.
     *
     * @param integer $policy policy ID
     *
     * @return view
     */

    function edit($policy = 1)
    {
        // Load libraries
        //---------------

        $this->lang->load('content_filter');
        $this->load->library('content_filter/DansGuardian');

        // Load view data
        //---------------

        try {
            $data['type'] = 'exception';
            $data['policy'] = $policy;
            $data['sites'] = $this->dansguardian->get_exception_sites_and_urls($policy);
        } catch (Engine_Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('content_filter/policy/sites', $data, lang('content_filter_exception_sites'));
    }

    /**
     * Delete exception site entry view.
     *
     * @param integer $policy policy ID
     * @param string  $site   exception site
     *
     * @return view
     */

    function delete($policy, $site)
    {
        $confirm_uri = '/app/content_filter/exception_sites/destroy/' . $policy . '/' . $site;
        $cancel_uri = '/app/content_filter/exception_sites/index/' . $policy;
        $items = array(base64_decode(strtr($site, '-_.', '+/=')));

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }

    /**
     * Destroys exception site view.
     *
     * @param integer $policy policy ID
     * @param string  $site   exception site
     *
     * @return view
     */

    function destroy($policy, $site)
    {
        // Load libraries
        //---------------

        $this->load->library('content_filter/DansGuardian');

        // Handle delete
        //--------------

        try {
            $this->dansguardian->delete_exception_site_and_url(base64_decode(strtr($site, '-_.', '+/=')), $policy);

            $this->page->set_status_deleted();
            redirect('/content_filter/exception_sites/edit/' . $policy);
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }
}
