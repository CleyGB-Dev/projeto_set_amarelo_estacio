// Adiciona/remover classe no header ao scroll
window.addEventListener('scroll', () => {
  const header = document.querySelector('header');
  if (window.scrollY > 10) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

// Função para remover um vídeo (se existir)
function fecharVideo() {
  const videoBox = document.getElementById('videoBox');
  if (videoBox) {
    videoBox.remove();
  }
}

// Menu do header - toggle show
const btnMenu = document.getElementById('btn-menu');
const menu = document.getElementById('outras_paginas');

btnMenu.addEventListener('click', () => {
  menu.classList.toggle('show');
});

// Carrossel
const carousel = document.querySelector('#carousel');
const images = carousel.querySelectorAll('.carousel-inner img');
const prevBtn = carousel.querySelector('.carousel-btn.prev');
const nextBtn = carousel.querySelector('.carousel-btn.next');
const carouselInner = carousel.querySelector('.carousel-inner');

// Modal
const modal = document.getElementById('modal');
const modalImg = document.getElementById('modal-img');
const modalClose = document.getElementById('modal-close');
const modalPrev = document.getElementById('modal-prev');
const modalNext = document.getElementById('modal-next');

let currentIndex = 0;

// Começa escondido
modal.style.display = 'none';

function updateActiveImage(index) {
  images.forEach((img, i) => {
    img.classList.toggle('active', i === index);
  });

  // Centraliza a imagem ativa no carrossel
  const activeImg = images[index];
  const containerRect = carouselInner.getBoundingClientRect();
  const imgRect = activeImg.getBoundingClientRect();

  const scrollLeft = carouselInner.scrollLeft;
  const offset = imgRect.left - containerRect.left;

  const scrollTo = scrollLeft + offset - (containerRect.width / 2) + (imgRect.width / 2);

  carouselInner.scrollTo({
    left: scrollTo,
    behavior: 'smooth'
  });
}

function openModal(index) {
  currentIndex = index;
  modal.style.display = 'flex';
  modalImg.src = images[index].src;
  modalImg.alt = images[index].alt;
  updateActiveImage(index);
}

function closeModal() {
  modal.style.display = 'none';
}

function showPrevImage() {
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  modalImg.src = images[currentIndex].src;
  modalImg.alt = images[currentIndex].alt;
  updateActiveImage(currentIndex);
}

function showNextImage() {
  currentIndex = (currentIndex + 1) % images.length;
  modalImg.src = images[currentIndex].src;
  modalImg.alt = images[currentIndex].alt;
  updateActiveImage(currentIndex);
}

// Botões do carrossel
prevBtn.addEventListener('click', () => {
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  updateActiveImage(currentIndex);
});

nextBtn.addEventListener('click', () => {
  currentIndex = (currentIndex + 1) % images.length;
  updateActiveImage(currentIndex);
});

// Clique nas imagens abre modal
images.forEach((img, i) => {
  img.addEventListener('click', () => {
    openModal(i);
  });
});

// Botões modal
modalClose.addEventListener('click', closeModal);
modalPrev.addEventListener('click', showPrevImage);
modalNext.addEventListener('click', showNextImage);

// Fecha modal ao clicar fora da imagem (fundo escuro)
modal.addEventListener('click', (e) => {
  if (e.target === modal) {
    closeModal();
  }
});

// Navegação no modal via teclado
document.addEventListener('keydown', (e) => {
  if (modal.style.display === 'flex') {
    if (e.key === 'ArrowLeft') {
      showPrevImage();
    } else if (e.key === 'ArrowRight') {
      showNextImage();
    } else if (e.key === 'Escape') {
      closeModal();
    }
  }
});

// Autoplay carrossel (opcional)
setInterval(() => {
  currentIndex = (currentIndex + 1) % images.length;
  updateActiveImage(currentIndex);
}, 4000);

// Inicializa o destaque da primeira imagem no carrossel
updateActiveImage(currentIndex);
