/**
 * Gospel Ambition Theme JavaScript
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {

        // Mobile menu toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const hamburgerMenu = document.querySelector('.hamburger-menu');
        const hamburgerMenuOverlay = document.querySelector('.hamburger-menu-overlay');

        if (mobileMenuToggle) {
            const toggleMenu = function() {
                if (hamburgerMenu) {
                    hamburgerMenu.dataset.state = hamburgerMenu.dataset.state === 'open' ? 'closed' : 'open';
                    mobileMenuToggle.classList.toggle('open');
                    hamburgerMenuOverlay.dataset.state = hamburgerMenuOverlay.dataset.state === 'open' ? 'closed' : 'open';
                }
            }
            mobileMenuToggle.addEventListener('click', toggleMenu);
            hamburgerMenuOverlay.addEventListener('click', toggleMenu);
        }

        // Mobile sub-menu toggle
        const menuItemsWithChildren = document.querySelectorAll('.main-navigation .menu-item-has-children > a');
        menuItemsWithChildren.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    this.parentElement.classList.toggle('mobile-open');
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const isClickInsideNav = e.target.closest('.main-navigation');
            const isClickOnToggle = e.target.closest('.mobile-menu-toggle');

            if (!isClickInsideNav && !isClickOnToggle) {
                if (mobileMenuToggle) {
                    mobileMenuToggle.classList.remove('active');
                }
                if (hamburgerMenu) {
                    hamburgerMenu.classList.remove('mobile-active');
                }
                const openMenuItems = document.querySelectorAll('.menu-item-has-children.mobile-open');
                openMenuItems.forEach(function(item) {
                    item.classList.remove('mobile-open');
                });
            }
        });

        // Smooth scrolling for anchor links
        const anchorLinks = document.querySelectorAll('a[href*="#"]:not([href="#"])');
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.indexOf('#') !== -1) {
                    const hash = href.substring(href.indexOf('#'));
                    const pathname = location.pathname.replace(/^\//, '');
                    const linkPathname = this.pathname.replace(/^\//, '');

                    if (pathname === linkPathname && location.hostname === this.hostname) {
                        let target = document.querySelector(hash);
                        if (!target) {
                            target = document.querySelector('[name="' + hash.slice(1) + '"]');
                        }

                        if (target) {
                            e.preventDefault();
                            const targetTop = target.getBoundingClientRect().top + window.scrollY - 80;

                            window.scrollTo({
                                top: targetTop,
                                behavior: 'smooth'
                            });
                        }
                    }
                }
            });
        });

        // Animate elements on scroll
        function animateOnScroll() {
            const postCards = document.querySelectorAll('.post-card');
            postCards.forEach(function(card) {
                const rect = card.getBoundingClientRect();
                const elementTop = rect.top + window.scrollY;
                const elementBottom = elementTop + card.offsetHeight;
                const viewportTop = window.scrollY;
                const viewportBottom = viewportTop + window.innerHeight;

                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    card.classList.add('animate-in');
                }
            });
        }

        // Run animation on scroll and page load
        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('resize', animateOnScroll);
        animateOnScroll();

        const highlights = document.querySelectorAll('.highlight');
        highlights.forEach(function(item) {
            const words = item.textContent.split(' ');
            const highlightIndex = item.dataset.highlightIndex ? parseInt(item.dataset.highlightIndex) -1 : -1;
            const highlightLast = item.dataset.highlightLast === "" ? true : false;
            const highlightColor = item.dataset.highlightColor ? item.dataset.highlightColor : 'primary';
            const newInnerHTML = words.reduce((acc, word, index) => {
                if (highlightIndex > -1 && index === highlightIndex) {
                    acc.push(`<span class="color-${highlightColor}">${word}</span>`);
                    return acc;
                }
                if (highlightLast && index === words.length - 1) {
                    acc.push(`<span class="color-${highlightColor}">${word}</span>`);
                    return acc;
                }
                acc.push(word)
                return acc;
            }, []);
            item.innerHTML = newInnerHTML.join(' ');
        });

        // Auto scrolling slideshow
        // credit to https://codepen.io/knekk/pen/ZEQMjgb?editors=0010 for the original code
        function initSlideshow(reel) {
            // Fade in
            reel.classList.add("in");

            const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
            const scrollDirection = isRtl ? -1 : 1;
            // Auto scroll slideshow
            setInterval(() => {
                const firstImage = [...reel.children].reduce((prev, current) => (Number(prev.style.order) < Number(current.style.order)) ? prev : current);

                // Move the first image back in queue when it's out of view
                if (firstImage.offsetWidth < reel.scrollLeft) {
                    reel.scrollLeft = reel.scrollLeft - firstImage.offsetWidth * scrollDirection;
                    firstImage.style.order = reel.children.length;
                    for (const image of [...reel.children]) {
                        if (image != firstImage) image.style.order = image.style.order-1;
                    }
                } else {
                    reel.scrollLeft += 1 * scrollDirection;
                }
            }, 20);
        }
        function getLanguage() {
            return 'en';
        }
        async function getPeopleGroupsForReel() {
            const reel = document.getElementById('reel-people-groups');
            if (!reel) return;

            const numberOfPeopleGroups = 20;
            const language = getLanguage();
            const prayBaseUrl = (window.uupgsData && window.uupgsData.prayBaseUrl) || 'https://pray.doxa.life';
            const response = await fetch(prayBaseUrl + '/api/people-groups/list?lang=' + language);
            const data = await response.json();

            const peopleGroups = data.posts.filter(group => group.has_photo)
            peopleGroups.sort(() => Math.random() - 0.5)

            let hasDeafPeopleGroup = false;
            let filteredPeopleGroups = peopleGroups.filter((group) => {
                if (group.display_name.toLowerCase().includes('deaf')) {
                    if (hasDeafPeopleGroup) {
                        return false;
                    }
                    hasDeafPeopleGroup = true;
                }
                return true
            });
            filteredPeopleGroups = filteredPeopleGroups.slice(0, numberOfPeopleGroups);

            filteredPeopleGroups.forEach(group => {
                const item = document.createElement('a');
                item.classList.add('stack', 'stack--sm', 'reel__item', 'light-link');
                item.href = reel.dataset.researchUrl + group.slug;
                item.target = '_blank';
                item.innerHTML = `
                    <div><img class="square rounded-md size-md" src="${group.picture_url}" alt="${group.display_name}"></div>
                    <p class="text-center uppercase width-md">${group.display_name}</p>
                `;
                reel.appendChild(item);
            });
        }
        async function initSlideshows() {
            // Using a for..of loop in case you want more slideshows on page.
            for (const reel of [...document.querySelectorAll(".reel[data-reel-mode='auto-scroll']")]) {
                const images = reel.querySelectorAll('img');
                for (const image of [...images]) {
                    await new Promise(resolve => {
                        if (image.complete) resolve();
                        else image.onload = resolve;
                    });
                }
                let index = 0;
                for (const child of [...reel.children]) {
                    child.style.order = index;
                    index++;
                }

                initSlideshow(reel);
            }
        };
        getPeopleGroupsForReel()
            .then(() => {
                initSlideshows();
            });

        // Video modal toggle
        const videoModalButton = document.querySelector('.video-modal-button');
        const videoModal = document.querySelector('.video-modal');
        const videoModalOverlay = document.querySelector('.video-modal-overlay');
        const vimeoPlayer = document.getElementById('vimeo-player');

        if (videoModalButton) {
            const videoSrc = vimeoPlayer.src;
            videoModalButton.addEventListener('click', function() {
                videoModal.dataset.state = videoModal.dataset.state === 'open' ? 'closed' : 'open';
                videoModalOverlay.dataset.state = videoModalOverlay.dataset.state === 'open' ? 'closed' : 'open';
                vimeoPlayer.src = videoSrc + '&autoplay=1';
                history.pushState(null, '', '#playing-video');
            });

            function closeVideoModal() {
                videoModal.dataset.state = 'closed';
                videoModalOverlay.dataset.state = 'closed';
                vimeoPlayer.src = '';
                vimeoPlayer.src = videoSrc;
            }
            videoModalOverlay.addEventListener('click', closeVideoModal);
            window.addEventListener('keydown', function(e) {
                if (videoModal.dataset.state === 'open' && e.key === 'Escape') {
                    closeVideoModal();
                }
            });
            window.addEventListener('popstate', function(e) {
                if (videoModal.dataset.state === 'open') {
                    e.preventDefault();
                    closeVideoModal();
                }
            });
        }

        // Back button
        const backButtons = document.querySelectorAll('[data-action="back"]');
        if (backButtons) {
            backButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    if ( window.history.length > 1 ) {
                        if ( window.navigation.canGoBack === false ) {
                            window.location.href = button.dataset.url;
                            return
                        }
                        window.history.back();
                    } else {
                        window.location.href = button.dataset.url;
                    }
                });
            });
        }

        const mapOverlay = document.querySelector('.map-card .overlay')
        if (mapOverlay) {
            let clicks = 0
            let timeout
            const resetOverlayTimeout = function() {
                clearTimeout(timeout)
                timeout = setTimeout(() => {
                    clicks = 0
                    mapOverlay.style.display = 'flex'
                }, 120000)
            }
            mapOverlay.addEventListener('click', function() {
                if (clicks === 0) {
                    const span = document.createElement('span')
                    span.innerHTML = window.uupgsData.translations.click_twice
                    mapOverlay.parentElement.appendChild(span)
                }

                if ( clicks === 1 ) {
                    mapOverlay.style.display = 'none'
                    resetOverlayTimeout()
                    const span = mapOverlay.parentElement.querySelector('span')
                    if (span) mapOverlay.parentElement.removeChild(span)
                }
                clicks += 1
            })
        }
    });
    document.querySelectorAll('.pll-parent-menu-item > a').forEach(link => {
        link.addEventListener('click', event => {
            const parent = link.parentElement;
            if (!parent) return;

            // Prevent following the "#pll_switcher" link
            if (link.getAttribute('href') === '#pll_switcher') {
                event.preventDefault();
            }

            parent.classList.toggle('is-open');
        });
    });
    document.querySelectorAll('.menu-item-type-custom > a').forEach(item => {
        if (item.getAttribute('href').startsWith('#')) {
            return
        }
        const url = new URL(item.getAttribute('href'));
        if (url.hostname !== window.location.hostname) {
            // add target="_blank" to the item and an icon for the external link
            item.target = '_blank';
            item.classList.add('with-icon');
            item.innerHTML = `
                ${item.innerHTML}
                <svg class="icon right width-md" fill="currentColor" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M36.026,20.058l-21.092,0c-1.65,0 -2.989,1.339 -2.989,2.989l0,25.964c0,1.65 1.339,2.989 2.989,2.989l26.024,0c1.65,0 2.989,-1.339 2.989,-2.989l0,-20.953l3.999,0l0,21.948c0,3.308 -2.686,5.994 -5.995,5.995l-28.01,0c-3.309,0 -5.995,-2.687 -5.995,-5.995l0,-27.954c0,-3.309 2.686,-5.995 5.995,-5.995l22.085,0l0,4.001Z"></path> <path d="M55.925,25.32l-4.005,0l0,-10.481l-27.894,27.893l-2.832,-2.832l27.895,-27.895l-10.484,0l0,-4.005l17.318,0l0.002,0.001l0,17.319Z"></path>
                </svg>
            `;
        }
    });
})();
