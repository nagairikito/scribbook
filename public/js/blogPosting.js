//toolフィールドのwidth設定
let toolsField = document.querySelector('#blog-posting .tools-field');

let toolsFieldW = w * 0.175;  //wはdisplayScreenSizeSettingで定義済み
toolsField.style.width =  toolsFieldW + 'px';

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
// let displayImagePath = "http://localhost/storage/blog_contents_images/"
let displayImagePath = APP_URL + "/storage/blog_contents_images/";

//ブログ編集フィールドの画像のファイル名
let imageFileName = '';

//投稿フラグ(２重送信防止)
let submitFlag = false;

const blogPostingForm = document.getElementById("blog-posting-form");
const inputData = document.getElementById('original-contents');

//spツールバーの表示/非表示
const blogToolsBtn = document.getElementById('blog-tools');

blogToolsBtn.addEventListener('click', () => {
    let toolList = document.getElementById('tool-list');
    let importedImageField = document.querySelector('.imported-img-field-wrapper');
    toolList.classList.toggle('active');
    importedImageField.classList.toggle('active');
})

//submit後の処理：入力内容をテキストエリアにコピー（inputで送信できないため）
blogPostingForm.addEventListener('submit', handleSubmit);

function handleSubmit(e) {
    e.preventDefault();
    if(document.querySelectorAll('.error-message').length > 0) {
        document.querySelectorAll('.error-message').forEach((error) => {
            error.remove();
        });
    }

    if(submitFlag == true) {
        return;
    }

    //バリデーション
    let validationFlag = true;
    let titleElm = document.querySelector('.blog-title');
    let title = document.querySelector('input[name="title"].blog-title').value;
    let contents = document.querySelector('.original-contents');

    if(title.trim() === '') {
        validationFlag = false;
        let p = document.createElement("p");
        p.classList.add("error-message");
        p.textContent = "タイトルは必須です"
        titleElm.after(p);
    }
    if(title.trim().length > 255) {
        validationFlag = false;
        let p = document.createElement("p");
        p.classList.add("error-message");
        p.textContent = "タイトルは255文字以下で入力してください"
        titleElm.after(p);
    }
    if(contents.textContent.trim() === '') {
        validationFlag = false;
        let p = document.createElement("p");
        p.classList.add("error-message");
        p.textContent = "コンテンツは必須です"
        contents.after(p);
    }

    //img srcをbase64から「保存先＋ファイル名」に差し替え
    if(validationFlag == true) {
        submitFlag = true;

        let blogPostingForm = document.getElementById("blog-posting-form");
        let contentsImages = document.querySelectorAll('.contents-image');
        let thumbnailName = document.getElementById("submit-thumbnail-name");
        let thumbnailImg = document.getElementById("submit-thumbnail-img");

        //ブログのユニークIDを生成
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

        //サムネイルデータ加工
        thumbnailPreviewImg = document.getElementById("thumbnail-preview-img");
        if(thumbnailPreviewImg !== "undefined" && thumbnailPreviewImg !== null && thumbnailPreviewImg !== "") {
            thumbnailName.value = thumbnailPreviewImg.alt
            thumbnailImg.value = thumbnailPreviewImg.src
        }

        //画像データをlaravel側で取得できるように加工
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

        //ブログ編集フィールドの画像削除ボタン非表示でテーブルに登録（編集時display:none;を外して編集フォームでは表示できるようにする）
        let contentsImageAreaButtons = document.querySelectorAll('.contents-image-area-buttons');
        contentsImageAreaButtons.forEach((buttons) => {
            buttons.style = "display: none;";
        })

        //contenteditable="true"のdivからtextareaにコピー
        const originalContents = document.getElementById("original-contents");
        const replacementContents = document.getElementById("replacement-contents");
        replacementContents.value = originalContents.innerHTML;
        blogPostingForm.submit();
    }
}

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
    // if(e.target.id == 'import-image-area') {
        imageFileName = e.target.alt;
    // }
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
        let registerImageFileName = randomStr + "_" + file.name ; 

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

//サムネイル登録（ファイル選択ボタンから）
function importThumbnail(data) {
    const file = data.files[0];
    if(!file) return;
    const fileName = file.name;
    
    const reader = new FileReader();
    reader.addEventListener('error', () => {
        return;
    });
    reader.addEventListener('load', (e) => {
        let previewBox = document.getElementById("thumbnail-preview-box");
        previewBox.innerHTML = '';
        previewBox.classList.remove('thumbnail-preview-box');

        let deleteBtn = document.createElement('div');
        deleteBtn.classList.add('delete-btn-wrapper');
        deleteBtn.setAttribute('onclick', 'deleteThumbnail()');
        let span = document.createElement('span');
        span.classList.add('delete-btn-content');
        span.textContent = '✕';
        deleteBtn.appendChild(span);
        previewBox.appendChild(deleteBtn);

        //ランダムな羅列生成
        let randomStr = generateRandomString();

        //ブログ編集フィールドの画像altと登録時の画像ファイル名
        let registerImageFileName = randomStr + "_" + fileName; 

        let img = document.createElement("img");
        img.id = "thumbnail-preview-img";
        img.src = e.target.result;
        img.alt = registerImageFileName;
        img.setAttribute("style", "width: 300px; height: 300px;");
        previewBox.appendChild(img);
    });
    reader.readAsDataURL(file);
}

//サムネイル登録（ドラッグアンドドロップ）
var thumbailField = document.getElementById("thumbnail-preview-box");

thumbailField.addEventListener('drop', (e) => {
    // let targetPoint = window.getSelection();
    // if(targetPoint.anchorNode === null || !thumbailField.contains(targetPoint.anchorNode)) {
    //     return;
    // }
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
                let previewBox = document.getElementById("thumbnail-preview-box");
        previewBox.innerHTML = '';
        previewBox.classList.remove('thumbnail-preview-box');

        let deleteBtn = document.createElement('div');
        deleteBtn.classList.add('delete-btn-wrapper');
        deleteBtn.setAttribute('onclick', 'deleteThumbnail()');
        let span = document.createElement('span');
        span.classList.add('delete-btn-content');
        span.textContent = '✕';
        deleteBtn.appendChild(span);
        previewBox.appendChild(deleteBtn);

        //ランダムな羅列生成
        let randomStr = generateRandomString();

        //ブログ編集フィールドの画像altと登録時の画像ファイル名
        let registerImageFileName = randomStr + "_" +  file.name; 

        let img = document.createElement("img");
        img.id = "thumbnail-preview-img";
        img.src = e.target.result;
        img.alt = registerImageFileName;
        img.setAttribute("style", "width: 300px; height: 300px;");

        previewBox.appendChild(img);
    });
    reader.readAsDataURL(file);
});


//サムネイル削除
function deleteThumbnail() {
    let inputThumbnail = document.getElementById("import-thumbail-input").value;
    inputThumbnail.value = '';

    let p = document.createElement('p');
    p.innerHTML = 'ここにサムネイル用画像をドラッグアンドドロップしてください<br><br><br>ファイルを選択ボタンからも登録できます';

    let previewBox = document.getElementById("thumbnail-preview-box");
    previewBox.innerHTML = '';
    previewBox.classList.add('thumbnail-preview-box');
    previewBox.appendChild(p);
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
        if(screen.availWidth > 767) {
            img.setAttribute("style", "width: 200px; height: 200px;");
        } else {
            img.setAttribute("style", "width: 50px; height: 50px;");
        }
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

