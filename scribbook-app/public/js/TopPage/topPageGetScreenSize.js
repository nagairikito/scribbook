let blogUnit = document.querySelector('.blog-unit');
let blogUnitWidth = blogUnit.clientWidth;
let blogUnitAll = document.querySelectorAll('.blog-unit');

window.addEventListener('DOMContentLoaded', () => {
    for(let i=0; i<blogUnitAll.length; i++) {
        blogUnitAll[i].setAttribute('style', 'height:' + blogUnitWidth + 'px;');
    }
});


