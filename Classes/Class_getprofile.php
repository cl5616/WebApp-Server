<?php
class ProfileGetter
{
    private $user_id;
    private $database;
    public function __construct($user_id, $database)
    {
        $this->user_id = $user_id;
        $this->database = $database;
    }
    public function getProfile()
    {
        $result = $this->database->getProfile($this->user_id);
        if ($result === false)
        {
            returnJsonStatus(false);
            die();
        }
        else if(isset($result["email"]))
            echo json_encode($result);
        else
            dieWithErrorMsg("user does not exist!");
    }
}