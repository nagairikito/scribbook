const blogPostingForm = document.getElementById("blog-posting-form");
// let image = '<img src="http://localhost/storage/blog_contents_images/noImage.png">';
const inputData = document.getElementById('original-contents');

//選択された画像
let selectedImage = null;

//設定された画像サイズ
let imageSize = 300;

//文字サイズ
let fontSize = 16;

//透明度
let opacity = 1;

//文字色
let fontColor = "#ffffff";

//背景色
let backGroundColor = "#ffffff";

//コンテンツの最初の行にdivタグ付与
const div = document.createElement("div");
div.appendChild(inputData.childNodes[0]);
inputData.prepend(div);

//入力１行目にdivタグをつける処理
inputData.addEventListener('input', function (e) {
    nodes = Array.from(this.childNodes).map((child) => {
        return Array.from(child.childNodes);
    });
});

//入力内容をテキストエリアにコピー（inputで送信できないため）
blogPostingForm.addEventListener('submit', function() {
    const originalContents = document.getElementById("original-contents");
    const replacementContents = document.getElementById("replacement-contents");
    replacementContents.value = originalContents.innerHTML;
})

//画像追加
function addImage() {
    let toolSettingField = document.querySelector('.tool-setting-field');

    const importImageAreas = document.querySelectorAll('.import-image-area');

    if(importImageAreas.length > 0) {
        adjustImportImageArea(importImageAreas);
    }

    const parentElement = document.createElement("div");
    parentElement.classList.add("import-image-area");
    parentElement.classList.add(`no${importImageAreas.length + 1}`);

    const deleteButton = document.createElement("button");
    deleteButton.setAttribute('onclick', `deleteToolbarFieldImage(".import-image-area.no${importImageAreas.length + 1}")`);
    deleteButton.textContent = "削除";
    parentElement.appendChild(deleteButton);

    // const transitionButton = document.createElement("button");
    // transitionButton.textContent = "移動";
    // parentElement.appendChild(transitionButton);

    const inputImageButton = document.createElement("input");
    inputImageButton.type = "file";
    inputImageButton.classList.add("import-image-button");
    inputImageButton.classList.add(`no${importImageAreas.length + 1}`);
    inputImageButton.setAttribute("onchange", "showInputImage(this)")
    parentElement.appendChild(inputImageButton);

    toolSettingField.appendChild(parentElement);
}

//画像インポートパーツ増減時のclass名の調整
function adjustImportImageArea(importImageAreas) {
    importImageCount = 1;
    importImageAreas.forEach((importImageArea) => {
        targetClass = importImageArea.classList[1];
        importImageArea.classList.remove(targetClass);
        importImageArea.classList.add(`no${importImageCount}`);
        importImageCount += 1;
    });
}

//インポートした画像をフィールドに表示
function showInputImage(data) {
    const imageNo = data.classList[1];
    const file = data.files[0];
    if(!file) return;
    
    const reader = new FileReader();

    reader.addEventListener('error', () => {
        return;
    });
    reader.addEventListener('load', (e) => {
        let img = document.createElement("img");
        img.src = e.target.result;
        img.classList.add("import-image");
        img.setAttribute("style", "width: 300px;");

        let inputImageField = document.querySelector(`.import-image-area.${imageNo}`);
        data.remove();
        inputImageField.appendChild(img);
    });
    reader.readAsDataURL(file);

}

//画像サイズをセット
function setImageSize(value) {
    var regexp = new RegExp(/^[0-9]+(\.[0-9]+)?$/);
    if(regexp.test(value) == false) {
        return;
    }
    imageSize = value;
}

//画像サイズを適用する
function adoptImageSize() {
    if(imageSize == null || imageSize == "" || imageSize == [] || imageSize === "undefined") {
        return;
    }
    selectedImage.setAttribute("style", `width: ${imageSize}px;`);

}

//選択画像をセット
function setSelectedImage(target) {
    targetImage = document.querySelector(target);
    selectedImage = targetImage;
}

//ツールバーフィールド画像削除
function deleteToolbarFieldImage(target) {
    targetImage = document.querySelector(target);
    targetImage.remove();

    const importImageAreas = document.querySelectorAll('.import-image-area');
    adjustImportImageArea(importImageAreas);
}



//画像のドラッグアンドドロップ処理

//通常のドラッグアンドドロップ無効化
document.addEventListener('dragover', (e) => {
    e.preventDefault();
});
document.addEventListener('drop', (e) => {
    e.preventDefault();
});

//ブログ作成フィールドにドラッグしたときの挙動
var blogEditField = document.getElementById("original-contents");

// document.addEventListener('drop', (e) => {
blogEditField.addEventListener('drop', (e) => {
    let targetPoint = window.getSelection();
    if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }
    e.preventDefault();

    //ファイルを取得
    var file = e.dataTransfer.files[0];
    if(!file || !file.type.match('image.*')) {
        return;
    }

    var reader = new FileReader();
    reader.addEventListener('error', () => {
        return;
    });
    reader.addEventListener('load', (e) => {
        //ブログ編集フィールドのカーソル位置に画像を挿入

        //クラス名「contents-image-area」の数を取得し再命名
        let contentsImageAreas = document.querySelectorAll('.contents-image-area');

        if(contentsImageAreas > 0) {
            adjustContentsImageArea(contentsImageAreas);
        }

        let contentsImageField = document.createElement("div");
        contentsImageField.classList.add("contents-image-area");
        contentsImageField.classList.add(`no${contentsImageAreas.length + 1}`);
        contentsImageField.setAttribute("style", "display: flex; flex-direction: column;");
        contentsImageField.setAttribute("contenteditable", false);

        let contentsImageFieldButtons = document.createElement("div");
        contentsImageFieldButtons.classList.add("contents-image-area-buttons");

        contentsImageField.appendChild(contentsImageFieldButtons);

        let deleteButton = document.createElement("button");
        deleteButton.classList.add("delete-contents-image-button");
        deleteButton.classList.add(`no${contentsImageAreas.length + 1}`);
        deleteButton.setAttribute("onclick", `deleteContentsImage(".contents-image-area.no${contentsImageAreas.length + 1}")`);
        deleteButton.textContent = "✕";

        let selectButton = document.createElement("input");
        selectButton.type = "radio";
        selectButton.name = "contents-image";
        selectButton.classList.add("select-contents-image-button");
        selectButton.classList.add(`no${contentsImageAreas.length + 1}`);
        selectButton.setAttribute("onclick", `setSelectedImage(".contents-image.no${contentsImageAreas.length + 1}")`);

        contentsImageFieldButtons.appendChild(deleteButton);
        contentsImageFieldButtons.appendChild(selectButton);

        let img = document.createElement("img");
        img.src = e.target.result;
        img.classList.add("contents-image"),
        img.classList.add(`no${contentsImageAreas.length + 1}`),
        img.setAttribute("style", "width: 300px;");

        contentsImageField.appendChild(img);

        let range = targetPoint.getRangeAt(0);
        range.insertNode(contentsImageField);
        // range.setStartAfter();
    });
    reader.readAsDataURL(file);
});

//ブログ編集フィールドの画像増減時のclass名調整
function adjustContentsImageArea(contentsImageAreas) {
    let index = 0;
    let contentsImageCount = 1;

    let deleteContentsImageButton = document.querySelectorAll('.delete-contents-image-button');
    let selectContentsImageButton = document.querySelectorAll('.select-contents-image-button');
    let contentsImage = document.querySelectorAll('.contents-image');

    contentsImageAreas.forEach((contentsImageArea) => {
        deleteTarget = contentsImageArea.classList[1];
        contentsImageArea.classList.remove($deleteTarget);
        contentsImageArea.classList.add(`no${contentsImageCount}`);

        deleteContentsImageButton[index].classList.remove($deleteTarget);
        deleteContentsImageButton[index].classList.add(`no${contentsImageCount}`);
        
        selectContentsImageButton[index].classList.remove($deleteTarget);
        selectContentsImageButton[index].classList.add(`no${contentsImageCount}`);

        contentsImage[index].classList.remove($deleteTarget);
        contentsImage[index].classList.add(`no${contentsImageCount}`);

        index += 1;
        contentsImageCount += 1;
    })
}

function deleteContentsImage(target) {
    let targetImage = document.querySelector(target);
    targetImage.remove();

    let contentsImageAreas = document.querySelectorAll('.contents-image-area');
    if(contentsImageAreas > 0) {
        adjustContentsImageArea(contentsImageAreas);
    }
}

//文字色セット
function setFontColor(value) {
    if(!value || value == null || value === "undefined" || value == "" || value == []) {
        return;
    }
    fontColor = value;
}

//文字色適用
function adoptFontColor() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const span = document.createElement("span");
    span.style.color = fontColor;
    span.textContent = range.toString();

    range.deleteContents();
    range.insertNode(span);
}

//背景色セット
function setBackGroundColor(value) {
    if(!value || value == null || value === "undefined" || value == "" || value == []) {
        return;
    }
    backGroundColor = value;
}

//背景色適用
function adoptBackGroundColor() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const span = document.createElement("span");
    span.style.backgroundColor = backGroundColor;
    span.textContent = range.toString();

    range.deleteContents();
    range.insertNode(span);
}

//文字サイズセット
function setFontSize(value) {
    if(!value || value == null || value === "undefined" || value == "" || value == []
        || 100 < value || 1 > value
    ) {
        return;
    }
    fontSize = value
}

//文字サイズ適用
function adoptFontSize() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const span = document.createElement("span");
    span.style.fontSize = `${fontSize}px`;
    span.textContent = range.toString();

    range.deleteContents();
    range.insertNode(span);
}

//文字斜体
function adoptFontStyleItaric() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const i = document.createElement("i");
    i.textContent = range.toString();

    range.deleteContents();
    range.insertNode(i);
}

//取消線
function adoptStrikeThrough() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const s = document.createElement("s");
    s.textContent = range.toString();

    range.deleteContents();
    range.insertNode(s);
}

//下部線
function adoptUnderLine() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const span = document.createElement("span");
    span.style.textDecoration = 'underline';
    span.textContent = range.toString();

    range.deleteContents();
    range.insertNode(span);
}

//透明度セット
function setOpacity(value) {
    if(!value || value == null || value === "undefined" || value == "" || value == []
        || 100 < value || 0 > value
    ) {
        return;
    }
    opacity = value / 100;
}

//透明度適用
function adoptOpacity() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const span = document.createElement("span");
    span.style.opacity = opacity;
    span.textContent = range.toString();

    range.deleteContents();
    range.insertNode(span);
}


// inputData.addEventListener('touchend', function (ev) {
//     const selection = document.getSelection();

//     if(!selection.rangeCount) {
//         return;
//     }

//     const range = selection.getRangeAt(0);

//     if(range.collapsed) {
//         return;
//     }

//     const span = document.createElement("span");
//     span.style.backgroundColor = "yellow";
//     span.textContent = range.toString();

//     range.deleteContents();
//     range.insertNode(span);

// }, false);
