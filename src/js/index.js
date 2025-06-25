const botoes = document.querySelectorAll('.botao');
const cont = document.querySelectorAll('.cont');

botoes.forEach((botao, indice) => {
  botao.addEventListener('click', () => {
    const botaoSelecionado = document.querySelector('.botao.selecionado');
    if (botaoSelecionado) botaoSelecionado.classList.remove('selecionado');

    botao.classList.add('selecionado');

    const contSelecionado = document.querySelector('.cont.selecionado');
    if (contSelecionado) contSelecionado.classList.remove('selecionado');

    cont[indice].classList.add('selecionado');

    window.scrollTo({ top: 0, behavior: 'smooth' }); // Suaviza rolagem
  });
});
