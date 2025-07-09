setTimeout(() => {
    document.querySelectorAll('.flash-message').forEach(el => {
        el.classList.add('opacity-0');
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
