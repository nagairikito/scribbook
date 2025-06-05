//文字サイズ
let fontSize = 16;

//透明度
let opacity = 1;

//文字色
let fontColor = "#ffffff";

//背景色
let backGroundColor = "#ffffff";

//選択された画像
let selectedImage = null;

//設定された画像サイズ
let imageSize = 300;

//画像の表示URL
let displayImagePath = "http://localhost/storage/blog_contents_images/"

//ブログ編集フィールドの画像のファイル名
let imageFileName = '';


const blogPostingForm = document.getElementById("blog-posting-form");
const inputData = document.getElementById('original-contents');

//コンテンツの最初の行にdivタグ付与(なぜか1行目にdivタグがふよされないため以下の処理を記述)
const div = document.createElement("div");
div.appendChild(inputData.childNodes[0]);
inputData.prepend(div);

//入力１行目にdivタグをつける処理
// inputData.addEventListener('input', function (e) {
//     inputChildNodes = Array.from(this.childNodes)
//     if(inputChildNodes.length > 1) {
//         inputChildNodes.forEach((child) => {
//             return Array.from(child.childNodes);
//         });
//     }
// });

//submit後の処理：入力内容をテキストエリアにコピー（inputで送信できないため）
blogPostingForm.addEventListener('submit', function(e) {
    //img srcをbase64から「保存先＋ファイル名」に差し替え
    let blogPostingForm = document.getElementById("blog-posting-form");
    let contentsImages = document.querySelectorAll('.contents-image');

    let date = new Date();
    let subimtTime = date.getFullYear()
                     + ('0' + (date.getMonth() + 1)).slice(-2)
                     + ('0' + date.getDate()).slice(-2)
                     + ('0' + date.getHours()).slice(-2)
                     + ('0' + date.getMinutes()).slice(-2);
    let blogUniqueId = subimtTime + "_" + generateRandomString(16);

    let inputBlogUniqueId = document.createElement("input");
    inputBlogUniqueId.type = "hidden";
    inputBlogUniqueId.name = "blog_unique_id";
    inputBlogUniqueId.value = blogUniqueId;
    blogPostingForm.appendChild(inputBlogUniqueId);

    contentsImages.forEach((image) => {
        let base64Src = image.src;
        let fileName = blogUniqueId + "_" + image.alt;

        image.src = displayImagePath + fileName;

        let div = document.createElement('div');
        div.classList.add(fileName);
        blogPostingForm.appendChild(div);

        let inputFileName = document.createElement("input");
        inputFileName.type = "hidden";
        inputFileName.name = "image_file_name[]"
        inputFileName.value = fileName;
        div.appendChild(inputFileName);

        let inputBase64Text = document.createElement("input");
        inputBase64Text.type = "hidden";
        inputBase64Text.name = "base64_text[]"
        inputBase64Text.value = base64Src;
        div.appendChild(inputBase64Text);
    })

    let contentsImageAreaButtons = document.querySelectorAll('.contents-image-area-buttons');
    contentsImageAreaButtons.forEach((buttons) => {
        buttons.style = "display: none;";
    })

    const originalContents = document.getElementById("original-contents");
    const replacementContents = document.getElementById("replacement-contents");
    replacementContents.value = originalContents.innerHTML;
})

//画像保存時のランダムな羅列を生成
function generateRandomString(length = 8) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}

//通常のドラッグアンドドロップ無効化
document.addEventListener('dragover', (e) => {
    e.preventDefault();
});
document.addEventListener('drop', (e) => {
    e.preventDefault();
});

document.addEventListener('dragstart', (e) => {
    if(e.target.id == 'import-image-area') {
        imageFileName = e.target.alt;
    }
})

//ブログ編集フィールドに画像をドラッグしたときの挙動
var blogEditField = document.getElementById("original-contents");

blogEditField.addEventListener('drop', (e) => {
    let targetPoint = window.getSelection();
    if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }
    e.preventDefault();

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

        //ランダムな羅列生成
        let randomStr = generateRandomString();

        //ブログ編集フィールドの画像altと登録時の画像ファイル名
        let registerImageFileName = imageFileName + "_" + randomStr; 

        let img = document.createElement("img");
        img.src = e.target.result;
        img.alt = registerImageFileName;
        img.classList.add("contents-image"),
        img.classList.add(`no${contentsImageAreas.length + 1}`),
        img.setAttribute("style", "width: 300px;");

        contentsImageField.appendChild(img);

        let range = targetPoint.getRangeAt(0);
        range.insertNode(contentsImageField);
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

//ブログ編集フィールドの画像削除
function deleteContentsImage(target) {
    let targetImage = document.querySelector(target);
    targetImage.remove();

    let contentsImageAreas = document.querySelectorAll('.contents-image-area');
    if(contentsImageAreas > 0) {
        adjustContentsImageArea(contentsImageAreas);
    }
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

//リンク
function adoptUrl() {
    const targetPoint = document.getSelection();
        if(targetPoint.anchorNode === null || !blogEditField.contains(targetPoint.anchorNode)) {
        return;
    }

    const range = targetPoint.getRangeAt(0);
    if(!range) {
        return;
    }

    const a = document.createElement("a");
    a.href = range.toString();
    a.textContent = range.toString();

    range.deleteContents();
    range.insertNode(a);
}

//画像インポートツール表示(画像インポートボタン押下時)
function addImage() {
    let importedImageField = document.querySelector('.imported-img-field');

    const importImageAreas = document.querySelectorAll('.import-image-area');

    if(importImageAreas.length > 0) {
        adjustImportImageArea(importImageAreas);
    }

    const parentElement = document.createElement("div");
    parentElement.classList.add("import-image-area");
    parentElement.classList.add(`no${importImageAreas.length + 1}`);

    const deleteButton = document.createElement("button");
    deleteButton.setAttribute('onclick', `deleteImportedImage(".import-image-area.no${importImageAreas.length + 1}")`);
    deleteButton.style = 'width: 30px;'
    deleteButton.textContent = '✕';
    parentElement.appendChild(deleteButton);

    const inputImageButton = document.createElement("input");
    inputImageButton.type = "file";
    inputImageButton.classList.add("import-image-button");
    inputImageButton.classList.add(`no${importImageAreas.length + 1}`);
    inputImageButton.setAttribute("onchange", "importImage(this)")
    parentElement.appendChild(inputImageButton);

    importedImageField.appendChild(parentElement);
}

//画像インポートパーツ増減時のclass名の調整
function adjustImportImageArea(importImageAreas) {
    let importImageCount = 1;
    importImageAreas.forEach((importImageArea) => {
        targetClass = importImageArea.classList[1];
        importImageArea.classList.remove(targetClass);
        importImageArea.classList.add(`no${importImageCount}`);
        importImageCount += 1;
    });
}

//ツールバーフィールド画像削除
function deleteImportedImage(target) {
    targetImage = document.querySelector(target);
    targetImage.remove();

    const importImageAreas = document.querySelectorAll('.import-image-area');
    adjustImportImageArea(importImageAreas);
}

//画像インポート
function importImage(data) {
    const imageNo = data.classList[1];
    const file = data.files[0];
    if(!file) return;
    const fileName = file.name;
    
    const reader = new FileReader();
    reader.addEventListener('error', () => {
        return;
    });
    reader.addEventListener('load', (e) => {
        let img = document.createElement("img");
        img.id = "import-image-area";
        img.src = e.target.result;
        img.alt = fileName;
        img.setAttribute("style", "width: 200px;");
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

