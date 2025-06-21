let header = document.querySelector('#header');
let mainWrapper = document.querySelector('.main-wrapper');
// let main = document.querySelector('#main');
let footer = document.querySelector('#footer');

//画面横幅
let w = screen.availWidth;

//表示幅
let Disp = w -15;

//画面高さ
let h = screen.availHeight;

//mainコンテンツの高さ
let mainH = h - 120 -1

header.setAttribute('style', 'min-width:' + w + 'px; margin:0 auto;');
mainWrapper.setAttribute('style', 'width:' + w + 'px; min-height:' + h + 'px; margin:0 auto;');
footer.setAttribute('style', 'min-width:' + w + 'px; margin:0 auto;');