 <?php 
mysqli_report(MYSQLI_REPORT_OFF);

function connect()
{
    $servername = "localhost";
    $username ="root";
    $password ="root";
    $database ="vrfps";
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


function disconnect($conn)
{
    //Function might be necessary for cleaning up stuff so keeping
    mysqli_close($conn);
}

function queryDB($sql)
{
    $conn = connect();
    $result = $conn->query($sql);

    if(!$result)
    {
        echo "Query failed: " . $conn->error;
        disconnect($conn);
    }
    else
    {
        disconnect($conn);
        return $result;
    }
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