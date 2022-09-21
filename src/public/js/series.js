import { openModal } from './modal.js';

const addserieButton = document.getElementById('add-serie-button');

addserieButton.onclick = createHandler;

function mapseries(series) {
    const seriesWrapper = document.querySelector('.series');
    series.forEach(serie => {
        const serieWrapper = document.createElement('div');
        serieWrapper.classList.add('serie');
        serieWrapper.setAttribute('id', serie.id);
        const serieName = document.createElement('h1');
        serieName.classList.add('serie__name');
        serieName.textContent = serie.name;

        const serieDescription = document.createElement('p');
        serieDescription.classList.add('serie__description');
        serieDescription.textContent = serie.description;

        const serieCreatedAt = document.createElement('h2');
        serieCreatedAt.classList.add('serie__created_at');
        serieCreatedAt.textContent = `Created at: ${serie.reg_date}`;
        const serieDelete = document.createElement('h4');
        serieDelete.classList.add('delete');
        serieDelete.setAttribute('id', `id-${serie.id}`);
        serieDelete.textContent = 'Remove serie';
        serieWrapper.appendChild(serieName);
        serieWrapper.appendChild(serieDescription);
        serieWrapper.appendChild(serieCreatedAt);
        serieWrapper.appendChild(serieDelete);
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
    const serieWrapper = document.getElementById(serie.id);

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
    const serieDelete = document.querySelector(`#id-${id}.delete`);
    const serie = document.getElementById(id);
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
    .catch(error => console.log('error', error));

    return data;
}

function reloadOnSuccess(res) {
    if (res.status === 200) {
        window.location.reload();
    }
}

fetch('api.php/series/list')
    .then(response => response.json())
    .then(data => mapseries(data));


