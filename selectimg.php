<?php

$logos = json_decode(file_get_contents("http://localhost/API/logos"), true);

echo "<select name='img' onchange='updateImage(this.value)'>";
for ($p = 0; $p < count($logos); $p++) {
    echo "<option value='" . $logos[$p]['id'] . "'>Logo " . $p + 1 . "</option>";
}
echo "</select>";

echo "<img id='logo-img' src='https://media.tenor.com/x8v1oNUOmg4AAAAd/rickroll-roll.gif' alt='logo' height='95%'/>";

?>

<script>
    function updateImage(id) {
        var img = document.getElementById('logo-img');
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