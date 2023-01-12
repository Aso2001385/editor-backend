const burgerBtn = document.getElementsByClassName('burger-btn').item(0);

const btnAction = () => {
    burgerBtn.classList.remove('restore');
    burgerBtn.classList.add('close');
    document
        .getElementsByClassName('nav-wrapper')
        .item(0)
        .classList.toggle('fade');
    document.querySelector('body').classList.toggle('noscroll');
    burgerBtn.addEventListener('click', btnClassRemover);
};

const btnClassRemover = () => {
    burgerBtn.classList.remove('close');
    burgerBtn.classList.add('restore');

    burgerBtn.removeEventListener('click', btnClassRemover);
    burgerBtn.addEventListener('click', btnAction);
};

burgerBtn.addEventListener('click', btnAction);

document.querySelector('.menu-btn').addEventListener('click', function () {
    document.querySelector('.menu').classList.toggle('is-active');
});
