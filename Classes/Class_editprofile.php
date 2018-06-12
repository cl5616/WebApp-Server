<?php
require_once "session.php";
class ProfileEditor
{
    private $new_intro;
    private $new_image;
    private $new_nickname;
    private $new_psw;
    private $database;
    private $now_psw;

    public function __construct($new_intro, $new_image,
                                $new_nickname, $new_psw,
                                $now_psw, $database)
    {
        $this->new_intro = $new_intro;
        $this->new_image = $new_image;
        $this->new_nickname = $new_nickname;
        $this->new_psw = $new_psw;
        $this->database = $database;
        $this->now_psw = $now_psw;
    }
    public function editProfile()
    {

        if ($this->new_psw == null)
        {
            $res = $this->database->editProfile($this->new_intro, $this->new_image,
                $this->new_nickname, null, getCurUserId());
        }
        else
        {
            $cid = getCurUserId();
            $result = $this->database->getIdPswInfo($cid);
            if ($result === false)
            {
                dieWithErrorMsg("problem in database query");
            }
            $old_hash = hash("sha256",$this->now_psw.$result["salt"]);
            if (strcmp($old_hash, $result["password"]) !== 0)
            {
                dieWithErrorMsg("password incorrect");
            }
            $new_hash = hash("sha256", $this->new_psw.$result["salt"]);
            $res = $this->database->editProfile($this->new_intro, $this->new_image,
                $this->new_nickname, $new_hash, getCurUserId());
        }
        returnJsonStatus($res);
    }
}