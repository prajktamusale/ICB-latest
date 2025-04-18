<?php 
class sqlToJson{
    function sql_to_json($rows){
        $json_output = "{";
        $count1 = 0;
        foreach($rows as $key => $value){
            if($count1!=0){
                $json_output.=",";
            }
            $count2 = 0;
            $json_output.='"'.$key.'": {';
            foreach($value as $key2 => $value2){
                $json_output.='"'.$key2.'":"'.$value2.'"';
                $count2++;
                if($count2!= count($value)){
                    $json_output.=",";
                }
            }
            $count1++;
            // if($count1!= count((array)$rows)){
                $json_output.="}";
            // }else{
                // $json_output.="}";
            // }
        }
        $json_output.="}";
        echo $json_output;
    }
}
?>