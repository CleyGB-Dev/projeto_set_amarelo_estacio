document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form');

  form.addEventListener('submit', function validarFormulario(e) {
    e.preventDefault(); // Interrompe o envio automático

    let erros = [];

    const nome = document.getElementById('name').value.trim();
    const nascimento = document.getElementById('birthdate').value.trim();
    const email = document.getElementById('email').value.trim();
    const bairro = document.getElementById('bairro').value.trim();
    const aluno = document.getElementById('alunos_estacio').value;
    const comorbidade = document.getElementById('comorbidade').value;
    const genero = document.querySelector('input[name="gender"]:checked');

    if (!nome) erros.push('Nome é obrigatório.');
    if (!nascimento) erros.push('Data de nascimento é obrigatória.');
    if (!email) erros.push('E-mail é obrigatório.');
    if (!bairro) erros.push('Bairro é obrigatório.');
    if (!aluno) erros.push('Selecione se é aluno.');
    if (!comorbidade) erros.push('Selecione uma comorbidade.');
    if (!genero) erros.push('Selecione um gênero.');

    if (erros.length > 0) {
      alert(erros.join('\n'));
    } else {
      // Remove o próprio listener para evitar chamar duas vezes
      form.removeEventListener('submit', validarFormulario);
      form.submit(); // Envia para o processa_formulario.php
    }
  });
});
