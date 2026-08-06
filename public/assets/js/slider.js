document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.slider-wrapper').forEach(initSlider);
});

function initSlider(wrapper) {
    const viewport = wrapper.querySelector('.slider-viewport');
    const track = wrapper.querySelector('.slider-items');
    const prevBtn = wrapper.querySelector('.prev-btn');
    const nextBtn = wrapper.querySelector('.next-btn');

    if (!viewport || !track) return;

    const originalItems = Array.from(track.children);
    const n = originalItems.length;
    if (n === 0) return;

    const cloneCount = Math.min(3, n);
    const headClones = originalItems.slice(n - cloneCount).map((el) => el.cloneNode(true));
    const tailClones = originalItems.slice(0, cloneCount).map((el) => el.cloneNode(true));

    headClones.forEach((clone) => {
        clone.setAttribute('aria-hidden', 'true');
        track.insertBefore(clone, track.firstChild);
    });
    tailClones.forEach((clone) => {
        clone.setAttribute('aria-hidden', 'true');
        track.appendChild(clone);
    });

    const allItems = Array.from(track.children);
    let index = cloneCount;
    let isAnimating = false;

    function getStep() {
        const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
        const itemWidth = allItems[0].getBoundingClientRect().width;
        return itemWidth + gap;
    }

    function setPosition(withTransition) {
        if (!withTransition) {
            track.style.transition = 'none';
        }
        track.style.transform = `translateX(-${index * getStep()}px)`;
        if (!withTransition) {
            void track.offsetHeight;
            track.style.transition = '';
        }
    }

    function goNext() {
        if (isAnimating) return;
        isAnimating = true;
        index += 1;
        setPosition(true);
    }

    function goPrev() {
        if (isAnimating) return;
        isAnimating = true;
        index -= 1;
        setPosition(true);
    }

    track.addEventListener('transitionend', function () {
        isAnimating = false;
        if (index >= cloneCount + n) {
            index -= n;
            setPosition(false);
        } else if (index < cloneCount) {
            index += n;
            setPosition(false);
        }
    });

    if (nextBtn) nextBtn.addEventListener('click', goNext);
    if (prevBtn) prevBtn.addEventListener('click', goPrev);

    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            setPosition(false);
        }, 150);
    });

    setPosition(false);
}
