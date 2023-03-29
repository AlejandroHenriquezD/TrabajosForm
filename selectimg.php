<?php

$logos = json_decode(file_get_contents("http://localhost/API/logos"), true);

for ($s = 0; $s < count($logos); $s++) {
    echo "<p><select name='img' onchange='updateImage(this.value, \"a-$s\")'>";
    for ($p = 0; $p < count($logos); $p++) {
        echo "<option value='" . $logos[$p]['id'] . "'>Logo " . $p + 1 . "</option>";
    }
    echo "</select></p>";

    echo "<img id='a-$s' src='' alt='logo' height='25%'/>";
}
?>

<script>
    function updateImage(id, logo) {
        var img = document.getElementById(logo);
        var logos = <?php echo json_encode($logos); ?>;
        for (var i = 0; i < logos.length; i++) {
            if (logos[i].id == id) {
                img.src = logos[i].img;
                img.alt = logos[i].img;
                break;
            }
        }
    }
</script>