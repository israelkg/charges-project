
// MASK VALOR
/*
document.addEventListener("DOMContentLoaded", function() {
    var element = document.getElementById('charge_form_amount');
    if (element) {
        IMask(element, {
            mask: Number,
            scale: 2,
            signed: false,
            thousandsSeparator: '.',
            padFractionalZeros: true,
            normalizeZeros: true,
            radix: ',',
            mapToRadix: ['.']
        });
    }
});
*/

// MASK NUMBER PHONE
document.addEventListener("DOMContentLoaded", function () {
    const phoneInputs = document.querySelectorAll('#charge_form_numberPhone');

    phoneInputs.forEach(input => {
      IMask(input, {
        mask: '(00) 00000-0000'
      });
    });
});

// MASK CPF/CNPJ
document.addEventListener('DOMContentLoaded', function () {
    const cpfCnpjInput = document.getElementById('charge_form_cpfCnpj');
    
    if (!cpfCnpjInput) {
        console.warn('Campo CPF/CNPJ não encontrado!');
        return;
    }

    new Cleave(cpfCnpjInput, {
        delimiters: ['.', '.', '-', '/', '-'],
            blocks: [3, 3, 3, 2, 4, 4, 2],
            numericOnly: true,
            onValueChanged: function (e) {
                const v = e.target.rawValue;
                if (v.length <= 11) {
                    cpfCnpjInput.cleave.setRawValue(v);
                    cpfCnpjInput.cleave.properties.blocks = [3, 3, 3, 2]; // CPF
                    cpfCnpjInput.cleave.properties.delimiters = ['.', '.', '-'];
                } else {
                    cpfCnpjInput.cleave.setRawValue(v);
                    cpfCnpjInput.cleave.properties.blocks = [2, 3, 3, 4, 2]; // CNPJ
                    cpfCnpjInput.cleave.properties.delimiters = ['.', '.', '/', '-'];
                }
            }
    });
});