document.addEventListener('DOMContentLoaded', function () {
    const valueInput = document.querySelector('#charge_form_amount');
    const paymentRadiosInputs = document.querySelectorAll('input[name="charge_form[paymentType]"]');
    const installmentsSelect = document.querySelector('#charge_form_installmentsCount');
    const wrapper = document.querySelector('.installments-wrapper');
    

    function updateInstallmentsOptions() {
        const selectedPayment = document.querySelector('input[name="charge_form[paymentType]"]:checked');
        const value = parseFloat(valueInput.value.replace(',', '.'));
        
        if (!selectedPayment || selectedPayment.value !== 'installment' || isNaN(value)) {
            wrapper.classList.add('hidden');
            return;
        }

        wrapper.classList.remove('hidden');
        installmentsSelect.innerHTML = '';

        const maxInstallments = Math.min(Math.floor(value / 2), 10);

        for (let i = 2; i <= maxInstallments; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `${i}x de R$ ${(value / i).toFixed(2).replace('.', ',')}`;
            installmentsSelect.appendChild(option);
        }
    }

    
    valueInput.addEventListener('input', updateInstallmentsOptions); //quando muda o valor;
    paymentRadiosInputs.forEach(r => r.addEventListener('change', updateInstallmentsOptions));  //quando muda o tipo
});
