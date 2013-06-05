<head>
    <title>Erreur fatale</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<style>
    body{
        background: #00A;
        color: #fff;
        height: 100%;
        width: 100%;
        font-family: "fixedsys", "terminal", monospace;
    }
    h1{
        font-size: 144px;
        margin-top: 15px;
        margin-left: 125px;
        margin-bottom: 0;
    }
    h2{
        font-size: 36px;
        margin-left: 35px;
    }
    h3{
        background: #aaa;
        color: #00a;
        background-clip: content-box;
        text-align: center;
        font-size: 16px;
        font-weight: normal;
        display: table;
        margin: auto;
    }
    li{
        margin-left: 25px;
        list-style: none;
    }
    li:before{
        content: "* ";
    }
</style>
<body>
    <h1>:(</h1>
    <h2>Oups... Une erreur est survenue</h2>
    <p>
        Une erreur fatale est survenue, rendant l'exécution impossible...
    </p>
    <h3>Débug :</h3>
    <p>
        <li><b>Message : </b><?php echo $message?></li>
        <li><b>Fichier : </b><?php echo $file?></li>
        <li><b>Ligne&nbsp;&nbsp;&nbsp;: </b><?php echo $line?></li>
    </p>
    <h3>Trace :</h3>
    <pre><?php debug_print_backtrace()?></pre>
</body>
