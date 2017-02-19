 <?php 
include("QueryGenerator.php");

//maybe have a select statement for tables and have them in constants??? thought for food....
//means that if things change in the database... these wont be affected

//remember to make gets posts


if(isset($_GET['selectUser']))
{
    $table = "users";
    $columns = array("user_id");

    if(isset($_GET['column']))
    {
        $columns = $_GET['column']; 
    }

    if(isset($_GET['condition_column']) && isset($_GET['condition']))
    {
        $conditionColumns = $_GET['condition_column'];
        $conditions = $_GET['condition'];
    }
    echo toJSON(selectStatement($table, $columns, NULL, NULL, NULL));
}


if(isset($_GET['authenticateUser']))
{
    $table = "users";
    $columns = array("users.user_id","users.first_name","users.surname", "player_nicknames.nickname");
    $joinTableWithCondition = array("INNER JOIN", "player_nicknames", "user_id");

    if(isset($_GET['email']) && isset($_GET['password']))
    {
        array_push($conditionColumns, "users.email", "users.password", "users.deleted", "player_nicknames.deleted");
        array_push($conditions, $_GET['email'], MD5($_GET['password']), "NULL", "NULL");
    }
    else
    {
        die("credentials not supplied");
    }
    echo toJSON(selectStatement($table, $columns, $conditionColumns, $conditions, $joinTableWithCondition));
}


if(isset($_GET['updateNickname']))
{
    $table = "player_nicknames";
    $columns = array("nickname_id");

    if(isset($_GET['user_id']) && isset($_GET['current_nickname']) && isset($_GET['new_nickname']))
    {
        array_push($conditionColumns, "user_id", "nickname", "deleted");
        array_push($conditions, $_GET['user_id'], $_GET['current_nickname'], "NULL");
        
        $nicknameObj = toObject(selectStatement($table, $columns, $conditionColumns, $conditions, NULL));
        if($nicknameObj != NULL)
        {
            softDelete($table, "nickname_id", $nicknameObj->nickname_id);
        }
        else
        {
            die("Unable to delete current nickname");
        }

        $insertColumns = array("user_id", "nickname", "created");
        $insertValues = array($_GET["user_id"],$_GET["new_nickname"],"NOW()");
       
        insertStatement($table, $insertColumns, $insertValues);
    }
    else
    {
        die("credentials not supplied");
    }
}

//TEST WHEN YOU HAVE MADE AN ADD USER

if(isset($_GET['deleteUser']))
{
    if(isset($_GET['user_id']))
    {
        softDelete("users", "user_id", $_GET['user_id']);
        softDelete("player_nicknames", "user_id", $_GET['user_id']);
        softDelete("user_game_scores", "user_id", $_GET['user_id']);
    }
    else
    {
        die("Unable to delete current nickname");
    }
}

if(isset($_GET['newScore']))
{
    if(isset($_GET['user_id']) && isset($_GET['map_id']) && isset($_GET['score']))
    {
        $insertColumns = array("user_id", "map_id", "score", "created");
        $insertValues = array($_GET["user_id"],$_GET["map_id"], $_GET["score"], "NOW()");
       
        insertStatement("user_game_scores", $insertColumns, $insertValues);
    }
    else
    {
        die("credentials not supplied");
    }
}

if(isset($_GET['newUser']))
{
    if(isset($_GET['first_name']) && isset($_GET['surname']) && isset($_GET['email']) && isset($_GET['password']) && isset($_GET['nickname']))
    {
        $table = "users";
        $insertColumns = array("first_name", "surname", "email", "password", "created");
        $insertValues = array($_GET["first_name"], $_GET["surname"], $_GET["email"], MD5($_GET["password"]), "NOW()");
        
        if(numbersInString($_GET["first_name"]) || numbersInString($_GET["surname"]))
        {
            die("Names cannot have numbers in");
        }

        insertStatement("users", $insertColumns, $insertValues);

        $user = toObject(selectStatement($table, array("user_id"), array("email"), array($_GET['email']),NULL));

        $insertColumns = array("user_id", "nickname", "created");
        $insertValues = array($user->user_id,$_GET["nickname"], "NOW()");

        insertStatement("player_nicknames", $insertColumns, $insertValues);
    }
    else
    {
        die("credentials not supplied");
    }
}

?>