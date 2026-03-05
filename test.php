<?php 
    require "config/config.php";
?>

<html>


<script>
function showSelectedText(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex].text;
    document.getElementById('day-text').innerHTML = `(${selectedOption})`;
}
</script>
</html>
    
    
