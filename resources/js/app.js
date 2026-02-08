import './bootstrap';
import './hotline-tracker';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import AOS from 'aos';
import 'aos/dist/aos.css';
import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.css';
import Swiper from 'swiper';
import { Autoplay, Navigation } from 'swiper/modules';
import 'swiper/css';

window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 650,
        once: true,
    });

    if (document.querySelector('.glightbox')) {
        GLightbox({
            selector: '.glightbox',
        });
    }

    const testimonialSwiper = document.querySelector('.testimonial-swiper');
    if (testimonialSwiper) {
        new Swiper(testimonialSwiper, {
            modules: [Navigation, Autoplay],
            slidesPerView: 1,
            spaceBetween: 16,
            navigation: {
                nextEl: '.testimonial-next',
                prevEl: '.testimonial-prev',
            },
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 3,
                },
            },
        });
    }
});
