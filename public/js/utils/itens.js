document.addEventListener('DOMContentLoaded', function () {
    const collectionHolder = document.querySelector('#charge-items');
    const addItemLink = document.querySelector('.add-item-link');

    addItemLink.addEventListener('click', function (e) {
        e.preventDefault();
        addItemForm(collectionHolder);
    });

    collectionHolder.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('li').remove();
        }
    });

    function addItemForm(collectionHolder) {
        const prototype = collectionHolder.dataset.prototype;
        let index = collectionHolder.dataset.index;

        const newForm = prototype.replace(/__name__/g, index);
        collectionHolder.dataset.index = parseInt(index) + 1;

        const li = document.createElement('li');
        li.innerHTML = newForm;

        const removeBtn = li.querySelector('button');
        if (removeBtn) {
            removeBtn.classList.add(
                'remove-item',
                'mt-2',
                'text-white',
                'bg-red-700',
                'hover:bg-red-500',
                'transition-colors',
                'duration-200',
                'px-4',
                'py-2',
                'rounded'
            );
            removeBtn.removeAttribute('disabled'); // Garante que não venha desativado
        }
        collectionHolder.appendChild(li);
    }

    const paymentTypeRadios = document.querySelectorAll('.payment-type-radio');
    const amountInput = document.querySelector('[data-payment-type-target="amountInput"]');

    paymentTypeRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'installment') {
                amountInput.min = 20;
                if (parseFloat(amountInput.value) < 20) {
                    amountInput.value = 20.00;
                }
            } else {
                amountInput.min = 0.01;
            }
        });
    });
});
