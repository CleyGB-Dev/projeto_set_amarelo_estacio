
document.getElementById("form").addEventListener("submit", function (e) {
  const nome = document.getElementById("name");
  const email = document.getElementById("email");
  const nascimento = document.getElementById("birthdate");
  const bairro = document.getElementById("bairro");
  const alunoEstacio = document.getElementById("alunos_estacio");
  const comorbidade = document.getElementById("comorbidade");
  const genero = document.querySelector('input[name="gender"]:checked');
  const honeypot = document.getElementById("hpfield");

  let erros = [];

  if (!nome.value.trim()) erros.push("Nome completo");
  if (!nascimento.value.trim()) erros.push("Data de nascimento");
  if (!email.value.trim() || !email.value.includes("@")) erros.push("E-mail válido");
  if (!bairro.value.trim()) erros.push("Bairro");
  if (!alunoEstacio.value) erros.push("Aluno Estácio");
  if (!comorbidade.value) erros.push("Comorbidade");
  if (!genero) erros.push("Gênero");
  if (honeypot.value !== "") erros.push("Verificação de segurança falhou (bot detectado).");

  if (erros.length > 0) {
    e.preventDefault();
    alert("Corrija os seguintes campos antes de enviar:\n\n- " + erros.join("\n- "));
  }
});

