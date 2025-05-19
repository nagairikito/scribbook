const blogPostingForm = document.getElementById("blog-posting-form");
// let image = '<img src="http://localhost/storage/blog_contents_images/noImage.png">';
const inputData = document.getElementById('original-contents');

//コンテンツの最初の行にdivタグ付与
const div = document.createElement("div");
div.appendChild(inputData.childNodes[0]);
inputData.prepend(div);

inputData.addEventListener('input', function (e) {
    // console.log('内容が変更されました: ', this.innerHTML);
    // count = this.childNodes.length;
    nodes = Array.from(this.childNodes).map((child) => {
        // console.log(child.childNodes.length);
        return Array.from(child.childNodes);
    });
    console.log(nodes);
});

blogPostingForm.addEventListener('submit', function() {
    const originalContents = document.getElementById("original-contents");
    const replacementContents = document.getElementById("replacement-contents");
    replacementContents.value = originalContents.innerHTML;
})

function addImage() {
    let toolSettingField = document.querySelector('.tool-setting-field');

    const parentElement = document.createElement("div");
    parentElement.classList.add("parent-elm");

    const transitionButton = document.createElement("button");
    transitionButton.textContent = "移動";
    parentElement.appendChild(transitionButton);

    const inputImageField = document.createElement("input");
    inputImageField.type = "file";
    parentElement.appendChild(inputImageField);

    toolSettingField.appendChild(parentElement);
}



console.log(document.querySelectorAll("#original-contents > div > img"))
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


