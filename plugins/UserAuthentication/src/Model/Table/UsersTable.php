<?php

/**
 * Users Table
 * 
 * @package UserAuthentication/Model
 * @since V 1.0.0
 * @author Krutarth Patel<krutarth@sourcefragment.com>
 * @copyright (c) 2017, Krutarth Patel
 */
namespace UserAuthentication\Model\Table;
use Cake\ORM\Table;

class UsersTable extends Table {

    /**
     * Define constants
     */
    const ROLE_CUSTOMER = 'customer';
    const ROLE_ADMIN = 'admin';
    const ROLE_VENDOR = 'vendor';

    /**
     * Flag to set email check in buildRules or not
     *
     * @var bool
     */
    public $isValidateEmail = FALSE;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
    }

    /**
     * Adds some rules for password confirm
     * @param Validator $validator Cake validator object.
     * @return Validator
     */
    public function validationPasswordConfirm(Validator $validator) {
        $validator
                ->requirePresence('password_confirm', 'create')
                ->notEmpty('password_confirm');

        $validator->add('password', 'custom', [
            'rule' => function ($value, $context) {
                $confirm = Hash::get($context, 'data.password_confirm');
                if (!is_null($confirm) && $value != $confirm) {
                    return false;
                }

                return true;
            },
            'message' => __d('UserAuthentication', 'Your password does not match your confirm password. Please try again'),
            'on' => ['create', 'update'],
            'allowEmpty' => false
        ]);

        return $validator;
    }

    /**
     * Adds rules for current password
     *
     * @param Validator $validator Cake validator object.
     * @return Validator
     */
    public function validationCurrentPassword(Validator $validator) {
        $validator
                ->notEmpty('current_password');

        return $validator;
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->allowEmpty('id', 'create');

        $validator
                ->requirePresence('username', 'create')
                ->notEmpty('username');

        $validator
                ->requirePresence('password', 'create')
                ->notEmpty('password');

        $validator
                ->requirePresence('email', 'create')
                ->notEmpty('email')
                ->add('email', 'validFormat', [
                    'rule' => 'email',
                    'message' => 'E-mail must be valid'
        ]);

        $validator
                ->allowEmpty('firstname');

        $validator
                ->allowEmpty('lastname');

        $validator
                ->allowEmpty('token');

        $validator
                ->add('token_expires', 'valid', ['rule' => 'datetime'])
                ->allowEmpty('token_expires');

        $validator
                ->add('activation_date', 'valid', ['rule' => 'datetime'])
                ->allowEmpty('activation_date');

        $validator
                ->add('join_date', 'valid', ['rule' => 'datetime'])
                ->allowEmpty('tos_date');

        return $validator;
    }

    /**
     * Wrapper for all validation rules for register
     * @param Validator $validator Cake validator object.
     *
     * @return Validator
     */
    public function validationRegister(Validator $validator) {
        $validator = $this->validationDefault($validator);
        $validator = $this->validationPasswordConfirm($validator);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['username']), '_isUnique', [
            'errorField' => 'username',
            'message' => __d('UserAuthentication', 'Username already exists')
        ]);

        if ($this->isValidateEmail) {
            $rules->add($rules->isUnique(['email']), '_isUnique', [
                'errorField' => 'email',
                'message' => __d('UserAuthentication', 'Email already exists')
            ]);
        }

        return $rules;
    }

}
