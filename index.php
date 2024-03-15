<!DOCTYPE html>
<html>
    <head>
    <script src="https://www.gstatic.com/charts/loader.js"></script>

        <title>
            Data Visualizer
        </title>
        <style type="text/css">
            h1{
                font-size: 55px;
                text-align: center;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            h2{
                text-align: center;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .form-container{
                margin-top: 50px;
                align-self: center;
                display: flex;
                justify-content: center;
            }
            form{
                display: flex;
                flex-direction: row;
                justify-content: space-evenly;
                align-items: center;
                flex-wrap: wrap;
                gap: 2em;
                width: 50%;
            }
            #myChart{
                display: flex;
                justify-content: center;
            }
            textarea{
                width: 60%;
                height: 200px;
            }
            label{
                font-size: 20px;
                font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            }
            input:focus{
                background-color: rgba(200,100,234,0.5);
            }

        </style>
    </head>
    <body>
        <h1>Data Visualizer</h1>
        <h2>SQL Data Input</h2>
        <div class="form-container">
            <form method="post" action="index.php">
                <label>SQL Host</label>
                <input required type="text" name="sqlHost"></input>
                <label>SQL Database</label>
                <input required type="text" name="sqlDb"></input><br/>
                <label>SQL Username</label>
                <input type="text" name="sqlUsername"></input>
                <label>SQL Password</label><br />
                <input required type="password" name="sqlPassword"></input>
                <label>SQL Query</label>
                <textarea type="text" name="sqlQuery"></textarea> <br/>
                <input type="submit" onsubmit="drawChart"></input>

            </form>
        </div>
    <?php 
    $host = $_POST["sqlHost"];
    $dbName = $_POST["sqlDb"];
    $username = $_POST["sqlUsername"];
    $password = $_POST["sqlPassword"];
    $query = $_POST["sqlQuery"];

    try{
        $connection = mysqli_connect($host,$username,$password,$dbName);
        echo "Connection Successful";       
        $result = mysqli_query($connection,$query);        
        $jsonData = json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
    }catch(PDOException $e){
        echo "Connection Error ".$e->getMessage();
    }
    ?>

        <div id="myChart" style="max-width:1000px; height:800px; margin-left:100px">
            <script>
            google.charts.load('current',{packages:['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {                
                var jsstring = '<?php echo $jsonData; ?>';
                let jsonArray = JSON.parse(jsstring)
                function convertToArray(){
                    let arrayData = [Object.keys(jsonArray[0])];
                    for (let i=0; i<jsonArray.length;i++){
                            arrayData.push(Object.values(jsonArray[i]).map((values) => {if(!values.includes("-")){return parseInt(values)}else{return values}}));
                    }
                    return arrayData;
                }
                console.log(convertToArray())
                const data = google.visualization.arrayToDataTable(convertToArray());
                const options = {
                title: '<?php echo $dbName; ?>'
                };

                const chart = new google.visualization.BarChart(document.getElementById('myChart'));
                chart.draw(data, options);

            }
            </script>
        </div>
    </body>
</html>
