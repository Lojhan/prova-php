const modalOverlay = document.querySelector('.modal-overlay');
const modal = document.querySelector('.modal');

const cancel = document.getElementById('cancel');


function openModal() {
    modalOverlay.style.display = 'block';
    modal.style.display = 'block';
}

function closeModal() {
    modalOverlay.style.display = 'none';
    modal.style.display = 'none';
}

cancel.addEventListener('click', closeModal);


export { openModal, closeModal };