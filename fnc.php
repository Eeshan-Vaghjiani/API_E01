<?php
class fnc{
    var $name;
    public $fname;
    public $yob;
    public $age;
    protected $username;
    private $password;
    public function computer_user($fname){
        return $fname;
    }
    // public function hash_pass($pass){
    //     return md5($pass);
    // }
    public function hash_pass($pass){
        return password_hash($pass,PASSWORD_DEFAULT); 
    }
    public function user_age ($fname,$yob){
        $user = $this->computer_user($fname);
        $age = date('Y') - $yob;
        return $user." is ". $age;
    }
}