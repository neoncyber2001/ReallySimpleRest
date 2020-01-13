<?php

class Resource
{
    public $userGet;
    public $userPost;
    public $userPut;
    public $userDelete;

    function Process(){
        
        switch($_SERVER['REQUEST_METHOD']){
			case "GET":
				if(!is_null($this->userGet)){
                    call_user_func($this->userGet);
                }
                else{
                    http_response_code(400);
                    echo "ERROR: Bad Request\n";
                    echo $_SERVER['REQUEST_METHOD'];
                }
				break;
			case "POST":
				if(!is_null($this->userPost)){
                    call_user_func($this->userPost);
                }
                else{
                    http_response_code(400);
                    echo "ERROR: Bad Request\n";
                    echo $_SERVER['REQUEST_METHOD'];
                }
				break;
			case "PUT":
                if(!is_null($this->userPut)){
                    call_user_func($this->userPut);
                }
                else{
                    http_response_code(400);
                    echo "ERROR: Bad Request\n";
                    echo $_SERVER['REQUEST_METHOD'];
                }
				break;
			default:
				http_response_code(400);
				echo "ERROR: Bad Request\n";
				echo $_SERVER['REQUEST_METHOD'];
		};

    }
}


?>
