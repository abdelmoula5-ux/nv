let liste = [20];

// Tri croissant
liste.sort((a, b) => a - b);
console.log(liste); // Résultat : [5, 10, 15, 20, 30]

// Tri décroissant
liste.sort((a, b) => b - a);
console.log(liste); // Résultat : [30, 20, 15, 10, 5]
