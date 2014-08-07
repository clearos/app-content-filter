<?php

/**
 * Content filter groups summary view.
 *
 * @category   apps
 * @package    content-filter
 * @subpackage views
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
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('groups');
$this->lang->load('policy_manager');
$this->lang->load('content_filter');

///////////////////////////////////////////////////////////////////////////////
// Headers
///////////////////////////////////////////////////////////////////////////////

$headers = array(
    lang('policy_manager_policy_name'),
    lang('groups_group'),
);

///////////////////////////////////////////////////////////////////////////////
// Anchors
///////////////////////////////////////////////////////////////////////////////

$anchors = array(anchor_add('/app/content_filter/policy/add'));

///////////////////////////////////////////////////////////////////////////////
// Items
///////////////////////////////////////////////////////////////////////////////

foreach ($groups as $id => $details) {
    if ($id === 1) {
        $detail_buttons = button_set(
            array(anchor_custom('/app/content_filter/policy/configure/' . $id, lang('base_configure_policy')))
        );
        $group = '-';
    } else {
        $detail_buttons = button_set(
            array(
                anchor_custom('/app/content_filter/policy/configure/' . $id, lang('base_configure_policy')),
                anchor_edit('/app/content_filter/policy/edit/' . $id),
                anchor_delete('/app/content_filter/policy/delete/' . $id)
            )
        );

        $group = $details['systemgroup'];
    }

    $item['title'] = $details['groupname'];
    $item['action'] = '/app/content_filter/policy/configure/' . $id;
    $item['anchors'] = $detail_buttons;
    $item['details'] = array(
        $details['groupname'],
        $group
    );

    $items[] = $item;
}

///////////////////////////////////////////////////////////////////////////////
// Summary table
///////////////////////////////////////////////////////////////////////////////

$options =  array('sort' => FALSE);

echo summary_table(
    lang('groups_app_policies'),
    $anchors,
    $headers,
    $items,
    $options
);
