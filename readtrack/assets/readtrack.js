window.addEventListener('scroll', function () {
    let scrollTop = window.scrollY;
    let docHeight = document.body.scrollHeight - window.innerHeight;
    let scrollPercent = (scrollTop / docHeight) * 100;
    let bar = document.querySelector('.readtrack-progress-bar');
    if (bar) {
        bar.style.width = scrollPercent + '%';
    }
});

