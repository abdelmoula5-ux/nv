<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p id="number">10</p>
    <script>
        // Sélectionne l'élément <p>

        function percentsAnimation() {
    let element = document.getElementById("number");
    let currentValue = parseInt(element.textContent);
    let targetValue = 20;
    
    // Calcul de l'incrément et du délai
    let increment = currentValue < targetValue ? 1 : -1;
    let delay = 800 / Math.abs(targetValue - currentValue);

    let interval = setInterval(() => {
        // Met à jour la valeur tant que la cible n'est pas atteinte
        if ((increment > 0 && currentValue < targetValue) || (increment < 0 && currentValue > targetValue)) {
            currentValue += increment;
            element.textContent = currentValue;
        } else {
            clearInterval(interval); // Arrête l'animation
        }
    }, delay);
}

percentsAnimation();

    </script>
</body>
</html>