<?php
include_once(__dir__ . "/RestResource.php");
class ResourceProcessor{
    private $ResourceList = array();

    function __construct(){

    }
    public function RegisterResource(String $Path, Resource $Resource){
        $this->ResourceList[$Path]=$Resource;
    }

    public function getRegisteredPaths(){
        return array_keys($this->ResourceList);
    }

    public function getResourceFromPath($pth){
        if(array_key_exists($pth, $this->ResourceList)==true){
            return $this->ResourceList[$pth];
        }
        else{
            return null;
        }
    }

    public function ProcessResources(){
        $RequestURI=$_SERVER['REQUEST_URI'];
        $expReq = explode('/', $RequestURI);
        $exp2=explode('?',$expReq[2]);
        $requestedRes = $exp2[0];
        $resrc=$this->getResourceFromPath($requestedRes);
        if(is_null($resrc)){
            http_response_code(404);
            foreach($this->ResourceList as $key=>$value){
                echo $key;
                echo "\n";
            }
            echo $RequestURI . "\n";
            echo $expReq[2]."\n";
            echo $requestedRes."\n";
            echo '<hr>';
            echo var_dump($exp2) ."\n";
        }
        else{
            $resrc->Process();
        }
    }
}

?>