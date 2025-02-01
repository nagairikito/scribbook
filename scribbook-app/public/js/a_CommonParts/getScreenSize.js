let header = document.getElementById('header');
let mainWrapper = document.querySelector('.main-wrapper');
let footer = document.getElementById('footer');

let w = window.screen.availWidth;
header.setAttribute('style', 'min-width:' + w + 'px; width: 100%;');
mainWrapper.setAttribute('style', 'width:' + w + 'px; margin:0 auto;');
footer.setAttribute('style', 'min-width:' + w + 'px; width: 100%;');
