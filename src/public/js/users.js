import { openModal } from './modal.js';

const addUserButton = document.getElementById('add-user-button');

addUserButton.onclick = createHandler;

function mapUsers(users) {
    const usersWrapper = document.querySelector('.users');
    users.forEach(user => {
        const userWrapper = document.createElement('div');
        userWrapper.classList.add('user');
        userWrapper.setAttribute('id', `id-${user.id}`);
        const userName = document.createElement('h1');
        userName.classList.add('user__name');
        userName.textContent = user.name;
        const userEmail = document.createElement('h2');
        userEmail.classList.add('user__email');
        userEmail.textContent = user.email;
        const userCreatedAt = document.createElement('h2');
        userCreatedAt.classList.add('user__created_at');
        userCreatedAt.textContent = `Created at: ${user.reg_date}`;

        const actions = document.createElement('div');
        actions.classList.add('user__actions');

        const userDelete = document.createElement('h4');
        userDelete.textContent = 'Remove User';
        userDelete.classList.add('delete');

        const userEdit = document.createElement('h4');
        userEdit.textContent = 'Edit User';
        userEdit.classList.add('edit');

        actions.appendChild(userEdit);
        actions.appendChild(userDelete);

        userWrapper.appendChild(userName);
        userWrapper.appendChild(userEmail);
        userWrapper.appendChild(userCreatedAt);
        userWrapper.appendChild(actions);
        usersWrapper.appendChild(userWrapper);
        deleteHandler(user.id);
        editHandler(user);
    });
}

function createHandler() {
    const form = document.getElementById('user-form');
    const name = document.querySelector('#user-form [name="name"]');
    const email = document.querySelector('#user-form [name="email"]');
    const password = document.querySelector('#user-form [name="password"]');
    const confirmButton = document.querySelector('#user-form [type="submit"]');

    name.value = '';
    email.value = '';
    password.value = '';
    form.setAttribute('action', `api.php/users/add`);

    confirmButton.innerHTML = 'Create User';
    openModal();

    confirmButton.onclick = (e) => {
        e.preventDefault();
        const payload = { name: name.value, email: email.value, password: password.value }
        sendReq(payload, form.getAttribute('action'));
    };
}

function editHandler(user) {
    const userWrapper = document.querySelector(`#id-${user.id} h4.edit`);

    userWrapper.onclick = () => {
        const title = document.querySelector('.modal h1.title');
        const form = document.getElementById('user-form');
        const name = document.querySelector('#user-form [name="name"]');
        const email = document.querySelector('#user-form [name="email"]');
        const password = document.querySelector('#user-form [name="password"]');
        const confirmButton = document.querySelector('#user-form [type="submit"]');
        
        title.innerHTML = `Edit ${user.name}`;
        name.value = user.name;
        email.value = user.email;
        password.value = user.password;
        form.setAttribute('action', `api.php/users/edit`);

        confirmButton.innerHTML = 'Edit User';
        confirmButton.onclick = (e) => {
            e.preventDefault();
            const payload = { id: user.id, name: name.value, email: email.value, password: password.value }
            sendReq(payload, form.getAttribute('action'));
        };
        openModal();
    };
}

function deleteHandler(id) {
    const userDelete = document.querySelector(`#id-${id} h4.delete`);
    const user = document.getElementById(id);
    userDelete.onclick = (e) => {
        if (e.target.classList.contains('delete')) {
            fetch(`api.php/users/delete/${id}`, { method: 'DELETE' })
                .then(response => response.status)
                .then(status => {
                    if (status === 200) user.remove();
                });
        }
    };
}

async function sendReq(user, url) {
    const formData = new FormData();
    for (const key in user) {
        formData.append(key, user[key]);
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

fetch('api.php/users/list')
    .then(response => response.json())
    .then(data => mapUsers(data));


