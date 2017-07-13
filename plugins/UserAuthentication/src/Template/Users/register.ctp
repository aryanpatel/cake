<?php

/**
 * Registration view
 * 
 * @package UserAuthenticatoin\Template
 * @since  1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */
use Cake\Core\Configure;
?>
<div class="users form large-10 medium-9 columns">
<?= $this->Form->create($user); ?>
    <fieldset>
        <legend><?= __d('UserAuthentication', 'Add User') ?></legend>
<?php
echo $this->Form->control('username', ['label' => __d('UserAuthentication', 'Username')]);
echo $this->Form->control('email', ['label' => __d('UserAuthentication', 'Email')]);
echo $this->Form->control('password', ['label' => __d('UserAuthentication', 'Password')]);
echo $this->Form->control('password_confirm', [
    'type' => 'password',
    'label' => __d('UserAuthentication', 'Confirm password')
]);
echo $this->Form->control('first_name', ['label' => __d('UserAuthentication', 'First name')]);
echo $this->Form->control('last_name', ['label' => __d('UserAuthentication', 'Last name')]);
if (Configure::read('Users.Tos.required')) {
    echo $this->Form->control('tos', ['type' => 'checkbox', 'label' => __d('UserAuthentication', 'Accept TOS conditions?'), 'required' => true]);
}
if (Configure::read('Users.reCaptcha.registration')) {
    echo $this->User->addReCaptcha();
}
?>
    </fieldset>
        <?= $this->Form->button(__d('UserAuthentication', 'Submit')) ?>
    <?= $this->Form->end() ?>
</div>