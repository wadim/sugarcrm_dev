<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/


require_once('modules/EmailTemplates/EmailTemplate.php');

class Bug48800Test extends Sugar_PHPUnit_Framework_TestCase
{

var $emailTemplate;
var $user;

public function setUp()
{
    $this->markTestIncomplete('Test fails in suite.');
    global $current_user, $app_list_strings;
    $this->user = SugarTestUserUtilities::createAnonymousUser();
    $current_user = $this->user;
    $app_list_strings = return_app_list_strings_language('en_us');
    $this->user->setPreference('default_locale_name_format', 's f l');
    $this->user->savePreferencesToDB();
    $this->user->save();

    $this->emailTemplate = new EmailTemplate();
    $this->emailTemplate->name = 'Bug48800Test';
    $this->emailTemplate->assigned_user_id = $this->user->id;
    $this->emailTemplate->save();
    //$this->useOutputBuffering = false;
}

public function tearDown()
{
    global $sugar_config;
    SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    $GLOBALS['db']->query("DELETE FROM email_templates WHERE id = '{$this->emailTemplate->id}'");
}

public function testAssignedUserName()
{
    global $locale;
    require_once('include/Localization/Localization.php');
    $locale = new Localization();
    $testName = $locale->getLocaleFormattedName($this->user->first_name, $this->user->last_name);
    $testTemplate = new EmailTemplate();
    $testTemplate->retrieve($this->emailTemplate->id);
    $this->assertEquals($testName, $testTemplate->assigned_user_name, 'Assert that the assigned_user_name is the locale formatted name value');
}

}

?>
