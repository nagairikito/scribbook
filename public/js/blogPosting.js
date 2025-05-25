const blogPostingForm = document.getElementById("blog-posting-form");
// let image = '<img src="http://localhost/storage/blog_contents_images/noImage.png">';
const inputData = document.getElementById('original-contents');

//選択された画像
let selectedImage = null;

//設定された画像サイズ
let imageSize = 300;

//コンテンツの最初の行にdivタグ付与
const div = document.createElement("div");
div.appendChild(inputData.childNodes[0]);
inputData.prepend(div);

//入力１行目にdivタグをつける処理
inputData.addEventListener('input', function (e) {
    // console.log('内容が変更されました: ', this.innerHTML);
    // count = this.childNodes.length;
    nodes = Array.from(this.childNodes).map((child) => {
        // console.log(child.childNodes.length);
        return Array.from(child.childNodes);
    });
    console.log(nodes);
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

    adjustImportImageArea(importImageAreas);

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
    imageCount = 1
    importImageAreas.forEach((importImageArea) => {
        targetClass = importImageArea.classList[1];
        importImageArea.classList.remove(targetClass);
        importImageArea.classList.add(`no${imageCount}`);
        imageCount += 1;
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
    console.log(value);
    // var regexp = new RegExp(/^[0-9]+(\.[0-9]+)?$/);
    // return regexp.test(val);
    imageSize = value;
    test();
}
function test() {
    console.log(imageSize)
}

//画像サイズを適用する
function adoptImageSize() {
    // if(imageSize == null || imageSize == "" || imageSize == [] || imageSize === "undefined") {
    //     return;
    // }
    console.log(selectedImage)
    // selectedImage.style = `width: ${imageSize};`
    selectedImage.setAttribute("style", `width: ${imageSize}px;`);
        console.log(selectedImage)

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
    // if(targetPoint.anchorNode === null) {
    //     return;
    // }
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
        let contentsImageFields = document.querySelectorAll('.contents-image-area');

        let contentsImageField = document.createElement("div");
        contentsImageField.classList.add("contents-image-area");
        contentsImageField.classList.add(`no${contentsImageFields.length + 1}`);
        contentsImageField.setAttribute("style", "display: flex; flex-direction: column;");
        contentsImageField.setAttribute("contenteditable", false);

        let contentsImageFieldButtons = document.createElement("div");
        contentsImageFieldButtons.classList.add("contents-image-area-buttons");

        contentsImageField.appendChild(contentsImageFieldButtons);

        let deleteButton = document.createElement("button");
        deleteButton.setAttribute("onclick", `deleteImage(".contents-image-area.no${contentsImageFields.length + 1}")`);
        deleteButton.textContent = "✕";

        let selectButton = document.createElement("input");
        selectButton.type = "radio";
        selectButton.name = "contents-image";
        selectButton.setAttribute("onclick", `setSelectedImage(".contents-image.no${contentsImageFields.length + 1}")`);

        contentsImageFieldButtons.appendChild(deleteButton);
        contentsImageFieldButtons.appendChild(selectButton);

        let img = document.createElement("img");
        img.src = e.target.result;
        img.classList.add("contents-image"),
        img.classList.add(`no${contentsImageFields.length + 1}`),
        img.setAttribute("style", "width: 300px;");

        contentsImageField.appendChild(img);

        let range = targetPoint.getRangeAt(0);
        range.insertNode(contentsImageField);
        // range.setStartAfter();
    });
    reader.readAsDataURL(file);
})


function showColorSelector() {
    const colorSelector = document.getElementById("color-selector");
    colorSelector.style.display = colorSelector.style.display === 'none' ? 'block' : 'none';
}

function updateColor(color) {
    document.getElementById('selectedColor').textContent = color;
}


// document.addEventListener('mouseup', function (ev) {
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
