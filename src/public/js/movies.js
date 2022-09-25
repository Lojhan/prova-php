import { openModal } from './modal.js';

const addmovieButton = document.getElementById('add-movie-button');

addmovieButton.onclick = createHandler;

function mapMovies(movies) {
    const moviesWrapper = document.querySelector('.movies');
    movies.forEach(movie => {
        const movieWrapper = document.createElement('div');
        movieWrapper.classList.add('movie');
        movieWrapper.setAttribute('id', `id-${movie.id}`);
        const movieName = document.createElement('h1');
        movieName.classList.add('movie__name');
        movieName.textContent = movie.name;

        const movieDescription = document.createElement('p');
        movieDescription.classList.add('movie__description');
        movieDescription.textContent = movie.description;

        const actions = document.createElement('div');
        actions.classList.add('movie__actions');

        const movieEdit = document.createElement('h4');
        movieEdit.classList.add('edit');
        movieEdit.setAttribute('id', `id-${movie.id}`);
        movieEdit.textContent = 'Edit Movie';

        const movieDelete = document.createElement('h4');
        movieDelete.classList.add('delete');
        movieDelete.setAttribute('id', `id-${movie.id}`);
        movieDelete.textContent = 'Remove movie';

        actions.appendChild(movieEdit);
        actions.appendChild(movieDelete);


        const movieCreatedAt = document.createElement('h2');
        movieCreatedAt.classList.add('movie__created_at');
        movieCreatedAt.textContent = `Created at: ${movie.reg_date}`;
      
        movieWrapper.appendChild(movieName);
        movieWrapper.appendChild(movieDescription);
        movieWrapper.appendChild(movieCreatedAt);
        movieWrapper.appendChild(actions);
        moviesWrapper.appendChild(movieWrapper);
        deleteHandler(movie.id);
        editHandler(movie);
    });
}

function createHandler() {
    const form = document.getElementById('movie-form');
    const name = document.querySelector('#movie-form [name="name"]');
    const description = document.querySelector('#movie-form [name="description"]');
    const confirmButton = document.querySelector('#movie-form [type="submit"]');

    name.value = '';
    description.value = '';
    form.setAttribute('action', `api.php/movies/add`);

    confirmButton.innerHTML = 'Create movie';
    openModal();

    confirmButton.onclick = (e) => {
        e.preventDefault();
        const payload = { name: name.value, description: description.value }
        sendReq(payload, form.getAttribute('action'));
    };
}

function editHandler(movie) {
    const movieWrapper = document.querySelector(`#id-${movie.id} h4.edit`);

    movieWrapper.onclick = () => {
        const title = document.querySelector('.modal h1.title');
        const form = document.getElementById('movie-form');
        const name = document.querySelector('#movie-form [name="name"]');
        const description = document.querySelector('#movie-form [name="description"]');

        const confirmButton = document.querySelector('#movie-form [type="submit"]');
        
        title.innerHTML = `Edit ${movie.name}`;
        name.value = movie.name;
        description.value = movie.description;

        form.setAttribute('action', `api.php/movies/edit`);

        confirmButton.innerHTML = 'Edit movie';
        confirmButton.onclick = (e) => {
            e.preventDefault();
            const payload = { id: movie.id, name: name.value, description: description.value }
            sendReq(payload, form.getAttribute('action'));
        };
        openModal();
    };
}

function deleteHandler(id) {
    const movieDelete = document.querySelector(`#id-${id} h4.delete`);
    const movie = document.getElementById(`id-${id}`);
    movieDelete.onclick = (e) => {
        if (e.target.classList.contains('delete')) {
            fetch(`api.php/movies/delete/${id}`, { method: 'DELETE' })
                .then(response => response.status)
                .then(status => {
                    if (status === 200) movie.remove();
                });
        }
    };
}

async function sendReq(movie, url) {
    const formData = new FormData();
    for (const key in movie) {
        formData.append(key, movie[key]);
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

fetch('api.php/movies/list')
    .then(response => response.json())
    .then(data => mapMovies(data));


