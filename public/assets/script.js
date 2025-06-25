let moreThan30 = false;

setTimeout(() => {
    moreThan30 = true;
}, 30000);

document.getElementById('leadForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    formData.set('more_than_30', moreThan30 ? '1' : '0');

    const phone = formData.get('phone');
    if (!phone.startsWith('+7')) {
        alert('Телефон должен начинаться с +7');
        return;
    }

    try {
        const response = await fetch('/', {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        console.log('Raw response text:', text);

        let result;
        try {
            result = JSON.parse(text);
        } catch (parseErr) {
            alert('Ошибка парсинга JSON: ' + parseErr.message);
            console.error('Invalid JSON:', text);
            return;
        }

        if (response.ok) {
            alert(result.message || 'Успешно!');
        } else {
            alert(result.message || 'Ошибка на сервере');
            console.error('Ошибка ответа:', result);
        }
    } catch (err) {
        alert('Произошла ошибка при отправке формы.');
        console.error(err);
    }
});



