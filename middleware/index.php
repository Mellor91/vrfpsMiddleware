 <?php 
 
include("DatabaseConnector.php");

//queryDB also needs a boolean flag at the end to state weather you are expecting a return value

if(isset($_GET['selectUser']))
{

    //NEED TO SORT OUT HANDLING INTEGERS BUT LOOKING GOOD!

    $querySkeleton = array("SELECT ", " FROM ");
    $table = "users";
    $columns = "";

    if(isset($_GET['column']))
    {
        foreach ($_GET['column'] as $value) $columns = $columns.$value.",";
        if($columns != "")
        {
            $columns = substr($columns, 0, -1);
        }
    }

    if(isset($_GET['condition_column']))
    {
        $conditionColumns = array($_GET['condition_column']);
    }

    if(isset($_GET['condition']))
    {
        $conditions = array($_GET['condition']);
    }

    if(sizeof($conditionColumns) > 0 && sizeof($conditions) > 0)
    {
        if(sizeof($conditionColumns) == sizeof($conditions))
        {
            $conditionString = " WHERE ";
            foreach ($conditionColumns as $value) 
            {
                $conditionString = $conditionString.$value." = " ;

                foreach ($conditions as $val) 
                {
                    $conditionString = $conditionString."'".$val."' AND ";
                }
            }
            $conditionString = substr($conditionString, 0, -5);
        }
        else
        {
            echo "condition column and conditions dont match";
        }
    }

    echo $querySkeleton[0].$columns.$querySkeleton[1].$table.$conditionString;
    echo "<br>";

    echo toJSON(queryDB($querySkeleton[0].$columns.$querySkeleton[1].$table.$conditionString));
}
?>