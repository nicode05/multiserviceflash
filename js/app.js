document.addEventListener('DOMContentLoaded', function() {

    // --- 1. SELECCIÓN DE ELEMENTOS GLOBALES ---
    // Seleccionamos todos los elementos del DOM que vamos a necesitar.
    // Es importante hacerlo una sola vez al inicio.
    const hamburguesa = document.querySelector('.hamburguesa');
    const navegacion = document.querySelector('.navegacion');
    const enlacesNavegacion = document.querySelectorAll('.navegacion a');
    const fechaElement = document.querySelector('.fecha');
    const servicioLink = document.getElementById("servicio");
    const submenuElement = document.getElementById("submenu");
    // const menuServicios = document.querySelector(".menu-servicios"); // Descomenta si necesitas esta variable para algo más

    // --- 2. FUNCIONES DE INICIALIZACIÓN GENERALES ---
    // Estas funciones se ejecutarán una vez que toda la página (DOM) esté cargada.
    inicializarMenuHamburguesa();
    manejarScrollSuaveEnlaces();
    fechaActual();
    manejarSubmenuServicios();
    inicializarSliders(); // Asegúrate de que Swiper.js esté bien enlazado en tu HTML antes de usar esto.

    // --- 3. DEFINICIÓN DE FUNCIONES ESPECÍFICAS ---

    // Función para manejar la apertura y cierre del menú de hamburguesa
    function inicializarMenuHamburguesa() {
        if (hamburguesa && navegacion) { // Verificamos que los elementos existan en el HTML
            hamburguesa.addEventListener('click', () => {
                navegacion.classList.toggle('ocultar'); // Agrega/quita la clase 'ocultar'
                // Si el submenú está abierto, lo cerramos al abrir/cerrar el menú principal
                if (submenuElement && submenuElement.classList.contains('active')) {
                    submenuElement.classList.remove('active');
                }
            });
        }
    }

    // Función para manejar el scroll suave cuando se hace clic en un enlace del menú principal
    function manejarScrollSuaveEnlaces() {
        enlacesNavegacion.forEach(link => { // Iteramos sobre cada enlace en la navegación
            link.addEventListener('click', function(event) {
                const href = this.getAttribute('href'); // Obtenemos el valor del atributo href del enlace

                // Solo si el href es un ancla (ej. #seccion1, #contacto)
                if (href && href.startsWith('#')) {
                    event.preventDefault(); // Evitamos el comportamiento por defecto (salto instantáneo)

                    const targetElement = document.querySelector(href); // Buscamos el elemento destino por su ID

                    if (targetElement) { // Si el elemento destino existe
                        targetElement.scrollIntoView({ // Hacemos scroll suave hacia él
                            behavior: 'smooth'
                        });
                        // Si estamos en un dispositivo móvil, cerramos el menú después de hacer clic en un enlace
                        if (window.innerWidth <= 767 && navegacion && !navegacion.classList.contains('ocultar')) {
                            navegacion.classList.add('ocultar');
                        }
                    } else {
                        console.warn(`Elemento con ID "${href}" no encontrado para scroll suave.`);
                    }
                }
                // Si el href NO comienza con '#', el navegador seguirá el enlace normalmente (ej. "contacto.html")
            });
        });
    }

    // Función para actualizar el año actual en el pie de página o donde tengas el elemento con clase 'fecha'
    function fechaActual() {
        if (fechaElement) { // Verificamos que el elemento exista
            let fechaHoy = new Date().getFullYear();
            fechaElement.textContent = fechaHoy;
        }
    }

    // Función para manejar la lógica del submenú de "Servicios"
    function manejarSubmenuServicios() {
        if (servicioLink && submenuElement) { // Verificamos que ambos elementos existan
            // Cuando se hace clic en el enlace principal de "Servicios"
            servicioLink.addEventListener("click", function (event) {
                event.stopPropagation(); // Importante: evita que el clic se propague al 'document' y cierre el submenú
                submenuElement.classList.toggle("active"); // Agrega/quita la clase 'active' para mostrar/ocultar
            });

            // Cuando se hace clic en cualquier parte del documento (excepto el submenú o el enlace de servicios)
            document.addEventListener("click", function (event) {
                if (!submenuElement.contains(event.target) && event.target !== servicioLink) {
                    submenuElement.classList.remove("active"); // Cierra el submenú
                }
            });

            // Cuando se hace clic en un elemento dentro del submenú
            submenuElement.querySelectorAll("li").forEach(item => {
                item.addEventListener("click", function () {
                    submenuElement.classList.remove("active"); // Cierra el submenú
                    // Opcional: Si el menú principal está visible en móvil, también lo cerramos
                    if (window.innerWidth <= 767 && navegacion && !navegacion.classList.contains('ocultar')) {
                        navegacion.classList.add('ocultar');
                    }
                });
            });
        }
        /*
        // La siguiente lógica es opcional si el ".menu-servicios" solo es un contenedor
        // del enlace "Servicios" y su submenú. Si la clase "activo" se usa para otra
        // cosa en tu CSS para ".menu-servicios", entonces podrías necesitarla.
        const menuServicios = document.querySelector(".menu-servicios");
        if (menuServicios) {
            const menuServiciosLink = document.querySelector(".menu-servicios a");
            if (menuServiciosLink) {
                menuServiciosLink.addEventListener("click", function(e) {
                    e.preventDefault(); // Evita que el enlace haga un salto de página
                    menuServicios.classList.toggle("activo"); // Alterna una clase "activo"
                });
            }
        }
        */
    }

    // Función para inicializar todos los sliders usando Swiper.js
    function inicializarSliders() {
        // Slider de los carros (sección #slider-section)
        const swiperCarrosContainer = document.querySelector('.slider-container');
        if (swiperCarrosContainer) {
            const swiperCarros = new Swiper(swiperCarrosContainer, {
                loop: true,
                slidesPerView: 'auto',
                spaceBetween: 5,
                autoplay: {
                    delay: 1000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                speed: 300,
                grabCursor: true,
                freeMode: false,
                rewind: false,
                loopAdditionalSlides: 10,
            });
        }

        // Slider de los testimonios (sección .slider-wrapper)
        const swiperTestimoniosContainer = document.querySelector('.slider-wrapper');
        if (swiperTestimoniosContainer) {
            const swiperTestimonios = new Swiper(swiperTestimoniosContainer, {
                loop: true,
                spaceBetween: 30,
                centeredSlides: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                speed: 800,
                rewind: false,
                grabCursor: true,
                navigation: { // Configuración de flechas de navegación
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: { // Configuración responsive para diferentes tamaños de pantalla
                    0: { // Para pantallas de 0px en adelante (móviles)
                        slidesPerView: 1,
                        navigation: {
                            enabled: true, // Habilita las flechas en móvil
                        }
                    },
                    768: { // Para pantallas de 768px en adelante (tablets)
                        slidesPerView: 2,
                        navigation: {
                            enabled: false, // Puedes cambiar a true si quieres flechas aquí
                        }
                    },
                    1024: { // Para pantallas de 1024px en adelante (escritorio)
                        slidesPerView: 3,
                        navigation: {
                            enabled: false, // Puedes cambiar a true si quieres flechas aquí
                        }
                    }
                }
            });
        }
    }
});