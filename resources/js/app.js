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

    const leadersSwiper = document.querySelector('.leaders-swiper');
    if (leadersSwiper) {
        new Swiper(leadersSwiper, {
            modules: [Navigation, Autoplay],
            slidesPerView: 1.25,
            spaceBetween: 12,
            centeredSlides: false,
            centerInsufficientSlides: true,
            loop: true,
            grabCursor: true,
            navigation: {
                nextEl: '.leaders-next',
                prevEl: '.leaders-prev',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 16,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 24, // Wider spacing for clarity
                },
            },
        });
    }
});
