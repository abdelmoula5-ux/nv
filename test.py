liste1 = [20, 40, 10, 15, 15]
n = len(liste1) - 1  # n est la longueur de liste1 moins 1
step = 90 // n       # Calcul du pas entier
liste = [i for i in range(10, 100 + step, step)]  # Incrémente jusqu'à (100 + step) pour inclure 100

print(liste)