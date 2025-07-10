const toggle = document.getElementById('toggle-theme');

toggle.addEventListener('click', () => {
  document.documentElement.classList.toggle('dark');

  if(document.documentElement.classList.contains('dark')){
    localStorage.setItem('theme', 'dark');
  } else {
    localStorage.setItem('theme', 'light');
  }
});

window.addEventListener('DOMContentLoaded', () => {
  if(localStorage.getItem('theme') === 'dark'){
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
});
