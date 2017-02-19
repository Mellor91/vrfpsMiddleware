 <?php 
include("DatabaseConnector.php");

//Accepts: Table = String, $columns = Array, $conditions = Array or NULL, $conditions = Array or NULL, $joinTableWithCondition = Array or NULL
function selectStatement($table, $columns, $conditionColumns, $conditions, $joinTableWithCondition)
{
    $querySkeleton = array("SELECT ", " FROM ");
    $columns = implode(",",$columns);
    $join = "";
    $conditionString = "";

    if(isset($joinTableWithCondition))
    {
        $join = " ".$joinTableWithCondition[0]." ".$joinTableWithCondition[1]." ON ".$table.".".$joinTableWithCondition[2]." = ".$joinTableWithCondition[1].".".$joinTableWithCondition[2];
    }


    if(sizeof($conditionColumns) > 0 && sizeof($conditions) > 0)
    {
        if(sizeof($conditionColumns) == sizeof($conditions))
        {
            $conditionString = " WHERE ";
            $i = 0;
            foreach ($conditionColumns as $tmp) 
            {
                if(is_numeric($conditions[$i]) || strtolower($conditions[$i]) == "true" || strtolower($conditions[$i]) == "false")
                {
                    $conditionString = $conditionString.$conditionColumns[$i]." = ".$conditions[$i]. " AND ";
                }
                elseif(strtolower($conditions[$i]) == "null")
                {
                    $conditionString = $conditionString.$conditionColumns[$i]." IS NULL AND ";
                }
                elseif(strtolower($conditions[$i]) == "not null")
                {
                    $conditionString = $conditionString.$conditionColumns[$i]." IS NOT NULL AND ";
                }
                else
                {
                    $conditionString = $conditionString.$conditionColumns[$i]." = '".$conditions[$i]. "' AND ";
                }
                $i++;
                echo "<br>";
            }
            $conditionString = substr($conditionString, 0, -5);
        }
        else
        {
            die("condition column and conditions dont match in length... did you do a whoopsy????");
            return;
        }
    }
    echo $querySkeleton[0].$columns.$querySkeleton[1].$table.$join;
    echo "<br>";
    return queryDB($querySkeleton[0].$columns.$querySkeleton[1].$table.$join.$conditionString);
}


function insertStatement($table, $columns, $values)
{
    $querySkeleton = array("INSERT INTO ", " (", ") ", " VALUES ");
    $columns = implode(",",$columns);
    $valueString = "";

    $i = 0;
    foreach($values as $v)
    {
        if(is_numeric($values[$i]) || strtolower($values[$i]) == "true" || strtolower($values[$i]) == "false" ||  strtolower($values[$i]) == "now()")
        {
            $valueString = $valueString.$values[$i].",";
        }
        else
        {
            $valueString = $valueString."'".$values[$i]."',";
        }
        $i++;
    }
    $valueString = substr($valueString, 0, -1);

    echo $querySkeleton[0].$table.$querySkeleton[1].$columns.$querySkeleton[2].$querySkeleton[3].$querySkeleton[1].$valueString.$querySkeleton[2];
    echo "<br>";

    queryDB($querySkeleton[0].$table.$querySkeleton[1].$columns.$querySkeleton[2].$querySkeleton[3].$querySkeleton[1].$valueString.$querySkeleton[2]);               
}

function softDelete($table, $key, $value)
{
    $querySkeleton = array("UPDATE ", " SET ", "WHERE ", " AND deleted = NULL");
   
    echo $querySkeleton[0].$table.$querySkeleton[1]."deleted = NOW() ".$querySkeleton[2].$key." = ".$value.$querySkeleton[3];
    echo "<br>";
    queryDB($querySkeleton[0].$table.$querySkeleton[1]."deleted = NOW() ".$querySkeleton[2].$key." = ".$value.$querySkeleton[3]);
}

function toObject($result)
{
    return mysqli_fetch_object($result);
}

function toJSON($result)
{
    while($row = $result->fetch_array(MYSQL_ASSOC))
        {
            $JSONArray[] = $row;
        }
    return json_encode($JSONArray); 
}

function getLastInsertedKey()
{
    return $conn->insert_id;
}

function numbersInString($string)
{
    if(1 === preg_match('~[0-9]~', $string))
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>