document.addEventListener("DOMContentLoaded", function () {
  defaultProgressionbarPosition();
});

function attachEventListeners() {
  document.getElementById("illimitedduration")?.addEventListener("change", function () {
    var mask = document.getElementById("mask");
    if (this.checked) {
      mask.style.display = "block";
      var option1 = document.getElementById("duration-input1").lastElementChild;

      var option2 = document.getElementById("duration-input2").children;

      option1.value = "";
      Array.from(option2).forEach((el) => {
        el.value = "0";
      });
    } else {
      mask.style.display = "none";
    }
  });

  var colorpalet = document.querySelectorAll(".color");
  colorpalet.forEach((el) => {
    el.addEventListener("click", function () {
      colorpalet.forEach((el) => {
        el.classList.remove("colorfocus");
        el.style.border = "none";
      });

      el.classList.add("colorfocus");
      var bgcolor = window.getComputedStyle(el).backgroundColor;
      var bordercolor = "rgb" + bgcolor.slice(4, bgcolor.lastIndexOf(",")) + ")";

      var preview = document.getElementById("minipoll");

      el.style.border = "1px solid " + bordercolor;

      preview.style.borderColor = bordercolor;

      var options = preview.lastElementChild.children;

      Array.from(options).forEach((el, index) => {
        if (index === 0) {
          el.style.backgroundColor = "rgba" + bordercolor.slice(3, bordercolor.length - 1) + ", 0.74 )";
        } else if (index === 1) {
          el.style.backgroundColor = "rgba" + bordercolor.slice(3, bordercolor.length - 1) + ", 0.2 )";
        } else if (index === 3) {
          el.style.backgroundColor = "rgba" + bordercolor.slice(3, bordercolor.length - 1) + ", 0.46 )";
        } else {
          el.style.backgroundColor = "rgb" + bordercolor.slice(3, bordercolor.length - 1) + ")";
        }
      });
    });
  });
}

function showContent(page = "home") {
  const contentDiv = document.getElementById("main-content");
  let url = "";

  switch (page) {
    case "home":
      url = "home.php";
      break;
    case "mypolls":
      url = "mypolls.php";
      break;
    case "test":
      url = "test.php";
      break;
  }
  fetch(url)
    .then((response) => response.text())
    .then((html) => {
      contentDiv.innerHTML = html;
      defaultProgressionbarPosition();
      attachEventListeners();

      if (url === "home.php") {
        document.getElementById("home").style.color = "rgb(255, 108, 113)";
        document.getElementById("mypolls").style.color = "";
      } else {
        document.getElementById("mypolls").style.color = "rgb(255, 108, 113)";
        document.getElementById("home").style.color = "";
      }
    });
}

function copyLink(code, event) {
  // Remplacez cette URL par celle que vous souhaitez copier
  var link = "http://localhost/devweb/poll/" + code + ".php";
  event.stopPropagation();
  // Copier le lien dans le presse-papier
  navigator.clipboard
    .writeText(link)
    .then(() => {
      alert("Lien copié dans le presse-papier !");
      closePoll();
    })
    .catch((err) => {
      console.error("Erreur lors de la copie du lien : ", err);
    });
}

function adaptColor(color) {
  var options = document.querySelector(".poll-content .options").children;

  Array.from(options).forEach((el) => {
    if (el.id.includes("option")) {
      el.style.borderColor = color;

      Array.from(el.children).forEach((el) => {
        if (el.id.includes("progression")) {
          el.style.backgroundColor = color;
        } else if (el.id.includes("percents")) {
          el.style.color = color;
        }
      });
    }
  });

  const seeVote = document.getElementById("see-vote");

  seeVote.addEventListener("mouseover", () => {
    seeVote.style.color = color;
  });

  seeVote.addEventListener("mouseout", () => {
    seeVote.style.color = "";
  });
}

function showPoll(pollId, color, bool) {
  $.ajax({
    url: "fetchpoll.php", // Page PHP qui va traiter les données
    type: "POST", // Méthode de la requête
    data: { id: pollId }, // Données à envoyer
    success: function (response) {
      var element = document.getElementsByTagName("main")[0];

      element.insertAdjacentHTML("beforeend", response);
      defaultProgressionbarPosition();
      document.body.classList.add("no-scroll");

      adaptColor(color);
      if (bool === "yes") {
        simulClick();
      }
    },
  });
}

function closePoll() {
  var popup = document.getElementById("poll-card");
  popup.remove();
  document.body.classList.remove("no-scroll");
}

function simulClick() {
  const button = document.getElementById("seevote");
  button.click();
}

function showVote(button) {
  const answers = button.closest(".poll-card").querySelectorAll('[id^="container-"]');

  const options = button.closest(".poll-card").querySelectorAll('[id^="option-"]');
  const numberOfOptions = options.length;

  const pollId = options[0].id.substr(options[0].id.lastIndexOf("-") + 1);

  answers.forEach((el) => {
    setTimeout(() => {
      el.style.display = "block"; // Affiche l'élément
      el.style.height = "0px";
      el.style.height = el.scrollHeight + "px";
    }, 800);
  });

  progressBar(pollId, numberOfOptions);

  button.style.display = "none";
  button.nextElementSibling.style.display = "block";
}

function hideVote(button) {
  const answers = button.closest(".poll-card").querySelectorAll('[id^="container-"]');

  const options = button.closest(".poll-card").querySelectorAll('[id^="option-"]');
  const numberOfOptions = options.length;
  const pollId = options[0].id.substr(-2);

  answers.forEach((el) => {
    // Masque l'élément
    el.style.height = "0px";
  });

  button.style.display = "none";
  button.previousElementSibling.style.display = "block";
}

function showMore(button) {
  // Sélectionne les éléments cachés dans le même conteneur que le bouton
  const hiddenVotes = button.parentElement.querySelectorAll(".hidden-vote");
  const element = button.parentElement.firstChild;
  let extrasize = 0;

  element.style.height = element.clientHeight - 20 + "px";

  hiddenVotes.forEach(function (el) {
    el.style.display = "flex";
    extrasize += 20;
  });

  requestAnimationFrame(() => {
    // Met à jour la hauteur de l'élément parent après que le DOM a été mis à jour
    element.style.height = element.clientHeight + extrasize - 20 + "px"; // Ajuste la hauteur
    button.parentElement.style.height = button.parentElement.clientHeight + extrasize + "px"; // Ajuste la hauteur du conteneur
  });

  // Cache le bouton "Afficher plus" après l'affichage des votes
  button.style.display = "none";
  button.nextElementSibling.style.display = "block";
}

function showLess(button) {
  // Sélectionne les éléments cachés dans le même conteneur que le bouton
  const showMoreButton = button.parentElement.querySelector("#showmore");
  const hiddenVotes = button.parentElement.querySelectorAll(".hidden-vote");
  const element = button.parentElement.firstChild;

  let extrasize = 0;

  hiddenVotes.forEach(function (el) {
    extrasize += 20;
  });

  requestAnimationFrame(() => {
    // Met à jour la hauteur de l'élément parent après que le DOM a été mis à jour
    element.style.height = element.clientHeight - extrasize - 20 + "px"; // Ajuste la hauteur
    button.parentElement.style.height = button.parentElement.clientHeight - extrasize + "px"; // Ajuste la hauteur du conteneur
  });

  setTimeout(() => {
    hiddenVotes.forEach(function (el) {
      el.style.display = "none";
    });
  }, 500);

  showMoreButton.style.display = "block";
  // Cache le bouton "Afficher plus" après l'affichage des votes
  button.style.display = "none";
}

function defaultProgressionbarPosition() {
  var allProgressionBar = document.querySelectorAll(`[id^="progression-"]`);
  allProgressionBar.forEach(function (el) {
    el.style.left = "-" + (el.offsetWidth + 10) + "px";
    el.style.width = el.offsetWidth + 10 + "px";
  });
}

function progressBar(pollid, nbr_options) {
  $.ajax({
    url: "results.php", // Page PHP qui va traiter les données
    type: "POST", // Méthode de la requête
    data: { id: pollid }, // Données à envoyer
    success: function (response) {
      // Traitement de la réponse
      let numbers = response
        .split(",")
        .map((item) => item.trim()) // Enlève les espaces vides
        .filter((item) => item !== "") // Exclure les éléments vides
        .map(Number); // Convertir les éléments en nombres

      // Affichage des pourcentages et progression
      let sorted_percents = [];

      numbers.forEach((element) => {
        sorted_percents.push(element);
      });

      sorted_percents.sort((a, b) => a - b);

      sorted_percents.forEach((element) => {});

      let distinctnumbersCount = new Set(numbers).size;

      let dict = createDictionary(sorted_percents, opacity(20, distinctnumbersCount));
      for (var i = 0; i < nbr_options; i++) {
        $("#progression-" + (i + 1) + "-poll-" + pollid).animate(
          { left: calculProgression(i, pollid, numbers, Math.max(...numbers)) },
          800
        );
        percentsAnimation(i + 1, pollid, numbers, Math.max(...numbers));
        opacityAnimation(i + 1, pollid, numbers, dict);
      }
      numbers.forEach((element) => {});
    },
  });
}

function createDictionary(liste1, liste2) {
  let dict = {};
  let association = {}; // Pour stocker les associations déjà faites
  let index = 0;

  liste1.forEach(function (item) {
    if (!association[item]) {
      // Si l'élément n'a pas encore été associé
      association[item] = liste2[index]; // Associe l'élément à la prochaine valeur de liste2
      index = (index + 1) % liste2.length; // Passe à la valeur suivante de liste2
    }
    dict[item] = association[item]; // Associe l'élément dans le dictionnaire final
  });

  return dict;
}

function opacity(debut, nb_element) {
  let step = 0;
  if (nb_element > 1) {
    step = (100 - debut) / (nb_element - 1);
  } else {
    step = 1;
    debut = 100;
  }

  let liste = [];

  for (let i = 0; i < nb_element; i++) {
    liste.push(debut + i * step);
  }

  liste.forEach((element) => {});

  return liste;
}

function calculProgression(i, pollid, numbers, max) {
  let adjustment = numbers[i] === 0 ? 40 : 20;
  let offset = numbers[i] === 100 ? 10 : 15;

  if (numbers[i] === max) {
    if (max === 0) {
      var progressiontype = -1 * $(`#option-${i + 1}-poll-${pollid}`).width() - 34;
    } else {
      var progressiontype = -10;
    }
  } else {
    progressiontype =
      -1 * $(`#option-${i + 1}-poll-${pollid}`).width() -
      34 +
      ($(`#option-${i + 1}-poll-${pollid}`).width() + 34 - 10) * (numbers[i] / max);
  }
  return progressiontype;
}

function percentsAnimation(i, pollid, numbers, max) {
  let div = document.getElementById(`option-${i}-poll-${pollid}`);
  let elementId = `percents-poll-${pollid}-option-${i}`;

  let element = document.querySelector(`#${elementId}`);

  if (element.textContent === "") {
    div.append(element);
    element.innerText = `0%`;
  }

  let currentValue = parseInt(element.textContent);
  let targetValue = numbers[i - 1];

  // Calcul de l'incrément et du délai
  let increment = currentValue < targetValue ? 1 : -1;
  let delay = 800 / Math.abs(targetValue - currentValue);

  let interval = setInterval(() => {
    // Met à jour la valeur tant que la cible n'est pas atteinte
    if ((increment >= 0 && currentValue < targetValue) || (increment <= 0 && currentValue > targetValue)) {
      currentValue += increment;
      element.textContent = `${currentValue}%`;
    } else {
      if (targetValue % 1 !== 0) {
        element.textContent = `${Math.round(targetValue * 100) / 100}%`;
      }
      clearInterval(interval); // Arrête l'animation

      // Change la couleur après l'animation
      if (max !== 0) {
        if (numbers[i - 1] === max) {
          element.style.color = "White";
        }
      }
    }
  }, delay);
}

function opacityAnimation(i, pollid, numbers, dict) {
  let elementId = `progression-${i}-poll-${pollid}`;

  let element = document.querySelector(`#${elementId}`);
  let style = window.getComputedStyle(element);

  let currentopacity = parseFloat(style.opacity) * 100;

  let targetopacity = dict[numbers[i - 1]];
  // Calcul de l'incrément et du délai
  let increment = currentopacity < targetopacity ? 1 : -1;
  let delay = 800 / Math.abs(targetopacity - currentopacity);

  let interval = setInterval(() => {
    // Met à jour la valeur tant que la cible n'est pas atteinte
    if ((increment > 0 && currentopacity < targetopacity) || (increment < 0 && currentopacity > targetopacity)) {
      currentopacity += increment;

      $("#progression-" + i + "-poll-" + pollid).css("opacity", `${currentopacity / 100}`);
    } else {
      clearInterval(interval); // Arrête l'animation
    }
  }, delay);
}

function showStage(selector) {
  var element = document.getElementById("stage-" + selector.classList[0].substr(-1));

  var stages = document.querySelectorAll(`[id*="stage-"]`);
  var selectors = document.querySelectorAll(`[class*="stageselector"]`);
  var progressionbar = document.getElementById("stagecompletion-bar");

  stages.forEach(function (el) {
    el.style.display = "none";
  });

  selectors.forEach(function (el) {
    el.classList.remove("selectorfocus");
  });

  element.style.display = "block";

  progressionbar.style.width = 40 * +selector.className.substr(-1) + "px";
  document.getElementById("stageprogression").firstElementChild.innerHTML =
    "Étape " + +selector.className.substr(-1) + " sur 3";
  selector.classList.add("selectorfocus");
}

function nextStage(button) {
  showStage(document.querySelector(".stageselector" + (+button.parentElement.parentElement.id.substr(-1) + 1)));
}

function showDurationOption(button) {
  var focused = document.getElementById("focusedoption");
  if (+button.id.substr(-1) === 1) {
    var option1 = document.querySelector("#duration-input" + +button.id.substr(-1));
    var option2 = document.querySelector("#duration-input" + (+button.id.substr(-1) + 1));

    var timer = document.getElementById("duration-input2").children;

    option1.style.left = "50%";
    option2.style.left = "140%";
    option2.value = "";
    focused.style.left = "3px";

    Array.from(timer).forEach((el) => {
      el.value = "0";
    });
  } else {
    var option1 = document.querySelector("#duration-input" + +button.id.substr(-1));
    var option2 = document.querySelector("#duration-input" + (+button.id.substr(-1) - 1));
    option1.style.left = "50%";
    option2.style.left = "-50%";

    option2.lastElementChild.value = "";
    focused.style.left = "calc(50% - 3px)";
  }
}

function showpopup() {
  var popup = document.getElementById("popup-new-poll");
  popup.style.display = "flex";
  document.body.classList.add("no-scroll");
}

function cancelNewPoll() {
  var popup = document.getElementById("popup-new-poll");
  popup.style.display = "none";
  popup.remove();
  $.ajax({
    url: "createpoll.php", // Page PHP qui va traiter les données
    success: function (response) {
      var element = document.getElementsByTagName("main")[0];

      element.insertAdjacentHTML("beforeend", response);
      attachEventListeners();
    },
  });
}

function closepopup() {
  var popup = document.getElementById("popup-new-poll");
  popup.style.display = "none";
  document.body.classList.remove("no-scroll");
}

function createinput(button) {
  var optionslist = document.getElementById("options");
  var option = document.getElementById("option");
  const clone = option.cloneNode(true);
  clone.querySelector("input").value = "";

  if (optionslist.childElementCount < 11) {
    optionslist.insertBefore(clone, button);
  }

  if (optionslist.childElementCount === 11) {
    optionslist.lastElementChild.style.display = "none";
  }
}

function removeinput(button) {
  var optionslits = document.getElementById("options");
  if (optionslits.childElementCount > 3) {
    button.parentElement.remove();
  }

  optionslits.lastElementChild.style.display = "block";
}

function sendAnswer(option, pollid, optionId, nbr_options) {
  // Récupérer les données du formulaire
  var formData = {
    option_id: optionId,
    poll_id: pollid,
  };

  var allOptions = document.querySelectorAll(`[id*="option-"][id*="-poll-${pollid}"]`);

  allOptions.forEach(function (el) {
    el.style.borderWidth = "1px"; // Réinitialiser la bordure
    el.style.boxShadow = "none"; // Réinitialiser l'ombre
  });

  // Sélectionner l'élément cliqué
  var element = document.getElementById(option);

  // Appliquer les nouveaux styles à l'option cliquée
  element.style.borderWidth = "2px";
  element.style.boxShadow = "0px 0px 10px 2px rgba(0, 0, 0, 0.2)";

  // Envoyer la requête AJAX
  $.ajax({
    url: "process.php", // Page PHP qui va traiter les données
    type: "POST", // Méthode de la requête
    data: formData, // Données à envoyer
    success: function (response) {
      var assertion = response;

      // Ajouter un délai avant d'appeler progressBar
      setTimeout(function () {
        progressBar(pollid, nbr_options);
      }, 250); // Délai de 2000 millisecondes (2 secondes)
    },
  });
}
