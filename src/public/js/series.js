import { openModal } from './modal.js';

const addserieButton = document.getElementById('add-serie-button');

addserieButton.onclick = createHandler;

function mapseries(series) {
    const seriesWrapper = document.querySelector('.series');
    series.forEach(serie => {
        const serieWrapper = document.createElement('div');
        serieWrapper.classList.add('serie');
        serieWrapper.setAttribute('id', `id-${serie.id}`);
        const serieName = document.createElement('h1');
        serieName.classList.add('serie__name');
        serieName.textContent = serie.name;

        const serieDescription = document.createElement('p');
        serieDescription.classList.add('serie__description');
        serieDescription.textContent = serie.description;

        const actions = document.createElement('div');
        actions.classList.add('serie__actions');


        const serieDelete = document.createElement('h4');
        serieDelete.classList.add('delete');
        serieDelete.textContent = 'Remove Serie';

        const serieEdit = document.createElement('h4');
        serieEdit.textContent = 'Edit Serie';
        serieEdit.classList.add('edit');

        actions.appendChild(serieEdit);
        actions.appendChild(serieDelete);

        const serieCreatedAt = document.createElement('h2');
        serieCreatedAt.classList.add('serie__created_at');
        serieCreatedAt.textContent = `Created at: ${serie.reg_date}`;

        serieDelete.setAttribute('id', `id-${serie.id}`);
        serieDelete.textContent = 'Remove serie';
        serieWrapper.appendChild(serieName);
        serieWrapper.appendChild(serieDescription);
        serieWrapper.appendChild(serieCreatedAt);
        serieWrapper.appendChild(actions);
        seriesWrapper.appendChild(serieWrapper);
        deleteHandler(serie.id);
        editHandler(serie);
    });
}

function createHandler() {

    const form = document.getElementById('serie-form');
    const name = document.querySelector('#serie-form [name="name"]');
    const description = document.querySelector('#serie-form [name="description"]');
    const confirmButton = document.querySelector('#serie-form [type="submit"]');

    name.value = '';
    description.value = '';
    form.setAttribute('action', `api.php/series/add`);

    confirmButton.innerHTML = 'Create serie';
    openModal();

    confirmButton.onclick = (e) => {
        e.preventDefault();
        const payload = { name: name.value, description: description.value }
        sendReq(payload, form.getAttribute('action'));
    }
}

function editHandler(serie) {
    const serieWrapper = document.querySelector(`#id-${serie.id} h4.edit`);

    serieWrapper.onclick = () => {
        const title = document.querySelector('.modal h1.title');
        const form = document.getElementById('serie-form');
        const name = document.querySelector('#serie-form [name="name"]');
        const description = document.querySelector('#serie-form [name="description"]');

        const confirmButton = document.querySelector('#serie-form [type="submit"]');
        
        title.innerHTML = `Edit ${serie.name}`;
        name.value = serie.name;
        description.value = serie.description;

        form.setAttribute('action', `api.php/series/edit`);

        confirmButton.innerHTML = 'Edit serie';
        confirmButton.onclick = (e) => {
            e.preventDefault();
            const payload = { id: serie.id, name: name.value, description: description.value }
            sendReq(payload, form.getAttribute('action'));
        }
        
        openModal();
    };
}

function deleteHandler(id) {
    const serieDelete = document.querySelector(`#id-${id} h4.delete`);
    const serie = document.getElementById(`id-${id}`);
    serieDelete.onclick = (e) => {
        if (e.target.classList.contains('delete')) {
            fetch(`api.php/series/delete/${id}`, { method: 'DELETE' })
                .then(response => response.status)
                .then(status => {
                    if (status === 200) serie.remove();
                });
        }
    };
}

async function sendReq(serie, url) {
    const formData = new FormData();
    for (const key in serie) {
        formData.append(key, serie[key]);
    }

    const requestOptions = {
    method: 'POST',
    body: formData,
    redirect: 'follow'
    };

    const data = await fetch(url, requestOptions)
    .then(reloadOnSuccess)
    .then(alertOnError)
    .catch(() => alert('Something went wrong'));

    return data;
}

function reloadOnSuccess(res) {
    if (res.status === 200) {
        return window.location.reload();
    }
    return res;
}

async function alertOnError(res) {
    if (res.status !== 400) return alert ('Something went wrong');
    const e = await res.json();
    const { error } = e;

    const errorModal = document.querySelector('.error-alert');
    const errorModalContent = document.querySelector('.error-alert .error-alert-content');
    const errorModalFieldsList = document.querySelector('.error-alert ul.fields');

    const closeButton = document.querySelector('#close-error-alert');
    function closeModal() {
        errorModal.style.display = 'none';
        errorModalContent.style.display = 'none';
        const body = document.getElementById('error');
        body.remove();
    }

    function openModal() {
        errorModal.style.display = 'block';
        errorModalContent.style.display = 'block';
    }

    if (error === 'Validation failed') {

        const { fields } = e;

        fields.forEach(f => {
            const fieldEl = document.createElement('div');
            fieldEl.setAttribute('id', `error`);

            const { field, messages } = f;

            fieldEl.innerHTML = `<h3>${field}</h3>`;
            errorModalFieldsList.appendChild(fieldEl);

            messages.forEach(m => {
                const messageEl = document.createElement('li');
                messageEl.textContent = m;
                fieldEl.appendChild(messageEl);
            });
        });
    }

    closeButton.onclick = closeModal;
    
    openModal();
}

fetch('api.php/series/list')
    .then(response => response.json())
    .then(data => mapseries(data));


