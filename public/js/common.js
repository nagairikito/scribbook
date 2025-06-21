window.addEventListener('DOMContentLoaded', () => {
    //ヘッダーメール未読件数
    $.ajax({
            url: '/getAllUnReadMessageCount',
            method: 'GET',
            dataType: "json",
    })
    .done((res) => {
        if(res.unReadMsgCount != 0 && res.unReadMsgCount != null) {
            let targetElm = document.querySelector('.header-talk-area');

            parentSpan = document.createElement("span");
            parentSpan.classList.add('header-unread-messege-count-circle');

            childSpan = document.createElement("span");
            childSpan.classList.add('header-unread-messege-count');
            childSpan.textContent = res.unReadMsgCount;

            parentSpan.appendChild(childSpan);
            targetElm.insertBefore(parentSpan, targetElm.firstChild);
        }
    })
    .fail((error) => {
        console.log('失敗！');
    });
});

let searchIcon = document.getElementById('search-icon');
searchIcon.addEventListener('click', () => {
    document.querySelector('.sp-header-search-bar').classList.toggle('active');
})

let listIcon = document.getElementById('sp-list-icon');
listIcon.addEventListener('click', () => {
    document.querySelector('nav').classList.toggle('active');
})