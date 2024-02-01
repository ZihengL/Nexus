const carouselScript = {
    init() {
        const nextDom = document.getElementById('next');
        const prevDom = document.getElementById('prev');

        const carouselDom = document.querySelector('.carousel');
        const SliderDom = carouselDom.querySelector('.list');
        const thumbnailBorderDom = document.querySelector('.thumbnail');
        const thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');

        if (thumbnailItemsDom.length > 0) {
            thumbnailBorderDom.appendChild(thumbnailItemsDom[0].cloneNode(true));
        }

        let runNextAuto;
        const timeAutoNext = 7000; // Délai pour le changement automatique

        function showSlider(type) {
            const SliderItemsDom = SliderDom.querySelectorAll('.item');
            const currentThumbnailItemsDom = document.querySelectorAll('.thumbnail .item');

            if (type === 'next') {
                SliderDom.appendChild(SliderItemsDom[0]);
                thumbnailBorderDom.appendChild(currentThumbnailItemsDom[0].cloneNode(true));
            } else {
                SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
                thumbnailBorderDom.prepend(currentThumbnailItemsDom[currentThumbnailItemsDom.length - 1].cloneNode(true));
            }

            // Redémarrer le changement automatique après action manuelle
            clearTimeout(runNextAuto);
            runNextAuto = setTimeout(() => {
                nextDom.click();
            }, timeAutoNext);
        }

        nextDom.addEventListener('click', () => showSlider('next'));
        prevDom.addEventListener('click', () => showSlider('prev'));

        // Initialiser le changement automatique
        runNextAuto = setTimeout(() => {
            nextDom.click();
        }, timeAutoNext);
    }
};

export default carouselScript;
