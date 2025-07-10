
    document.addEventListener('DOMContentLoaded', () => {
        const nextBtn = document.getElementById('next-step');
        const backBtn = document.getElementById('back-step');
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');

        nextBtn.addEventListener('click', () => {
            const inputs = step1.querySelectorAll('input, textarea, select');
            let valid = true;
        
            inputs.forEach(input => {
                // Ignora campos ocultos ou desnecessários com display: none ou escondidos via classe 'hidden'
                const isHidden = input.offsetParent === null;
                if (!isHidden && !input.value.trim()) {
                    input.classList.add('border-red-500');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });
        
            if (!valid) {
                alert('Preencha todos os campos obrigatórios antes de continuar.');
                return;
            }
        
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
        });

        backBtn.addEventListener('click', () => {
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
        });
    });
