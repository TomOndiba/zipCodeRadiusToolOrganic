<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/images/fave.ico">
        <script type='text/javascript' src='/twc_includes/js/jquery-1.11.1.min.js'></script>
        <script type='text/javascript' src='js.js'></script>
        <title>Zip Radius Tool</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="/narrow.css" rel="stylesheet">
    </head>
    <body>
        
    <div class="container">
        <div class="header clearfix">
            <nav>
                <ul class="nav nav-pills pull-right">
                    <li role="presentation"><a href="../../index.html">Examples</a></li>
                    <li role="presentation"><a href="skills.html">Tech Skills</a></li>
                    <li role="presentation"><a href="contact/contact.html">Contact</a></li>
                </ul>
            </nav>
            <h3 class="text-muted">Zip Radius Tool</h3>
        </div>
            <form id="formwhatever" action="" method="post" name="form" enctype="multipart/form-data">
                <div class="row marketing">
                    <p>Organic version of the Zip Radius Tool API that uses a MySQL database of zip codes and corresponding lats and longs to finds the zip codes for a list of  zip codes  within the radius selected.</p>

                    <div class="col-lg-8">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Enter Zip Codes (Comma separated list)</h3>
                            </div>
                            <div class="panel-body">
                                <textarea class="form-control" id="form_zips" rows="6"></textarea>
                            </div>
                        </div> 
                    </div>
                    
                     <div class="col-lg-4">       
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Radius (Miles)</h3>
                            </div>
                            <div class="panel-body">
                                <select class="form-control" id="form_miles">
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                    <option value="4">Four</option>
                                    <option value="5" selected="true">Five</option>
                                    <option value="10">Ten</option>
                                    <option value="25">Twenty-five</option>
                                    <option value="50">Fifty</option>
                                    <option value="100">One-Hundred</option>
                                    
                                </select>
                            </div>
                        </div>  
                         
                        <div class="panel-body">
                             <input type="submit" class="btn btn-md btn-primary" value="Find" id="submit">   
                        </div>
                         
                    </div>
                </div>
            </form>
             
      <footer class="footer">
             <p>
                <a href="https://github.com/pwingard/zipCodeRadiusTool">Git</a>&nbsp;&middot;&nbsp;
                <a href="https://www.linkedin.com/in/pwingard">LinkedIn</a>
            </p>
          
            <p>&copy; <span id="year"></span> | Peter Wingard</p>
          
            <script type='text/javascript' >
                var today = new Date();
                var year = today.getFullYear();
                $('#year').text(year);
            </script>
            <small>Site written and tested in Mac Chrome</small>
            
      </footer>

    </div> <!-- /container -->
  </body>
</html>
