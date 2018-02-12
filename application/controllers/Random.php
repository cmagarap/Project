<?php
/**
 * Created by PhpStorm.
 * User: Seeeeej
 * Date: 12/13/2017
 * Time: 9:00 PM
 */

# This is just a random controller used for debugging, etc.
date_default_timezone_set("Asia/Manila");
class Random extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->library(array('session', 'apriori'));
        $this->load->helper('string');
    }

    public function index() {

        $this->load->view("paper/practice_charts");

        /*$this->load->library('encryption');
        $hash = random_string('alnum', 20);
        echo "<b>This is a random string: </b>$hash<br>";
        echo "<b>SHA1: </b>".sha1('seej101')."<br>";
        echo "<b>SHA1: </b>".sha1('seej101')."<br>";
        echo sha1('customer')."<br>";
        echo sha1('vvilliam')."<br>";
        echo "<b>MD5: </b>".md5('phpsucksforever')."<br>";
        echo "<b>CRYPT: </b>".crypt('phpsucksforever', $hash)."<br>";
        echo "<b>PASSWORD_HASH (bycrypt): </b>".password_hash('phpsucksforever', PASSWORD_BCRYPT)."<br>";
        echo "<b>PASSWORD_HASH (default): </b>".password_hash('phpsucksforever', PASSWORD_DEFAULT)."<br>";
        # echo "<b>PASSWORD_HASH (argon): </b>".password_hash('phpsucksforever', PASSWORD_ARGON2I)."<br>";

        echo "<br>".$this->uri->segment(1);
        # $this->session->sess_destroy();
        echo "test<br><hr>";

        // ==========================================================================================
        $plain_text = 'This is a plain-text message!';
        $ciphertext = $this->encryption->encrypt($plain_text);

        // Outputs: This is a plain-text message! (decrypt())
        echo $this->encryption->decrypt($ciphertext)."<br>";
        // ==========================================================================================


        # To check, get the salt first:
        # getSalt($user_table, $saltFromDB, $columnForUserID, $user_id)
        $salt = $this->item_model->getSalt("admin", "verification_code", "admin_id", 2);
        echo "<b>This is the salt:</b> $salt<br>";

        # To set the password:
        # setPassword($passwordString, $user_table, $saltFromDB, $columnForUserID, $user_id)
        $password = $this->item_model->setPassword("qwertyuiop123", $salt);
        echo "<b>This is the password:</b> $password<br>";
        # To verify:
        # password_verify($stringToBeTested, $actualPassword)
        if(password_verify($salt."qwertyuiop123", $password)) {
            echo "<br><b style = 'color: green'><u>EQUAL</u></b>";
        } else {
            echo "<br><b style = 'color: red'>NOT EQUAL</b>";
        }

        #echo "<br><pre>";
        $bytes = openssl_random_pseudo_bytes(30, $crypto_strong);
        $hex = bin2hex($bytes); # length of hex is double the bytes
        # var_dump($hex);
        # var_dump($crypto_strong);
        #echo $hex."</pre>";

        */
        #$sample = $this->item_model->fetch("user_log");
        #$sample = $sample[0];
        #echo date("F j, Y", 1516409137);
        #$lastweek = time() - (6 * 24 * 60 * 60);
        #echo date("F j, Y", $lastweek);
        # echo $lastweek."<br>";
        $d = strtotime("Sept 17, 1996 18:27");
        echo date("Y-m-d h:i:sa", $d)."<br>";
        echo $d."<br>";
        if(strtotime("December") == $d) {
            echo "december!!";
        } else {
            echo "boring month";
        }
    }

    public function getProductdata() {
        header('Content-Type: application/json');
        #$this->db->select("product_quantity");
        $data = $this->item_model->fetch('product', NULL, NULL, NULL, 6);
        echo json_encode($data);
    }

    public function apr() {
        $this->load->view("paper/ap/example");
    }
}