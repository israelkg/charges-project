const toggle = document.getElementById('toggle-theme');

let isHandlingClick = false;

toggle.addEventListener('click', (e) => {
  // Gambiarra: ignora se já estiver tratando um clique
  if (isHandlingClick) return;
  isHandlingClick = true;

  console.log("btn clicado");

  document.documentElement.classList.toggle('dark');

  if (document.documentElement.classList.contains('dark')) {
    localStorage.setItem('theme', 'dark');
  } else {
    localStorage.setItem('theme', 'light');
  }

  // Depois de 200ms, permite novo clique
  setTimeout(() => {
    isHandlingClick = false;
  }, 200);
});

window.addEventListener('DOMContentLoaded', () => {
  if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
});
