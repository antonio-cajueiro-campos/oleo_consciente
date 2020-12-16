<?php
if (isset($_GET['to']) && isset($_GET['id']) && isset($_GET['type'])) {
    $to = $_GET['to'];
    $msg = $_GET['id'];
    $type = $_GET['type'];

?>
<script>
    var data = 'msgShow(<?php echo $msg; ?>, <?php echo $type; ?>)';
    localStorage.setItem('msgHtml', data);
    location.href = "..\\<?php echo $to; ?>.php";
</script>
<?php } ?>