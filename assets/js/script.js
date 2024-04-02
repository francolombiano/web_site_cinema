// Fonction pour afficher ou cacher le mot de passe

function showPass() {
  // let mdp = document.getElementById("mdp");
  // let confirmMdp = document.getElementById("confirmMdp");

  if (mdp.type === "password") {
    mdp.type = "text";
    confirmMdp.type = "text";
  } else {
    mdp.type = "password";
    confirmMdp.type = "password";
  }
}
