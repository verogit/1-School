<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title;?></title>
        <meta charset="utf-8" />
 		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>
	
	<body>
	    <header id="header">
            <h1><img id="logo" src="../images/logo.png" alt="logo"></img></h1>
            <nav>
			    <div id="nav">
			            <ul class="topnav">
			                <li class="menu">Menu</li>
				            <li><a href="home.php">Home</a></li>
				            <li class="separetion">|</li>
				            <li><a href="news.php">News</a></li>
				            <li class="separetion">|</li>
				            <li><a href="admissions.php">Admissions</a></li>
				            <li class="separetion">|</li>
				            <li><a href="contactUs.php">Contant Us</a></li>
				            <li class="icon">
                                <a href="javascript:void(0);" style="font-size:20px;" onclick="headerChange()">☰</a>
                            </li>
                        </ul>
			        </div>
	    	</nav>
        </header>

        <div id="content">
            
    <script type="text/javascript">
        function headerChange() {
            document.getElementsByClassName("topnav")[0].classList.toggle("responsive");
        }
    </script>
        
