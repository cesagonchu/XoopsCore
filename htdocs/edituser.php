<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *  Xoops Edit User
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id$
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->preload()->triggerEvent('core.edituser.start');
$xoops->loadLanguage('user');

// If not a user, redirect
if (!$xoops->isUser()) {
    $xoops->redirect('index.php', 3, XoopsLocale::E_NO_ACTION_PERMISSION);
    exit();
}

$request = Xoops_Request::getInstance();
// initialize $op variable
$op = $request->asStr('op', 'editprofile');

$myts = MyTextSanitizer::getInstance();
if ($op == 'saveuser') {
    if (!$xoops->security()->check()) {
        $xoops->redirect('index.php', 3, XoopsLocale::E_NO_ACTION_PERMISSION . "<br />" . implode('<br />', $xoops->security()->getErrors()));
        exit();
    }
    $uid = $request->asInt('uid', 0);
    if (empty($uid) || $xoops->user->getVar('uid') != $uid) {
        $xoops->redirect('index.php', 3, XoopsLocale::E_NO_ACTION_PERMISSION);
        exit();
    }
    $errors = array();
    if ($xoops->getConfig('allow_chgmail') == 1) {
        $email = $request->asStr('email', '');
        $email = $myts->stripSlashesGPC(trim($email));;
        if ($email == '' || ! $xoops->checkEmail($email)) {
            $errors[] = XoopsLocale::E_INVALID_EMAIL;
        }
    }
    $password = $request->asStr('password', '');
    $password = $myts->stripSlashesGPC(trim($password));
    if ($password != '') {
        if (strlen($password) < $xoops->getConfig('minpass')) {
            $errors[] = sprintf(XoopsLocale::EF_PASSWORD_MUST_BE_GREATER_THAN, $xoops->getConfig('minpass'));
        }
        $vpass = $request->asStr('vpass', '');
        $vpass = $myts->stripSlashesGPC(trim($vpass));
        if ($password != $vpass) {
            $errors[] = XoopsLocale::E_PASSWORDS_MUST_MATCH;
        }
    }
    if (count($errors) > 0) {
        $xoops->header();
        echo '<div>';
        foreach ($errors as $er) {
            echo '<span class="red bold">' . $er . '</span><br />';
        }
        echo '</div><br />';
        $op = 'editprofile';
    } else {
        $member_handler = $xoops->getHandlerMember();
        $edituser = $member_handler->getUser($uid);
        $edituser->setVar('name', $request->asStr('name', ''));
        if ($xoops->getConfig('allow_chgmail') == 1) {
            $edituser->setVar('email', $email, true);
        }
        if ($password != '') {
            $edituser->setVar('pass', md5($password), true);
        }
        $edituser->setVar('url', $xoops->formatURL($request->asStr('url', '')));
        $edituser->setVar('user_icq', $request->asStr('user_icq', ''));
        $edituser->setVar('user_from', $request->asStr('user_from', ''));
        $edituser->setVar('user_sig', XoopsLocale::substr($request->asStr('user_sig', ''), 0, 255));
        $edituser->setVar('user_viewemail', $request->asBool('user_viewemail', 0));
        $edituser->setVar('user_aim', $request->asStr('user_aim', ''));
        $edituser->setVar('user_yim', $request->asStr('user_yim', ''));
        $edituser->setVar('user_msnm', $request->asStr('user_msnm', ''));
        $edituser->setVar('attachsig', $request->asBool('attachsig', 0));
        $edituser->setVar('timezone_offset', $request->asFloat('timezone_offset', 0));
        $edituser->setVar('uorder', $request->asInt('uorder', 0));
        $edituser->setVar('umode', $request->asStr('umode', 'flat'));
        $edituser->setVar('notify_method', $request->asInt('notify_method', 1));
        $edituser->setVar('notify_mode', $request->asInt('notify_mode', 1));
        $edituser->setVar('bio', XoopsLocale::substr($request->asStr('bio', ''), 0, 255));
        $edituser->setVar('user_occ', $request->asStr('user_occ', ''));
        $edituser->setVar('user_intrest', $request->asStr('user_intrest', ''));
        $edituser->setVar('user_mailok', $request->asBool('user_mailok', 0));
        $usecookie = $request->asBool('user_mailok', 0);
        if (!$usecookie) {
            setcookie($xoops->getConfig('usercookie'), $xoops->user->getVar('uname'), time() + 31536000, '/', XOOPS_COOKIE_DOMAIN);
        } else {
            setcookie($xoops->getConfig('usercookie'));
        }
        if (! $member_handler->insertUser($edituser)) {
            $xoops->header();
            echo $edituser->getHtmlErrors();
            $xoops->footer();
        } else {
            $xoops->redirect('userinfo.php?uid=' . $uid, 1, XoopsLocale::S_YOUR_PROFILE_UPDATED);
        }
        exit();
    }
}

if ($op == 'editprofile') {
    $xoops->header('system_edituser.html');
    $xoops->tpl()->assign('uid', $xoops->user->getVar("uid"));
    $xoops->tpl()->assign('editprofile', true);
    $form = new XoopsThemeForm(XoopsLocale::EDIT_PROFILE, 'userinfo', 'edituser.php', 'post', true);
    $uname_label = new XoopsFormLabel(XoopsLocale::USERNAME, $xoops->user->getVar('uname'));
    $form->addElement($uname_label);
    $name_text = new XoopsFormText(XoopsLocale::REAL_NAME, 'name', 30, 60, $xoops->user->getVar('name', 'E'));
    $form->addElement($name_text);
    $email_tray = new XoopsFormElementTray(XoopsLocale::EMAIL, '<br />');
    if ($xoops->getConfig('allow_chgmail') == 1) {
        $email_text = new XoopsFormText('', 'email', 30, 60, $xoops->user->getVar('email'));
    } else {
        $email_text = new XoopsFormLabel('', $xoops->user->getVar('email'));
    }
    $email_tray->addElement($email_text);
    $email_cbox_value = $xoops->user->user_viewemail() ? 1 : 0;
    $email_cbox = new XoopsFormCheckBox('', 'user_viewemail', $email_cbox_value);
    $email_cbox->addOption(1, XoopsLocale::ALLOW_OTHER_USERS_TO_VIEW_EMAIL);
    $email_tray->addElement($email_cbox);
    $form->addElement($email_tray);
    $url_text = new XoopsFormText(XoopsLocale::WEBSITE, 'url', 30, 100, $xoops->user->getVar('url', 'E'));
    $form->addElement($url_text);

    $timezone_select = new XoopsFormSelectTimezone(XoopsLocale::TIME_ZONE, 'timezone_offset', $xoops->user->getVar('timezone_offset'));
    $icq_text = new XoopsFormText(XoopsLocale::ICQ, 'user_icq', 15, 15, $xoops->user->getVar('user_icq', 'E'));
    $aim_text = new XoopsFormText(XoopsLocale::AIM, 'user_aim', 18, 18, $xoops->user->getVar('user_aim', 'E'));
    $yim_text = new XoopsFormText(XoopsLocale::YIM, 'user_yim', 25, 25, $xoops->user->getVar('user_yim', 'E'));
    $msnm_text = new XoopsFormText(XoopsLocale::MSNM, 'user_msnm', 30, 100, $xoops->user->getVar('user_msnm', 'E'));
    $location_text = new XoopsFormText(XoopsLocale::LOCATION, 'user_from', 30, 100, $xoops->user->getVar('user_from', 'E'));
    $occupation_text = new XoopsFormText(XoopsLocale::OCCUPATION, 'user_occ', 30, 100, $xoops->user->getVar('user_occ', 'E'));
    $interest_text = new XoopsFormText(XoopsLocale::INTEREST, 'user_intrest', 30, 150, $xoops->user->getVar('user_intrest', 'E'));
    $sig_tray = new XoopsFormElementTray(XoopsLocale::SIGNATURE, '<br />');
    $sig_tarea = new XoopsFormDhtmlTextArea('', 'user_sig', $xoops->user->getVar('user_sig', 'E'));
    $sig_tray->addElement($sig_tarea);
    $sig_cbox_value = $xoops->user->getVar('attachsig') ? 1 : 0;
    $sig_cbox = new XoopsFormCheckBox('', 'attachsig', $sig_cbox_value);
    $sig_cbox->addOption(1, XoopsLocale::ALWAYS_ATTACH_MY_SIGNATURE);
    $sig_tray->addElement($sig_cbox);
    $bio_tarea = new XoopsFormTextArea(XoopsLocale::EXTRA_INFO, 'bio', $xoops->user->getVar('bio', 'E'));
    $cookie_radio_value = empty($_COOKIE[$xoops->getConfig('usercookie')]) ? 0 : 1;
    $cookie_radio = new XoopsFormRadioYN(XoopsLocale::STORE_USERNAME_IN_COOKIE_FOR_ONE_YEAR, 'usecookie', $cookie_radio_value);
    $pwd_text = new XoopsFormPassword('', 'password', 10, 32);
    $pwd_text2 = new XoopsFormPassword('', 'vpass', 10, 32);
    $pwd_tray = new XoopsFormElementTray(XoopsLocale::PASSWORD . '<br />' . XoopsLocale::TYPE_NEW_PASSWORD_TWICE_TO_CHANGE_IT);
    $pwd_tray->addElement($pwd_text);
    $pwd_tray->addElement($pwd_text2);
    $mailok_radio = new XoopsFormRadioYN(XoopsLocale::Q_RECEIVE_OCCASIONAL_EMAIL_NOTICES_FROM_ADMINISTRATORS, 'user_mailok', $xoops->user->getVar('user_mailok'));
    $uid_hidden = new XoopsFormHidden('uid', $xoops->user->getVar('uid'));
    $op_hidden = new XoopsFormHidden('op', 'saveuser');
    $submit_button = new XoopsFormButton('', 'submit', XoopsLocale::SAVE_CHANGES, 'submit');

    $form->addElement($timezone_select);
    $form->addElement($icq_text);
    $form->addElement($aim_text);
    $form->addElement($yim_text);
    $form->addElement($msnm_text);
    $form->addElement($location_text);
    $form->addElement($occupation_text);
    $form->addElement($interest_text);
    $form->addElement($sig_tray);
    $form->addElement($bio_tarea);
    $form->addElement($pwd_tray);
    $form->addElement($cookie_radio);
    $form->addElement($mailok_radio);
    $form->addElement($uid_hidden);
    $form->addElement($op_hidden);
    //$form->addElement($token_hidden);
    $form->addElement($submit_button);
    if ($xoops->getConfig('allow_chgmail') == 1) {
        $form->setRequired($email_text);
    }
    $form->display();
    $xoops->footer();
}