<?php
include_once(__dir__ . "/RestResource.php");
include_once(__dir__ . "/ResourceProcessor.php");

define("API_Base_Path", "ReallySimpleRest");

$processor = new ResourceProcessor;


//Get some basic test data.
$TestData = new Resource;
function GetTestData(){
    $SiteData=array(
        "Title"=>"Test Website",
        "Author"=>"Joe Herbert",
        "Description"=>"This tests my REST api",
        "Revision"=>"1/12/2020"
    );
    http_response_code(200);
    echo json_encode($SiteData);
};
$TestData->userGet = 'GetTestData';
$processor->RegisterResource("Test", $TestData);

//Get a random fortune or report one as "Mature".
$Fortune = new Resource;
function GetFortune(){
    $mysqli = mysqli_connect("localhost", "joeher5_fortune","fortune_dev","joeher5_fortune");
    $res = mysqli_query($mysqli,"SELECT COUNT(*) FROM `api_fortunes`;");
    $resrow = mysqli_fetch_assoc($res);
    $err = mysqli_error($mysqli);
    $id = rand(0,$resrow['COUNT(*)']);
    $fortuneRes = mysqli_query($mysqli,"SELECT * FROM `api_fortunes` WHERE FortuneID=".$id.";");
    $fortuneRow = mysqli_fetch_assoc($fortuneRes);
    http_response_code(200);
    echo json_encode($fortuneRow);
}
$Fortune->userGet = 'GetFortune';
function PutFortune(){
    $Params = array();
    parse_str($_SERVER['QUERY_STRING'],$Params);
    $mysqli = mysqli_connect("localhost", "joeher5_fortune","fortune_dev","joeher5_fortune");

    switch($Params["Field"]){
        case("IsMature"):
            $res = mysqli_query($mysqli,"UPDATE `api_fortunes` SET `FortuneIsMature`=".$Params["Value"]." WHERE `FortuneID`=".$Params["FortuneID"].";");
            if(mysqli_errno($mysqli)){
                http_response_code(500);
                echo mysqli_error($mysqli);
            }
            else{
                http_response_code(200);
                echo(json_encode("OK!"));
            }
        break;
        default:
            http_response_code(418);
            $messege = array(
                "MSG" => "This server is a tea pot. Coffee brewing and/or processing not supported on this server. See Hyper Text Coffee Pot Control Protocol(RFC2324 https://tools.ietf.org/html/rfc2324) for further details.",
                "ID" => $Params["FortuneID"],
                "Field" => $Params["Field"],
                "Value" => $Params["Value"]
            );
            echo(json_encode($messege));
    }
}
$Fortune->userPut = 'PutFortune';
$processor->RegisterResource("Fortune", $Fortune);

//Vote on your favorite fortunes.
$Vote = new Resource;
function PostCastVote(){
    
    $Params = array();
    parse_str($_SERVER['QUERY_STRING'],$Params);
    $cookie_name = "VoteLogDemo";
   
    if(isset($Params["Vote"]) & isset($Params["FortuneID"])){
        $mysqli = mysqli_connect("localhost", "joeher5_fortune","fortune_dev","joeher5_fortune");
        switch($Params["Vote"]){
            case("Up"):
                $res = mysqli_query($mysqli,"UPDATE `api_fortunes` SET `UpVotes`=`UpVotes`+1 WHERE `FortuneID`=".$Params["FortuneID"].";");
                if(mysqli_errno($mysqli)){
                    http_response_code(500);
                    echo mysqli_error($mysqli);
                }
                else{
                    http_response_code(200);
                    echo(json_encode("OK!"));
                }
            break;
            case("Down"):
                $res = mysqli_query($mysqli,"UPDATE `api_fortunes` SET `DownVotes`=`DownVotes`+1 WHERE `FortuneID`=".$Params["FortuneID"].";");
                if(mysqli_errno($mysqli)){
                    http_response_code(500);
                    echo mysqli_error($mysqli);
                }
                else{
                    http_response_code(200);
                    echo(json_encode("OK!"));
                }
            break;
            default:
        }
    }
    else{
        http_response_code(400);
        echo "Query String Values required for Vote (either Up or Down) and FortuneID (Number of the Fortune you would like to vote for.";
    }

}
$Vote->userPost = 'PostCastVote';
$processor->RegisterResource("CastVote", $Vote);

$processor->ProcessResources();
?>