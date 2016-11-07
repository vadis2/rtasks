<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    
    <title>Test page</title>
    
    <!-- Bootstrap core CSS -->
    <link href="/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="/assets/starter-template.css" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <link href="/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php/test/start">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
    
    <div class="starter-template">
        <h1>Page for testings</h1>
    </div>
    <div class="row bg-info bg-info-margin">
        <div class="col-md-5">
            <dl class="dl-horizontal">
                <dt>All Tasks</dt>
                <dd><?php echo count($tasksAll); ?></dd>
                <dt>Active Tasks</dt>
                <dd><?php echo count($tasksActive); ?></dd>
                <dt>Incoming Tasks</dt>
                <dd><?php echo count($tasksIncoming); ?></dd>
            </dl>
            </p>
        </div>
        <div class="col-md-7">
            <div class="row">
                <a class="btn btn-default pull-left margin-right-10" href="/index.php/test/clear-tasks">Clear Tasks</a>
                <a class="btn btn-default pull-left margin-right-10" href="/index.php/test/clear-incoming-tasks">Clear
                    Incoming Tasks</a>
                <a class="btn btn-default pull-left" href="/index.php/test/clear-all-tasks">Clear All</a>
            </div>
            <div class="row">
                <a class="btn btn-default margin-right-10 margin-top-20" href="/index.php/test/calculate">Calculate</a>
            </div>
        </div>
    </div>
    <div class="row bg-warning bg-warning-margin">
        <div class="col-md-5"><p>Last tasks</p></div>
        <div class="col-md-7">
            <a class="btn btn-default margin-right-10" href="/index.php/test/add-last-tasks">Add Last Tasks</a>
        </div>
    </div>
    <div class="row bg-warning bg-warning-margin">
        <div class="col-md-5"><p>Future tasks</p></div>
        <div class="col-md-7">
            <a class="btn btn-default margin-right-10" href="/index.php/test/add-future-tasks">Add Future Tasks</a>
        </div>
    </div>
    <div class="row bg-warning bg-warning-margin">
        <div class="col-md-5">
            <p>Non repeatable active tasks</p>
            <p><em class="small">Task Start After Interval Start and Before Interval End</em></p>
        </div>
        <div class="col-md-7">
            <a class="btn btn-default margin-right-10" href="/index.php/test/add-non-repeatable-tasks">Add Non Repeatable Tasks</a>
        </div>
    </div>
    <div class="row bg-warning bg-warning-margin">
        <div class="col-md-5">
            <p>Active tasks Repeatable daily</p>
            <p><em class="small">Task Start After Interval Start and Before Interval End</em></p>
        </div>
        <div class="col-md-7">
            <a class="btn btn-default margin-right-10" href="/index.php/test/add-daily-repeatable-tasks">Add Daily Repeatable Tasks</a>
        </div>
    </div>
    <div class="row bg-warning bg-warning-margin">
        <div class="col-md-5">
            <p>Active tasks Repeatable weekly</p>
            <p><em class="small">Task Start After Interval Start and Before Interval End</em></p>
        </div>
        <div class="col-md-7">
            <a class="btn btn-default margin-right-10" href="/index.php/test/add-weekly-repeatable-tasks">Add Weekly Repeatable Tasks</a>
        </div>
    </div>
    <div class="row bg-warning bg-warning-margin">
        <div class="col-md-5">
            <p>Active tasks Repeatable monthly</p>
            <p><em class="small">Task Start After Interval Start and Before Interval End</em></p>
        </div>
        <div class="col-md-7">
            <a class="btn btn-default margin-right-10" href="/index.php/test/add-monthly-repeatable-tasks">Add Monthly Repeatable Tasks</a>
        </div>
    </div>
    <div class="row bg-warning bg-warning-margin">
        <div class="col-md-5">
            <p>Active tasks Repeatable yearly</p>
            <p><em class="small">Task Start After Interval Start and Before Interval End</em></p>
        </div>
        <div class="col-md-7">
            <a class="btn btn-default margin-right-10" href="/index.php/test/add-yearly-repeatable-tasks">Add Yearly Repeatable Tasks</a>
        </div>
    </div>
    <div class="row">
        <p><pre><?php print_r($tasksAll); ?></pre></p>
    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/libs/jquery/dist/jquery.min.js"><\/script>')</script>
<script src="/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
