document.querySelectorAll('.btn-envio').forEach(button => {
    button.addEventListener('click', () => {
        button.classList.toggle('bg-blue-600');
        button.classList.toggle('text-white');
  
        const method = button.dataset.method;
        const checkbox = document.querySelector(`input[type="checkbox"][value="${method}"]`);
        if (checkbox) {
            checkbox.checked = !checkbox.checked; // sincroniza o clique do botão com o input
        }
    });
});
  
  