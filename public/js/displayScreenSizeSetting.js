let header = document.querySelector('#header');
let mainWrapper = document.querySelector('.main-wrapper');
let footer = document.querySelector('#footer');

let w = window.screen.availWidth;
header.setAttribute('style', 'min-width:' + w + 'px; margin:0 auto;');
mainWrapper.setAttribute('style', 'width:' + w + 'px; margin:0 auto;');
footer.setAttribute('style', 'min-width:' + w + 'px; margin:0 auto;');


