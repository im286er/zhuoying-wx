<?php
namespace Api\Model;

use Think\Model\ViewModel;

class StatementViewModel extends ViewModel {
    public $viewFields = array(     
        'ActivityUser'=>array('aid', 'uid', 'issignin', 'signintime'),     
        'Activity'=>array('money', 'title', 'uid', '_on'=>'ActivityUser.aid=Activity.id'),
        'User'=>array('nickname', 'avatar', '_on'=>'ActivityUser.uid=User.id'),   
    );
}