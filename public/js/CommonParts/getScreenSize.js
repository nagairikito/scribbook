let header = document.getElementById('header');
let mainWrapper = document.querySelector('.main-wrapper');
let footer = document.getElementById('footer');

let w = window.screen.availWidth;
header.setAttribute('style', 'width:' + w + 'px;');
mainWrapper.setAttribute('style', 'width:' + w + 'px; margin:0 auto;');
footer.setAttribute('style', 'width:' + w + 'px;');
