const container = document.querySelector('.container');
const forgotLink = document.getElementById('forgot-link');
const loginBtn = document.querySelector('.login-btn');

forgotLink.addEventListener('click', (e) => {
    e.preventDefault();
    container.classList.add('active');
});
loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});