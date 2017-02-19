 <?php 

include("QueryGenerator.php");

if(isset($_GET['selectUser']))
{
    $table = "users";
    $columns = array();
    $conditionColumns = array();
    $conditions = array();

    if(isset($_GET['column']))
    {
        $columns = $_GET['column']; 
    }

    if(isset($_GET['condition_column']) && isset($_GET['condition']))
    {
        $conditionColumns = $_GET['condition_column'];
        $conditions = $_GET['condition'];
    }
    selectStatement($table, $columns, NULL, NULL);
}







?>