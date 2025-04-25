const hamburguesa = document.querySelector('.hamburguesa');
const navegacion = document.querySelector('.navegacion');
const enlaces = document.querySelectorAll('.navegacion a');
const fecha = document.querySelector('.fecha');

document.addEventListener('DOMContentLoaded', () => {
    mostrarMenu();
    cerrarMenu();
    fechaActual();
    manejarSubmenu();
});

function mostrarMenu() {
    hamburguesa.addEventListener('click', () => {
        navegacion.classList.toggle('ocultar');
    });
}

function cerrarMenu() {
    enlaces.forEach(enlace => {
        enlace.addEventListener('click', (e) => {
            e.preventDefault();
            const seccion = document.querySelector(e.target.attributes.href.value);
            cambioSeccion(seccion);
            if (e.target.tagName === 'A') {
                navegacion.classList.add('ocultar');
            }
        });
    });
}

function cambioSeccion(seccion) {
    seccion.scrollIntoView({
        behavior: 'smooth'
    });
}

function fechaActual() {
    let fechaHoy = new Date().getFullYear();
    if (fecha) {
        fecha.textContent = fechaHoy;
    }
}

// Función para manejar el submenú de "Servicios"
function manejarSubmenu() {
    const servicio = document.getElementById("servicio");
    const submenu = document.getElementById("submenu");

    if (servicio && submenu) {
        servicio.addEventListener("click", function (event) {
            event.stopPropagation();
            submenu.classList.toggle("active");
        });

        document.addEventListener("click", function (event) {
            if (!submenu.contains(event.target) && event.target !== servicio) {
                submenu.classList.remove("active");
            }
        });

        submenu.querySelectorAll("li").forEach(item => {
            item.addEventListener("click", function () {
                submenu.classList.remove("active");
            });
        });
    }
}
document.querySelector(".menu-servicios").addEventListener("click", function() {
    document.querySelector(".submenu").classList.toggle("mostrar");
});
document.querySelector(".menu-servicios a").addEventListener("click", function(e) {
    e.preventDefault(); // Evita que el enlace haga un salto de página
    document.querySelector(".menu-servicios").classList.toggle("activo");
});
/*! http://responsiveslides.com v1.55 by @viljamis */
(function(c,K,C){c.fn.responsiveSlides=function(m){var a=c.extend({auto:!0,speed:500,timeout:4E3,pager:!1,nav:!1,random:!1,pause:!1,pauseControls:!0,prevText:"Previous",nextText:"Next",maxwidth:"",navContainer:"",manualControls:"",namespace:"rslides",before:c.noop,after:c.noop},m);return this.each(function(){C++;var f=c(this),u,t,v,n,q,r,p=0,e=f.children(),D=e.length,h=parseFloat(a.speed),E=parseFloat(a.timeout),w=parseFloat(a.maxwidth),g=a.namespace,d=g+C,F=g+"_nav "+d+"_nav",x=g+"_here",k=d+"_on",
    y=d+"_s",l=c("<ul class='"+g+"_tabs "+d+"_tabs' />"),z={"float":"left",position:"relative",opacity:1,zIndex:2},A={"float":"none",position:"absolute",opacity:0,zIndex:1},G=function(){var b=(document.body||document.documentElement).style,a="transition";if("string"===typeof b[a])return!0;u=["Moz","Webkit","Khtml","O","ms"];var a=a.charAt(0).toUpperCase()+a.substr(1),c;for(c=0;c<u.length;c++)if("string"===typeof b[u[c]+a])return!0;return!1}(),B=function(b){a.before(b);G?(e.removeClass(k).css(A).eq(b).addClass(k).css(z),
    p=b,setTimeout(function(){a.after(b)},h)):e.stop().fadeOut(h,function(){c(this).removeClass(k).css(A).css("opacity",1)}).eq(b).fadeIn(h,function(){c(this).addClass(k).css(z);a.after(b);p=b})};a.random&&(e.sort(function(){return Math.round(Math.random())-.5}),f.empty().append(e));e.each(function(a){this.id=y+a});f.addClass(g+" "+d);m&&m.maxwidth&&f.css("max-width",w);e.hide().css(A).eq(0).addClass(k).css(z).show();G&&e.show().css({"-webkit-transition":"opacity "+h+"ms ease-in-out","-moz-transition":"opacity "+
    h+"ms ease-in-out","-o-transition":"opacity "+h+"ms ease-in-out",transition:"opacity "+h+"ms ease-in-out"});if(1<e.length){if(E<h+100)return;if(a.pager&&!a.manualControls){var H=[];e.each(function(a){a+=1;H+="<li><a href='#' class='"+y+a+"'>"+a+"</a></li>"});l.append(H);m.navContainer?c(a.navContainer).append(l):f.after(l)}a.manualControls&&(l=c(a.manualControls),l.addClass(g+"_tabs "+d+"_tabs"));(a.pager||a.manualControls)&&l.find("li").each(function(a){c(this).addClass(y+(a+1))});if(a.pager||a.manualControls)r=
    l.find("a"),t=function(a){r.closest("li").removeClass(x).eq(a).addClass(x)};a.auto&&(v=function(){q=setInterval(function(){e.stop(!0,!0);var b=p+1<D?p+1:0;(a.pager||a.manualControls)&&t(b);B(b)},E)},v());n=function(){a.auto&&(clearInterval(q),v())};a.pause&&f.hover(function(){clearInterval(q)},function(){n()});if(a.pager||a.manualControls)r.bind("click",function(b){b.preventDefault();a.pauseControls||n();b=r.index(this);p===b||c("."+k).queue("fx").length||(t(b),B(b))}).eq(0).closest("li").addClass(x),
    a.pauseControls&&r.hover(function(){clearInterval(q)},function(){n()});if(a.nav){g="<a href='#' class='"+F+" prev'>"+a.prevText+"</a><a href='#' class='"+F+" next'>"+a.nextText+"</a>";m.navContainer?c(a.navContainer).append(g):f.after(g);var d=c("."+d+"_nav"),I=d.filter(".prev");d.bind("click",function(b){b.preventDefault();b=c("."+k);if(!b.queue("fx").length){var d=e.index(b);b=d-1;d=d+1<D?p+1:0;B(c(this)[0]===I[0]?b:d);(a.pager||a.manualControls)&&t(c(this)[0]===I[0]?b:d);a.pauseControls||n()}});
    a.pauseControls&&d.hover(function(){clearInterval(q)},function(){n()})}}if("undefined"===typeof document.body.style.maxWidth&&m.maxwidth){var J=function(){f.css("width","100%");f.width()>w&&f.css("width",w)};J();c(K).bind("resize",function(){J()})}})}})(jQuery,this,0);
    
    $(function() {
          $(".rslides").responsiveSlides({
            timeout: 5000
          });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const menuButton = document.getElementById("menu-btn");
        const menuItems = document.getElementById("menu-items");
    
        menuButton.addEventListener("click", function () {
            // Alternar la visibilidad del menú
            if (menuItems.style.display === "block") {
                menuItems.style.display = "none";
            } else {
                menuItems.style.display = "block";
            }
        });
    
        // Cerrar el menú si se hace clic fuera de él
        document.addEventListener("click", function (event) {
            if (!menuButton.contains(event.target) && !menuItems.contains(event.target)) {
                menuItems.style.display = "none";
            }
        });
    });