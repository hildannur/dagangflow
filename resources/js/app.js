import './bootstrap';

// Fungsi untuk memformat input sebagai mata uang Rupiah
document.addEventListener('DOMContentLoaded', function () {
    const rupiahInputs = document.querySelectorAll('.rupiah-input');

    function onlyNumber(value) {
        return value.replace(/\D/g, '');
    }

    function formatRibuan(value) {
        const number = onlyNumber(value);

        if (!number) {
            return '';
        }

        return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    rupiahInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            input.value = formatRibuan(input.value);
        });

        input.value = formatRibuan(input.value);
    });

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            form.querySelectorAll('.rupiah-input').forEach(function (input) {
                input.value = onlyNumber(input.value);
            });
        });
    });
});