<?php
//echo phpinfo();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//increase memory
ini_set('memory_limit','64M');

/*print_r($_POST);
 * <pre>Array
(
    [zipscsv] => 30002, 30030
    [miles] => 4
)
</pre>
 */

$searchRadius=$_POST["miles"];

$zipArrTrimed=array();
$zipsCsvPosted=$_POST["zipscsv"];

if(!IsNullOrEmptyString($zipsCsvPosted)){
    $zipsInputAsArr= explode(',', $zipsCsvPosted);
} else {
    echo json_encode("No zip codes input. (List must be comma delimited.)");
    die();
}

foreach ($zipsInputAsArr as $zipArrEl) {
    if(!is_null($zipArrEl))
        //addslashes
        $zipArrTrimed[]=addslashes(trim($zipArrEl));
}


$db="zipcode";//select the database
//returns connection as $PDOcon
require_once '/home/pwingard/hidden/connect2db.php';

$retArrKey=0;

foreach($zipArrTrimed as $zip){

    if(!ctype_digit($zip) || strlen($zip)!=5){
        echo json_encode("$zip is not a valid zipcode...");
        die();
    }else{
        
        $sql="SELECT zip,latitude__c, longitude__c from zips WHERE zip='".$zip."'";
        $postedzipdata=listData($PDOcon, $sql);
        //printr $postedzipdata
        /*
                <pre>Array
         (
             [0] => Array
                 (
                     [zip] => 30002
                     [latitude__c] => 33.76
                     [longitude__c] => -84.26
                 )

         )
         </pre>
         */
        
        //check for response
        if(count($postedzipdata)<1){
            echo json_encode("$zip was not found in the data...");
            die();
        }
        
        //get center point lat and long for zip
        $cLat=$postedzipdata[0]["latitude__c"];
        $cLong=$postedzipdata[0]["longitude__c"];
        
        //set rough boundaries for search
        
            //for south we subtract  search radius from lat 
            //(At 38 degrees North latitude, one degree of latitude roughly equals 69 miles)
            $latMaxsearch_south=$cLat-$searchRadius/69;
            //subtract an extra degree just to be sure
            $latMaxsearch_south=$latMaxsearch_south-1;

            //for north add search radius to lat (one degree ~ 69 miles)
            $latMaxsearch_north=$cLat+$searchRadius/69;
            //add an extra degree just to be sute
            $latMaxsearch_north=$latMaxsearch_north+1;
            
            //for east we add search radius to long 
            //(At 38 degrees North one-degree of longitude roughly equals 54.6 miles)
            $longMaxsearch_east=$cLong+$searchRadius/54.6;
            //add an extra degree just to be sute
            $longMaxsearch_east=$longMaxsearch_east+1;
            
            //for west we subtract search radius from long 
            //(At 38 degrees North one-degree of longitude roughly equals 54.6 miles)
            $longMaxsearch_west=$cLong-$searchRadius/54.6;
            //add an extra degree just to be sute
            $longMaxsearch_west=$longMaxsearch_west-1;
        
        //get a 'oversized-square area' of zips large enough to contain all the target zips
        //but not all of the tens of thousands of them
        //this will increase the overall speed of the response
            
        $sql="SELECT zip,latitude__c, longitude__c from zips "
                . "WHERE latitude__c >='".$latMaxsearch_south."'"
                . "&& latitude__c <='".$latMaxsearch_north."'"
                . "&& longitude__c <='".$longMaxsearch_east."'"
                . "&& longitude__c >='".$longMaxsearch_west."'";
 
        $overSizedAreaZipsArr=listData($PDOcon, $sql);
        //$sql and printr $overSizedAreaZipsArr
        /*
         * SELECT zip,latitude__c, longitude__c from zips WHERE latitude__c >='32.6875362319'&& latitude__c <='34.8324637681'&& longitude__c <='-83.1684249084'&& longitude__c >='-85.3515750916'cLat 33.76
            cLat -84.26
            max search south 32.6875362319
            max search North 34.8324637681
            max search East -83.1684249084
            max search West -85.3515750916
            <pre>Array
            (
                [0] => Array
                    (
                        [zip] => 36855
                        [latitude__c] => 33.01
                        [longitude__c] => -85.35
                    )

                [1] => Array
                    (
                        [zip] => 30747
                        [latitude__c] => 34.47
                        [longitude__c] => -85.34
                    )
         */
        
        //save the initial zip
        
        //search the rough arr to find which zips are within the search radius
        foreach ($overSizedAreaZipsArr as $ele) {
            
            $disBTZips=distance($cLat, $cLong, $ele["latitude__c"], $ele["longitude__c"]);
            //echo $ele["zip"];echo "\n";
            //if the distance between the zips is less that the input miles
            //save the zip code to the return array
            if($searchRadius>$disBTZips){
                $returnZipArr[$retArrKey]=" ".$ele["zip"];
                $retArrKey++;
            }
        }
    }
}

//remove any duplicates
$returnZipArr = array_unique($returnZipArr); 

//encode and resequence the array index so json doesn't convert it into an object
echo json_encode(array_values($returnZipArr));
die();

function parsezips($output){
    
    $yo=json_decode($output);
    
    foreach($yo->zip_codes as $ele){
    $zipsArr[]=$ele->zip_code;
    }
    return $zipsArr;
}

function IsNullOrEmptyString($input){
    return (!isset($input) || trim($input)==='');
}

function listData($PDOcon, $sql){
    try {
        $stmt = $PDOcon->prepare($sql);
	    $stmt->execute();        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e){
        //echo $e;
        $rows[0]["error_message"] = $e;
	} 
    return $rows;
}

function distance($lat1, $lon1, $lat2, $lon2) {

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;

    return $miles;
}
