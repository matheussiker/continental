function typeWriter(elemento) {
  if (!elemento) return;
  const textoArray = elemento.innerHTML.split('');
  elemento.innerHTML = '';
  textoArray.forEach((letra, i) => {
    setTimeout(() => elemento.innerHTML += letra, 75 * i);
  });
}

const titulo = document.getElementById('sublogo');
typeWriter(titulo);

// --------- textos de recomendação ---------
const textosRecomendacao = [
  `Curitiba é uma cidade que combina natureza, arte e boa gastronomia, sendo um dos destinos urbanos mais charmosos do Brasil. O Jardim Botânico, com sua estufa de vidro inspirada em palácios londrinos, é um dos lugares mais visitados, mas não para por aí: o Parque Tanguá e o Parque Barigui são ótimos para relaxar e aproveitar áreas verdes. O Museu Oscar Niemeyer, famoso por sua arquitetura em forma de olho, reúne exposições de arte moderna e contemporânea. Já no centro histórico, o Largo da Ordem ganha vida aos domingos com a tradicional feira, onde é possível encontrar artesanato, comidas típicas e música de rua. Para conhecer a cidade de um jeito prático, o ônibus turístico faz um circuito completo passando pelos principais pontos, como a Ópera de Arame e a Torre Panorâmica. À noite, os bares e restaurantes no bairro Batel oferecem desde culinária internacional até pratos típicos paranaenses, como o barreado.`,
  
  `Florianópolis, a famosa Ilha da Magia, conquista visitantes com suas praias deslumbrantes e atmosfera descontraída. A ilha tem opções para todos os estilos: a Joaquina e a Praia Mole são ideais para surfistas e jovens em busca de agito, enquanto Jurerê Internacional oferece beach clubs sofisticados e muito glamour. Já quem prefere tranquilidade encontra refúgio no sul da ilha, em praias como Armação, Campeche e Lagoinha do Leste, esta última acessível apenas por trilha. A Lagoa da Conceição é um dos pontos mais animados, reunindo bares, restaurantes e esportes aquáticos como windsurf e stand-up paddle. Para quem gosta de cultura, o bairro Santo Antônio de Lisboa encanta com suas casinhas coloniais, ruas de pedra e frutos do mar fresquíssimos. Além das praias, Floripa é cercada de trilhas com vistas incríveis, como a que leva ao Morro da Lagoa, revelando a beleza natural da região. A noite também é agitada, com opções que vão de bares descontraídos a festas sofisticadas.`,

  `Salvador é um destino cheio de energia, história e cores, considerado uma das cidades mais vibrantes do Brasil. O Pelourinho é parada obrigatória, com seus casarões coloniais coloridos, igrejas barrocas como a de São Francisco e apresentações culturais que transformam as ruas em palco. Na orla, o Farol da Barra é um dos cartões-postais mais famosos e o melhor ponto para assistir ao pôr do sol. O Elevador Lacerda conecta a Cidade Alta à Cidade Baixa, oferecendo uma vista espetacular da Baía de Todos-os-Santos e do Mercado Modelo, ótimo para comprar artesanato e provar comidas típicas. As praias também são atrações à parte: Itapuã, imortalizada na canção de Vinicius de Moraes, é perfeita para relaxar, enquanto Stella Maris atrai famílias e surfistas. Além do mar, Salvador é puro sabor — provar um acarajé feito pelas baianas de rua, saborear moqueca e curtir a música ao vivo são experiências inesquecíveis. E para quem visita na época do Carnaval, a cidade se transforma em um dos maiores espetáculos culturais do mundo, com trios elétricos e muita festa.`
];

// --------- carrossel simples em JS ---------
const cards = Array.from(document.querySelectorAll('.carousel-track .card'));
let index = cards.findIndex(c => c.classList.contains('active'));
if (index === -1) index = 0;

const textContainer = document.getElementById('recomendacao-texto').querySelector('p');

// Atualiza o card e o texto
function showCard(i) {
  cards.forEach((c, idx) => {
    if (idx === i) {
      c.classList.add('active');
      c.classList.remove('inactive');
    } else {
      c.classList.remove('active');
      c.classList.add('inactive');
    }
  });

  // Atualiza o texto de recomendação
  if (textContainer) {
    textContainer.innerText = textosRecomendacao[i];
  }
}

// Inicializa
showCard(index);

// Troca automática a cada 5 segundos
let interval = setInterval(nextCard, 5000);

function nextCard() {
  index = (index + 1) % cards.length;
  showCard(index);
}

// Pausa ao passar o mouse no carrossel
const carousel = document.querySelector('.carousel-track');
carousel.addEventListener('mouseenter', () => clearInterval(interval));
carousel.addEventListener('mouseleave', () => interval = setInterval(nextCard, 5000));

// Botões manuais
const nextBtn = document.querySelector('.btn.next');
const prevBtn = document.querySelector('.btn.prev');

nextBtn?.addEventListener('click', () => {
  index = (index + 1) % cards.length;
  showCard(index);
});

prevBtn?.addEventListener('click', () => {
  index = (index - 1 + cards.length) % cards.length;
  showCard(index);
});
