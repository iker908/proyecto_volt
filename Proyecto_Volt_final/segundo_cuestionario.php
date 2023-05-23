<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title></title> 
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" >
    <link rel="stylesheet" href="css/segundo.css">
  </head>
  <body>
    <?php
    #este arhcivo recoge los datos del formulario interactivo y comprueba los resultados para poder decidir que tipo de tarifa escoger
      session_start();
    ?>
    <div id="container">
      <div id="questions">
        <h1 class="question">¿En qué momentos del día sueles utilizar más electricidad?</h1>
      </div>
      <div id="answers">
        <div id="answer-options"></div>
        <button id="next-button">Siguiente Pregunta</button>
      </div>
    </div>

    <div id="result">

    </div>

    <script>
      const container = document.getElementById("container");
      const nextButton = document.getElementById("next-button");
      const resultDiv = document.getElementById("result");
      const questions = [
        "¿En qué momentos del día sueles utilizar más electricidad?",
        "¿Apagas los electrodomésticos cuando no los utilizas?",
        "¿Dejas la luz encendida incluso cuando entra suficiente luz por la ventana?",
        "¿Qué tipo de electrodomésticos tienes en tu hogar?",
        "¿Utilizas dispositivos inteligentes para monitorear y controlar el consumo eléctrico en tu hogar?",
        "¿Tienes una rutina establecida para el uso de los electrodomésticos, como por ejemplo la lavadora?",
        "¿Utilizas la secadora para cada lavadora que pones?",
        "¿Utilizas mucho el aire acondicionado y la calefacción?",
        "¿Si tuvieras un día/horas a la semana en el que pagas menos luz cambiarías tu rutina para aprovecharlo?",
        "¿Trabajas en casa?",
        "¿Utilizas coche eléctrico?",
        "¿Si tienes coche eléctrico lo cargas de noche?"
      ];

      let currentQuestionIndex = 0;
      const userResponses = [];

      function showNextQuestion() {
        const selectedOption = document.querySelector('input[name="answer"]:checked');
        if (selectedOption) {
          userResponses.push(selectedOption.value);
        }

        currentQuestionIndex++;
        if (currentQuestionIndex >= questions.length) {
          container.style.display = "none";
          resultDiv.style.display = "block";
          redirectToResultsPage();
          return;
        }

        container.style.transform = "translateX(-100%)";
        setTimeout(() => {
          updateQuestion();
          container.style.transform = "translateX(0)";
        }, 500);
      }

      function updateQuestion() {
        const question = document.querySelector(".question");
        const answerOptionsDiv = document.getElementById("answer-options");
        const currentQuestion = questions[currentQuestionIndex];

        question.textContent = currentQuestion;

        // Eliminar las opciones de respuesta anteriores
        while (answerOptionsDiv.firstChild) {
          answerOptionsDiv.removeChild(answerOptionsDiv.firstChild);
        }

        // Crear y agregar las nuevas opciones de respuesta
        const answerOptions = getAnswerOptions(currentQuestion);
        for (const option of answerOptions) {
          const answerDiv = document.createElement("div");
          answerDiv.className = "answer";
          const input = document.createElement("input");
          input.type = "radio";
          input.name = "answer";
          input.value = option;
          const label = document.createElement("label");
          label.textContent = option;
          label.setAttribute("for", option);
          answerDiv.appendChild(input);
          answerDiv.appendChild(label);
          answerOptionsDiv.appendChild(answerDiv);
        }
      }

      function getAnswerOptions(question) {
        switch (question) {
          case "¿En qué momentos del día sueles utilizar más electricidad?":
            return ["En la mañana", "En la tarde", "En la noche"];
          case "¿Apagas los electrodomésticos cuando no los utilizas?":
            return ["Siempre", "A veces", "Nunca"];
          case "¿Dejas la luz encendida incluso cuando entra suficiente luz por la ventana?":
            return ["Sí", "Bastante", "Poco", "Nunca"];
          case "¿Qué tipo de electrodomésticos tienes en tu hogar?":
            return ["Todos son de alta eficiencia energética", "Algunos son de alta eficiencia energética", "Ninguno es de alta eficiencia energética"];
          case "¿Utilizas dispositivos inteligentes para monitorear y controlar el consumo eléctrico en tu hogar?":
            return ["Si", "No"];
          case "¿Tienes una rutina establecida para el uso de los electrodomésticos, como por ejemplo la lavadora?":
            return ["Sí, siempre sigo una rutina", "A veces sigo una rutina", "No tengo una rutina establecida"];
          case "¿Utilizas la secadora para cada lavadora que pones?":
            return ["Si", "A veces", "No"];
          case "¿Utilizas mucho el aire acondicionado y la calefacción?":
            return ["Si", "Bastante", "Poco", "Nunca"];
          case "¿Si tuvieras un día/horas a la semana en el que pagas menos luz cambiarías tu rutina para aprovecharlo?":
            return ["Si", "Solo un poco", "No"];
          case "¿Trabajas en casa?":
            return ["Si", "Solo unos días", "No"];
          case "¿Utilizas coche eléctrico?":
            return ["Si", "No"];
          case "¿Si tienes coche eléctrico lo cargas de noche?":
            return ["Si", "No", "No tengo"];
          default:
            return [];
        }
      }

      function redirectToResultsPage() {
        const encodedResponses = encodeURIComponent(JSON.stringify(userResponses));
        window.location.href = `guardar_respuesta.php?responses=${encodedResponses}`;
      }

      nextButton.addEventListener("click", showNextQuestion);
      updateQuestion();
    </script>
  </body>
</html>