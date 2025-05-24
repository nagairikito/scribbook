const blogPostingForm = document.getElementById("blog-posting-form");
// let image = '<img src="http://localhost/storage/blog_contents_images/noImage.png">';
const inputData = document.getElementById('original-contents');

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

    const parentElement = document.createElement("div");
    parentElement.classList.add("parent-elm");

    const deleteButton = document.createElement("button");
    deleteButton.setAttribute('onclick', 'deleteImage(this)')
    deleteButton.textContent = "削除";
    parentElement.appendChild(deleteButton);

    const transitionButton = document.createElement("button");
    transitionButton.textContent = "移動";
    parentElement.appendChild(transitionButton);

    const inputImageField = document.createElement("input");
    inputImageField.type = "file";
    parentElement.appendChild(inputImageField);

    toolSettingField.appendChild(parentElement);
}

function deleteImage(target) {
    target.remove();
}



// console.log(document.querySelectorAll("#original-contents > div > img"))
// document.querySelectorAll("#original-contents > div > img").draggable = true;
// document.querySelector("#original-contents > div > img").addEventListener("dragstart", onDragStart);

// document.querySelectorAll(".box.drop").forEach((element) => {
//     element.addEventListener("drop", onDrop);
//     element.addEventListener("dragover", onDragover);
//     element.addEventListener("dragenter", onDragenter);
//     element.addEventListener("dragleave", onDragleave);
// });

// /**
//  * ドラッグ処理
//  * @param {Event} event 
//  */
// function onDragStart(event) {
//     console.log("test");
//     event.dataTransfer.setData("text", event.currentTarget.id);
// }

// /**
//  * ドロップ処理
//  * @param {Event} event 
//  */
// function onDrop(event) {
//     event.currentTarget.classList.remove("dragging");
//     const boxs = [...document.querySelectorAll(".box")];
//     if (boxs.indexOf(event.currentTarget) === 0) {
//         event.currentTarget.before(document.getElementById(event.dataTransfer.getData("text")));
//     } else {
//         event.currentTarget.after(document.getElementById(event.dataTransfer.getData("text")));
//     }
// }

// /**
//  * 操作が要素上に入ってきたとき
//  * @param {Event} event 
//  */
// function onDragenter(event) {
//     event.currentTarget.classList.toggle("dragging");
// }

// /**
//  * 操作が要素上から出たとき
//  * @param {Event} event 
//  */
// function onDragleave(event) {
//     event.currentTarget.classList.toggle("dragging");
// }

// /**
//  * 操作が要素上を通過してるとき
//  * @param {Event} event 
//  */
// function onDragover(event) {
//     event.preventDefault();
// }

//文字色押下で色選択を表示
// function showColorSelector() {
//     const colorSelector = document.getElementById("color-selector").click();
//     const colorSelectorButton = document.getElementById("color-selector-button");
//     // colorSelectorButton.appendChild(colorSelector);
//     console.log(colorSelector);
//     console.log(colorSelectorButton);
// }

function showColorSelector() {
    const colorSelector = document.getElementById("color-selector");
    colorSelector.style.display = colorSelector.style.display === 'none' ? 'block' : 'none';
}

function updateColor(color) {
    document.getElementById('selectedColor').textContent = color;
}


// document.onselectionchange = function() {
//   var cpytxt = window.getSelection();
//   console.log(cpytxt);
// }
let test = document.getElementById("original-contents");

// var cpytxt = document.getSelection();
// console.log(cpytxt.getRangeAt(0));

document.addEventListener('mouseup', function (ev) {
    const selection = document.getSelection();

    if(!selection.rangeCount) {
        return;
    }

    const range = selection.getRangeAt(0);

    if(range.collapsed) {
        return;
    }

    const span = document.createElement("span");
    span.style.backgroundColor = "yellow";
    span.textContent = range.toString();

    range.deleteContents();
    range.insertNode(span);

}, false);
