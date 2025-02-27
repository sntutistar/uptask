const mobileMenu = document.querySelector('#mobile-menu');
const sidebar = document.querySelector('.sidebar');

if(mobileMenu){
    mobileMenu.addEventListener('click', function() {
        sidebar.classList.add('mostrar');
    });
}

const cerrarMenu = document.querySelector('#cerrar-menu');
if(cerrarMenu){
    cerrarMenu.addEventListener('click',function(){
        sidebar.classList.add('ocultar');
        setTimeout(() => {
            sidebar.classList.remove('mostrar');
            sidebar.classList.remove('ocultar');
        }, 1000);
    })
}

const hpantalla = document.body.clientWidth;

window.addEventListener('resize', function(){
    const hpantalla = document.body.clientWidth;
    if(hpantalla >= 768){
        sidebar.classList.remove('mostrar');
    }
})