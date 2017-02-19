 <?php 
include("DatabaseConnector.php");

//Accepts: Table = String, $columns = Array, $conditions = Array or NULL, $conditions = Array or NULL
function selectStatement($table, $columns, $conditionColumns, $conditions)
{
    $querySkeleton = array("SELECT ", " FROM ");
    $columns = implode(",",$columns);

    if(sizeof($conditionColumns) > 0 && sizeof($conditions) > 0)
    {
        if(sizeof($conditionColumns) == sizeof($conditions))
        {
            $conditionString = " WHERE ";
            $i = 0;
            foreach ($conditionColumns as $tmp) 
            {
                if(is_numeric($conditions[$i]) || strtolower($conditions[$i]) == "true" || strtolower($conditions[$i]) == "false" )
                {
                    $conditionString = $conditionString.$conditionColumns[$i]." = ".$conditions[$i]. " AND ";
                }
                else
                {
                    $conditionString = $conditionString.$conditionColumns[$i]." = '".$conditions[$i]. "' AND ";
                }
                $i++;
            }

            $conditionString = substr($conditionString, 0, -5);

            echo $querySkeleton[0].$columns.$querySkeleton[1].$table.$conditionString;
            echo "<br>";
            echo toJSON(queryDB($querySkeleton[0].$columns.$querySkeleton[1].$table.$conditionString));
            return;

        }
        else
        {
            echo "condition column and conditions dont match";
        }
    }
    echo $querySkeleton[0].$columns.$querySkeleton[1].$table;
    echo "<br>";
    echo toJSON(queryDB($querySkeleton[0].$columns.$querySkeleton[1].$table));
}






function toJSON($result)
{
    while($row = $result->fetch_array(MYSQL_ASSOC))
        {
            $JSONArray[] = $row;
        }
    return json_encode($JSONArray); 
}
?>