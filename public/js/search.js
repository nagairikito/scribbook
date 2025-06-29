document.querySelector('.select-btn.blog').addEventListener('click', () => {
    document.querySelectorAll('.select-btn').forEach((btn) => {
        if(btn.classList.contains('blog')) {
            if(!btn.classList.contains('selected')) {
                btn.classList.add('selected');
            }
        } else {
            btn.classList.remove('selected');
        }
    });

    document.querySelectorAll('.category').forEach((category) => {
        if(category.classList.contains('blog')) {
            if(category.classList.contains('display-none')) {
                category.classList.remove('display-none');
            }
        } else {
            category.classList.add('display-none');
        }
    });
});

document.querySelector('.select-btn.user').addEventListener('click', () => {
    document.querySelectorAll('.select-btn').forEach((btn) => {
        if(btn.classList.contains('user')) {
            if(!btn.classList.contains('selected')) {
                btn.classList.add('selected');
            }
        } else {
            btn.classList.remove('selected');
        }
    });

    document.querySelectorAll('.category').forEach((category) => {
        if(category.classList.contains('user')) {
            if(category.classList.contains('display-none')) {
                category.classList.remove('display-none');
            }
        } else {
            category.classList.add('display-none');
        }
    });
});
