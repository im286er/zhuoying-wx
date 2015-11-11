<?php
/**
 * Author: NanQi
 * Date: 20150512 20:38
 */
namespace Admin\Controller;
class LuckyDrawController extends AdminController {

    public function index() {
        $this->display();
    }

    public function shareRecord(){
        $this->display();
    }

    public function luckyDraw($id){
        S('luckydray_round', $id, 60 * 5);//五分钟
        $this->redirect('index');
    }
}