<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Controller;

use Think\Controller;

class LTPtestController extends Controller {

    private $n_Test_Success = 0;
    private $n_Test_Failed = 0;
    private $n_Test_ID;
    private $c_Test_method = "";
    private $a_Test_Failed_List = array();
    private $a_Test_List = array();

    public function index() {
        echo "Test Start!!!<br>";
    }

    public function get_Success_Num() {
        return $this->n_Test_Success;
    }

    public function get_Failed_Num() {
        return $this->n_Test_Failed;
    }

    public function get_Method_Name() {
        return $this->c_Test_method;
    }

    public function run() {

        $class_methods = get_class_methods($this);

        //var_dump(get_class_methods($this));
        foreach ($class_methods as $class_method) {
            $a_testname = explode("_", $class_method);
            //var_dump($a_testname);
            $this->c_Test_method = $class_method;

            if (isset($a_testname['1']) and "test" == strtolower($a_testname['0'])) {
                //echo "Test Function:" . $class_method . "<br>";
                $a_class_method = array(
                    'name' => $class_method,
                    'flag' => 0,
                );
                array_push($this->a_Test_List, $a_class_method);
                $return = $this->$class_method();
            }
        }

//        echo "<br>";
//        echo "<br>";
//        echo "Test success:" . $this->n_Test_Success . "<br>";
//        echo "Test failed:" . $this->n_Test_Failed . "<br>";
//        if (0 != count($this->a_Test_Failed_List)) {
//            echo "Failed list:<br>";
//            //var_dump($this->a_Test_Failed_List);
//        }

//        foreach ($this->a_Test_Failed_List as $Failed_Function_Name) {
//            echo "$Failed_Function_Name" . "<br>";
//        }
        
        $this->assign('test_num', $this->n_Test_Success + $this->n_Test_Failed);
        $this->assign('test_success_num', $this->n_Test_Success);
        $this->assign('test_failed_num', $this->n_Test_Failed);
        $this->up_Test_Flag();
        $this->assign('test_failed_list', $this->a_Test_Failed_List);
        $this->assign('test_list', $this->a_Test_List);
        //var_dump($this->a_Test_List);
        echo '<br>';
        $this->display("LTPtest:run");
    }

    private function up_Test_Flag() {
        foreach ($this->a_Test_List as $key => $Test_Function) {
            $name = $Test_Function['name'];
            if(in_array($name, $this->a_Test_Failed_List)){
                $this->a_Test_List[$key]['flag'] = 1;
            }
        }
    }

    public function assert_is_null($return) {
        if (null == $return) {
            $this->n_Test_Success++;
        } else {
            $this->n_Test_Failed++;
            //$this->a_Test_List[$this->c_Test_method]['flag'] = 1;
            array_push($this->a_Test_Failed_List, $this->c_Test_method);
        }
    }

    public function assert_is_true($return) {
        if ($return) {
            $this->n_Test_Success++;
        } else {
            $this->n_Test_Failed++;
            //$this->a_Test_List[$this->c_Test_method]['flag'] = 1;
            array_push($this->a_Test_Failed_List, $this->c_Test_method);
        }
        return true === $return ? true : false;
    }

    public function assert_is_false($return) {
        return $this->assert_is_true(!$return);
    }

    public function assert_is_int($return) {
        if (is_int($return)) {
            $this->n_Test_Success++;
        } else {
            $this->n_Test_Failed++;
            //$this->a_Test_List[$this->c_Test_method]['flag'] = 1;
            array_push($this->a_Test_Failed_List, $this->c_Test_method);
        }
        return is_int($return);
    }

    public function assert_is_array($return) {
        if (is_array($return)) {
            $this->n_Test_Success++;
            return true;
        } else {
            $this->n_Test_Failed++;
            //$this->a_Test_List[$this->c_Test_method]['flag'] = 1;
            array_push($this->a_Test_Failed_List, $this->c_Test_method);
            return false;
        }
        return is_array($return);
    }

    public function assert($assert, $return) {
        //return $assert === $return ? true : false;

        if ($assert === $return) {
            $this->n_Test_Success++;
            return true;
        } else {
            $this->n_Test_Failed++;
            //$this->a_Test_List[$this->c_Test_method]['flag'] = 1;
            array_push($this->a_Test_Failed_List, $this->c_Test_method);
            return false;
        }
    }

    public function ltp_print($print, $name = "") {
        $c_method = $this->get_Method_Name();

        echo "<br>$c_method:<br>";
        echo "<br>$name:";
        var_dump($print);
        echo "<br>";
    }

}
