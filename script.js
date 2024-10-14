// Listas de raças de cães e gatos
const racasCao = [
  "Akita",
  "Basset Hound",
  "Beagle",
  "Border Collie",
  "Boxer",
  "Bulldog Francês",
  "Cocker Spaniel",
  "Collie",
  "Dachshund",
  "Dálmata",
  "Doberman",
  "Golden Retriever",
  "Husky Siberiano",
  "Labrador Retriever",
  "Lhasa Apso",
  "Maltês",
  "Pastor Alemão",
  "Pastor Belga",
  "Pinscher",
  "Pit Bull",
  "Poodle",
  "Pug",
  "Rottweiler",
  "São Bernardo",
  "Shih Tzu",
  "Schnauzer",
  "Spitz Alemão (Lulu da Pomerânia)",
  "SRD",
  "Staffordshire Bull Terrier",
  "Yorkshire Terrier",
  "Outros",
];

const racasGato = [
  "Abissínio",
  "American Shorthair",
  "Angorá",
  "Bengal",
  "Birmanês",
  "British Shorthair",
  "Burmilla",
  "Chartreux",
  "Cornish Rex",
  "Devon Rex",
  "Exótico",
  "Havana Brown",
  "LaPerm",
  "Maine Coon",
  "Manx",
  "Munchkin",
  "Norueguês da Floresta",
  "Ocicat",
  "Oriental Shorthair",
  "Persa",
  "Peterbald",
  "Ragdoll",
  "Scottish Fold",
  "Selkirk Rex",
  "Siamês",
  "Siberiano",
  "Singapura",
  "Somali",
  "Sphynx",
  "SRD",
  "Tonquinês",
  "Outros",
];

// Evento para atualizar as opções de raça com base na espécie selecionada
document.getElementById("especie").addEventListener("change", function () {
  const especie = this.value;
  const racaSelect = document.getElementById("raca");
  const outraRacaInput = document.getElementById("outraRaca");
  racaSelect.innerHTML = "";

  let racas = [];
  if (especie === "Cão") {
    racas = racasCao;
  } else if (especie === "Gato") {
    racas = racasGato;
  }

  racas.forEach((raca) => {
    const option = document.createElement("option");
    option.value = raca;
    option.textContent = raca;
    racaSelect.appendChild(option);
  });

  racaSelect.addEventListener("change", function () {
    if (this.value === "Outros") {
      outraRacaInput.style.display = "block";
    } else {
      outraRacaInput.style.display = "none";
    }
  });
});

// Dispara o evento de mudança para preencher as opções de raça inicialmente
document.getElementById("especie").dispatchEvent(new Event("change"));

// Validação de data de nascimento
document
  .getElementById("cadastroPetForm")
  .addEventListener("submit", function (event) {
    const dataNascimentoPet =
      document.getElementById("dataNascimentoPet").value;
    const dataNascimento = new Date(dataNascimentoPet);
    const hoje = new Date();
    const anoMinimo = 1900;
    const dataMaxima = new Date();
    dataMaxima.setFullYear(dataMaxima.getFullYear() - 25);

    if (
      isNaN(dataNascimento.getTime()) ||
      dataNascimento > hoje ||
      dataNascimento < dataMaxima ||
      dataNascimento.getFullYear() < anoMinimo
    ) {
      event.preventDefault();
      alert(
        "Por favor, insira uma data de nascimento válida. O animal deve ter no máximo 25 anos."
      );
    }
  });

// Alterna a visibilidade da barra lateral
document.querySelector(".menu-toggle").addEventListener("click", function () {
  document.querySelector(".sidebar").classList.toggle("active");
});

// Redirecionamento de navegação
document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    const href = this.getAttribute("href");
    window.location.href = href;
  });
});

// Redirecionamento para logout
document.querySelector(".logout").addEventListener("click", function (event) {
  event.preventDefault();
  window.location.href = "index.php";
});

// Redirecionamento para atualizar pet
document.querySelectorAll(".update-pet").forEach((button) => {
  button.addEventListener("click", function () {
    const petId = this.getAttribute("data-id");
    console.log("Pet ID:", petId); // Adiciona log para depuração
    window.location.href = "perfil.php?id=" + petId;
  });
});

document.getElementById("update-tutor").addEventListener("click", function () {
  const tutorId = this.getAttribute("data-id");
  window.location.href = "atualizar.php?id=" + tutorId;
});

document.querySelectorAll(".delete-pet").forEach((button) => {
  // Seleciona todos os botões de exclusão de pets
  button.addEventListener("click", function () {
    const petId = this.getAttribute("data-id");
    if (confirm("Tem certeza que deseja excluir este pet?")) {
      console.log("Enviando requisição para excluir o pet com ID:", petId); // Log para depuração
      fetch("deletarPet.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ idPet: petId }), // Envia o ID do pet a ser excluído no corpo da requisição
      })
        .then((response) => {
          console.log("Resposta recebida:", response); // Log para depuração
          return response.json();
        })
        .then((data) => {
          console.log("Dados recebidos:", data); // Log para depuração
          if (data.status === "success") {
            alert("Pet excluído com sucesso.");
            location.reload(); // Recarrega a página para atualizar a lista de pets
          } else {
            alert("Falha ao excluir o pet: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Erro:", error);
          alert("Ocorreu um erro ao tentar excluir o pet.");
        });
    }
  });
});
