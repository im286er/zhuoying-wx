<?php

namespace Api\Controller;
use Think\Controller;
use Think\Model;

class AccountController extends Controller{

    public function statement() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $model = D('StatementView');
        $list = $model->field('aid,title,uid,signintime,money,nickname,avatar')->where('issignin = 1 and Activity.uid = %d and money > 0', I('uid'))->select();

        $this->retSuccess($list);
    }

    public function purse() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $user = D('User')->field('amount,freeze_amount')->find(I('uid'));

        $this->retSuccess($user);
    }

    public function bank_list() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $banks = D('UserBank')->field('id,bank_type,open_bank,uname,account')->where('uid = %d and status > 0', I('uid'))->select();

        $this->retSuccess($banks);
    }

    public function add_bank() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('bank_type', 'require', '银行类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('bank_type', '1,16', '银行类型长度不正确', Model::MUST_VALIDATE, 'length'),
            array('uname', 'require', '用户姓名不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uname', '1,16', '用户姓名长度不正确', Model::MUST_VALIDATE, 'length'),
            array('account', 'require', '银行卡号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('account', '16,19', '银行卡号长度不正确', Model::MUST_VALIDATE, 'length'),
            array('open_bank', 'require', '开户行不能为空', Model::MUST_VALIDATE, 'regex'),
            array('open_bank', '1,50', '开户行长度不正确', Model::MUST_VALIDATE, 'length'),
        ));

        $userBank = D('UserBank');

        if ($userBank->create()) {
            $flg = $userBank->add();

            if ($flg) {
                $this->retSuccess();
            }
        }

        $this->retError();
    }

    public function delete_bank() {
        M()->validation(array(
            array('id', 'require', 'ID不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $userBank = D('UserBank');

        $flg = $userBank->where('id = %d', I('id'))->setField('status', '-1');

        if ($flg) {
            $this->retSuccess();
        }

        $this->retError();
    }

    public function cash_list() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
        ));

        $cashs = D('UserCash')->field('bank_type,uname,account,amount,cash_status,createtime')->where('uid = %d', I('uid'))->select();

        $this->retSuccess($cashs);
    }

    public function cash() {
        M()->validation(array(
            array('uid', 'require', '用户不能为空', Model::MUST_VALIDATE, 'regex'),
            array('bank_type', 'require', '银行类型不能为空', Model::MUST_VALIDATE, 'regex'),
            array('bank_type', '1,16', '银行类型长度不正确', Model::MUST_VALIDATE, 'length'),
            array('uname', 'require', '用户姓名不能为空', Model::MUST_VALIDATE, 'regex'),
            array('uname', '1,16', '用户姓名长度不正确', Model::MUST_VALIDATE, 'length'),
            array('account', 'require', '银行卡号不能为空', Model::MUST_VALIDATE, 'regex'),
            array('account', '16,19', '银行卡号长度不正确', Model::MUST_VALIDATE, 'length'),
            array('open_bank', 'require', '开户行不能为空', Model::MUST_VALIDATE, 'regex'),
            array('open_bank', '1,50', '开户行长度不正确', Model::MUST_VALIDATE, 'length'),
            array('amount', 'require', '提现金额不能为空', Model::MUST_VALIDATE, 'regex'),
            array('amount', 'number', '提现金额不正确', Model::MUST_VALIDATE, 'regex'),
        ));

        $uid = I('uid');

        $user = D('User');

        $info = $user->field('amount')->find($uid);

        $amount = I('amount');

        if ($info['amount'] < $amount) {
            $this->retError('钱包中没有足够的余额');
        }

        $userCash = D('UserCash');

        if ($userCash->create()) {
            $flg = $userCash->add();

            if ($flg) {

                $flg = M()->execute("update t_user set amount = amount - $amount, freeze_amount = freeze_amount + $amount where id = $uid");

                $this->retSuccess();
            }
        }

        $this->retError(); 
    }
}