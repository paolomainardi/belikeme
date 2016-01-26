<?php
    
class bill{
    /**
     * @var string The new image name, to be provided or will be generated.
     */
    protected $name;
    
    /**
     * @var string The full image path (dir + image + mime)
     */
    protected $fullPath;
    
    /**
     * @var string The folder or image storage location
     */
    protected $location;
    
    /**
     * @var string The folder or image storage location
     */
    protected $strings;
    
    /**
     * @var string The folder or image storage location
     */
    public $error;
    

    /**
     * Returns the image name
     *
     * @return string
     */
    public function getName()
    {
        if (!$this->name) {
           return  uniqid(true) . "_" . str_shuffle(implode(range("e", "q")));
        }
        return $this->name;
    }
    
    /**
     * Returns the full path of the image ex "location/image.mime"
     *
     * @return string
     */
    public function getFullPath()
    {
        $this->fullPath = $this->location . "/" . $this->name . ".jpg";
        return $this->fullPath;
    }
    

    public function setLocation($dir = "bills", $permission = 0666)
    {
        if (!file_exists($dir) && !is_dir($dir) && !$this->location) {
            $createFolder = @mkdir("" . $dir, (int) $permission, true);
            if (!$createFolder) {
                $this->error = "Folder " . $dir . " could not be created";
                return ;
            }
        }
        $this->location = $dir;
        return $this;
    }


    public function getLocation()
    {
        if(!$this->location){
            $this->setLocation(); 
        }
        return $this->location; 
    }

    public function upload($strings)
    {

    $bill = $this;
    
            /* set and get folder name */
        $bill->fullPath = $this->getLocation(). "/";
        $bill->name = $this->getName().'.jpg';
    
        //header("Content-type: image/jpeg");
        $imgPath = 'image.jpg'; //the bill template
        $image = imagecreatefromjpeg($imgPath);
        $color = imagecolorallocate($image, 0, 0, 0);
        $font = '/Library/Fonts/Arial Black.ttf';
        $fontsize ='30';

    
if(isset($_POST['does_what'])){
    
    if(empty(array_filter($_POST['does_what']))){
        $this->error = 'Your Bill dosent say ANYTHING...';
        return ;
    }

            if(empty($_POST['does_what'][0])){
                $this->error = 'Your Bill dosent have a name...';
                return ;
            }
           if(empty($_POST['does_what'][1])){
                $this->error = 'Your Bill dosent do anything...';
               return ;
            }
           if(empty($_POST['does_what'][2])){
                $this->error = 'Your Bill need to do more...';
               return ;
            }
           if(empty($_POST['does_what'][3])){
                $this->error = 'Your Bill need to do more...';
               return ;
            }
    
    
    $strings = array();
    foreach($_POST['does_what'] as $key => $value){
        if($key == 0){
            $strings[] = "This is " . $value;
        }else{
        $strings[] = $value;
        }
    }
}
    
    if(count($strings)>8){
        $this->error =  "To many bills";
        return ;
    }
    
        foreach($strings as $value){
            if(strlen($value)>31){
                $this->error = '<b>Whoops</b>, to long Bill conversation.';
                return ;
            }
    }
    
    $offset = 100;
    foreach($strings as $value){
        imagettftext($image, $fontsize, 0, 50, $offset, $color, $font, $value);
        $offset = $offset + 50;
    }


    if(!$this->moveUploadedFile($image, $bill->fullPath.$bill->name)){
        $this->error = 'Error with file creation';
        return ;
    }
    
    if(isset($this->error)){
        return false;
    }
    
header('Location: index.php?billpath='.$bill->name);

}
    
    /**
     * Final upload method to be called, isolated for testing purposes
     *
     * @param $tmp_name int the temporary location of the image file
     * @param $destination int upload destination
     *
     * @return bool
     */
    public function moveUploadedFile($tmp_name, $destination)
    {
        return imagejpeg($tmp_name, $destination);
    }
    
}

if(isset($_POST["submit"])){
   $formdata = $_POST["does_what"]; // dont forget to sanitize any post data
   //than you can call your class function and pass this data
   $bill = new bill();
    
   if(!$bill->upload($formdata)){
       $upload_error = TRUE;
   }
 }


if(isset($_GET['billpath'])){
    if(!preg_match('/^[a-z0-9_]+\.jpg$/', $_GET['billpath'])){
        echo 'Unknown error';
        return false;
    }
    $imagepreview = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['billpath']);
    $imagepreview = 'bills/' . $_GET['billpath'];
}else{
$imagesDir = 'bills/';
$images = glob($imagesDir . '*.{jpg}', GLOB_BRACE);
$imagepreview = $images[array_rand($images)]; // See comments
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>belikebill.se</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:url"                content="http://www.belikebill.se" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="Be like Bill, Bill is smart" />
    <meta property="og:description"        content="Bill minds his own business" />
    <meta property="og:image"              content="http://www.belikebill.se/<?php echo $imagepreview; ?>" />
    <meta property="og:image:type"         content="image/jpeg" />
    <meta property="og:image:width"        content="360" />
    <meta property="og:image:height"       content="188" />

    <link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAYMEi8HEBg+CxIbZxojMncRGiQxBgMDAQAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAQGCmkIDhT+CxIZ/w4WHv8IDhb/FyQy/xMbJt4XHyk1AAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGCg3QFR4o/wYJDf8SHSb/FiAp/wwSGP8SHSf/ChAW+A0T
HLAVHidcJzVCDwAAAAAAAAAAAAAAAAAAAAAKDhBOCg4T/R4oMf8JDBH/EBkk/yIyQv8PFh3/ChAX
/zRLX/8xQU//Fh8p/y0/U/guQVBuOk9gBAAAAAAAAAAACxAV4hMcKP8lM0D/DxYc/yM0S/9AZIz/
LD1O/xAYJv9Qanv/dZqv/xMZJv9Sb4b/c5mw/1t2iYcTGyIIAAAAABEVHdYpO07/VHye/xgfJv8c
Jzj/TGmB/yAtOv8YGx3/R1FW/1hnb/8RFhv/MEFT/2mLn/89UGH/b42h2IGtxQYoLDgtHiUz9zxc
eP8PEhT/Exok/zVDUf8MERj/ICMk/15jZf9obnH/HCEn/yEuPf9NbYX/Exon/01tjP9Lc5dDAAAA
ACUvP34wP1D/Cg8V/xchLv8hLDr/CQ0T/xUaIP9MXGX/S1xm/wsOFv8oNkT/UG6G/woOF/8/VW3/
S2aDjAAAAABFU2QHHyk26BAYIf4gLT7/ERkl/wwSGv8iMED/VGt6/1hygP8bIzP/Q2F//0Zlgv8d
JDT/RmJ+/0ZkgLEAAAAAAAAAAENXZhMwPk0oKzxPxhslNP8XIjDcKThL/115jf9kgpXXLDxV3mB8
lPU4Tmv/PV2B8kl1n2lHcZMCAAAAAAAAAAAAAAAAAAAAAHykuQNcc4UiHic0MTJBVv9xmLH/e6O5
fAAAAAA4UF4YQWKFqUt4oh8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABcbIyYvPVD/
faa9/4KtxR4AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAf
KDgrM0NX/3ecsf+BrMMcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAGSAqLyw9Vf9sjaTxaoWVBgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAElmaAMoOk3kT3mgvgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALkFTfE52nWMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAA//8AAMD/AACAPwAAgA8AAAADAAAAAQAAgAEAAMAAAADAAAAA8AMAAP53AAD+fwAA/n8AAP5/
AAD+fwAA//8AAA==" rel="icon" type="image/x-icon" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
      
    <!-- fa -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">


<div class="container">
<div class="row">
<div class="col-sm-4">
    <?php echo isset($upload_error) ? '<div class="alert alert-danger">' . $bill->error .'</div>' : ''; ?>
<img class="img-responsive" src="<?php echo $imagepreview; ?>"><br>
    <a href="<?php echo $imagepreview; ?>"><i class="fa fa-file-image-o"></i> URL to this Bill</a><br>
    
<a class="btn btn-lg btn-success" href="#">
  <i class="fa fa-facebook-official fa-2x pull-left"></i> Share your Bill!<br>on Facebook!</a><br><br>
    
        <a href="index.php"><button type="button" class="btn btn-info"><i class="fa fa-random"></i> Randomize another Bill!</button></a><br>
    
    </div>
    
    <div class="col-sm-4">
        <h1>Make your own Bill!</h1>
<form action="index.php" method="post">
    <div class="form-group">
<label>Name: </label>
    <input type="text" id="namn" class="form-control pull-right" name="does_what[]" required>Chars left: <span id="chars_namn"></span>
    </div>
    
    <div class="form-group">
<label>Does what?: </label>
    <input type="text" id="does_what_1" class="form-control pull-right" name="does_what[]" required>Chars left: <span id="chars_does_what_1"></span>
    </div>
    
        <div class="form-group">
<label>Does what?: </label>
    <input type="text" id="does_what_2" class="form-control pull-right" name="does_what[]" required>Chars left: <span id="chars_does_what_2"></span>
        </div>
    
            <div class="form-group">
<label>Does what?: </label>
    <input type="text" id="does_what_3" class="form-control pull-right" name="does_what[]" required>Chars left: <span id="chars_does_what_3"></span>
            </div>
            
            <div class="form-group">
<label>Does what? (optional): </label>
    <input type="text" id="does_what_4" class="form-control pull-right" name="does_what[]">Chars left: <span id="chars_does_what_4"></span>
            </div>
            
            <div class="form-group">
<label>Does what? (optional): </label>
    <input type="text" id="does_what_5" class="form-control pull-right" name="does_what[]">Chars left: <span id="chars_does_what_5"></span>
            </div>
            
            <div class="form-group">
<label>Does what? (optional): </label>
    <input type="text" id="does_what_6" class="form-control pull-right" name="does_what[]">Chars left: <span id="chars_does_what_6"></span>
            </div>
            
            <div class="form-group">
<label>Does what? (optional): </label>
    <input type="text" id="does_what_7" class="form-control pull-right" name="does_what[]">Chars left: <span id="chars_does_what_7"></span>
            </div>
            
          <div class="form-group">  
<input type="submit" class="btn btn-default" name="submit" value="submit">
            </div>
    
            </form>
    </div></div></div>


<style>
    .container { margin-top: 5px; }
</style>
<!-- Latest compiled and minified jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>

<!-- Latest compiled and minified Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<script>
(function($) {
    $.fn.extend( {
        limiter: function(limit, elem) {
            $(this).on("keyup focus", function() {
                setCount(this, elem);
            });
            function setCount(src, elem) {
                var chars = src.value.length;
                if (chars > limit) {
                    src.value = src.value.substr(0, limit);
                    chars = limit;
                }
                elem.html( limit - chars );
            }
            setCount($(this)[0], elem);
        }
    });
})(jQuery);
    
var chars_namn = $("#chars_namn");
$("#namn").limiter(30, chars_namn);
    
var chars_does_what_1 = $("#chars_does_what_1");
$("#does_what_1").limiter(30, chars_does_what_1);
    
var chars_does_what_2 = $("#chars_does_what_2");
$("#does_what_2").limiter(30, chars_does_what_2);
    
var chars_does_what_3 = $("#chars_does_what_3");
$("#does_what_3").limiter(30, chars_does_what_3);
    
var chars_does_what_4 = $("#chars_does_what_4");
$("#does_what_4").limiter(30, chars_does_what_4);

var chars_does_what_5 = $("#chars_does_what_5");
$("#does_what_5").limiter(30, chars_does_what_5);

var chars_does_what_6 = $("#chars_does_what_6");
$("#does_what_6").limiter(30, chars_does_what_6);
    
var chars_does_what_7 = $("#chars_does_what_7");
$("#does_what_7").limiter(30, chars_does_what_7);

</script>
    
    </body>
</html>