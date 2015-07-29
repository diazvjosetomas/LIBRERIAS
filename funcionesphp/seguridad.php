<?

function antiChismoso()

{

    $script=explode(".php",$_SERVER["PHP_SELF"]);

    if($script[0]!="/administrativo/index")

    {
      echo "<script>window.location='../index.php'; </script>"; 

    }

}

?>